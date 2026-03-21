#!/usr/bin/env python3
from __future__ import annotations

import argparse
import json
import mimetypes
import os
import posixpath
import urllib.error
import urllib.parse
import urllib.request
from copy import deepcopy
from datetime import datetime, timedelta, timezone
from http import HTTPStatus
from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path


ROOT = Path(__file__).resolve().parent
CONFIG_PATH = ROOT / "dev" / "secure_viewer_sessions.json"
LOCAL_CONFIG_PATH = ROOT / "dev" / "secure_viewer_sessions.local.json"
KST = timezone(timedelta(hours=9))


DEFAULT_CLOSE_URL = "/secure-viewer-local.html"


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="KoreanTalk secure viewer local dev server")
    parser.add_argument("--host", default="127.0.0.1", help="Bind host")
    parser.add_argument("--port", type=int, default=8015, help="Bind port")
    parser.add_argument("--config", default=str(CONFIG_PATH), help="Session config JSON path")
    return parser


def load_json(path: Path) -> dict:
    if not path.exists():
        return {}
    with path.open("r", encoding="utf-8") as handle:
        return json.load(handle)


def merge_dict(base: dict, override: dict) -> dict:
    result = deepcopy(base)
    for key, value in override.items():
        if key == "sessions" and isinstance(value, list) and isinstance(result.get(key), list):
            result[key] = merge_sessions(result[key], value)
            continue
        if isinstance(value, dict) and isinstance(result.get(key), dict):
            result[key] = merge_dict(result[key], value)
        else:
            result[key] = value
    return result


def merge_sessions(base_sessions: list, override_sessions: list) -> list:
    merged: list[dict] = []
    index_by_token: dict[str, int] = {}

    for item in base_sessions:
        token = item.get("token")
        copied = deepcopy(item)
        if token:
            index_by_token[token] = len(merged)
        merged.append(copied)

    for item in override_sessions:
        token = item.get("token")
        copied = deepcopy(item)
        if token and token in index_by_token:
            merged[index_by_token[token]] = merge_dict(merged[index_by_token[token]], copied)
            continue
        if token:
            index_by_token[token] = len(merged)
        merged.append(copied)

    return merged


def load_sessions(config_path: Path) -> dict[str, dict]:
    base = load_json(config_path)
    local = load_json(LOCAL_CONFIG_PATH)
    merged = merge_dict(base, local)
    sessions = {}

    for item in merged.get("sessions", []):
        token = item.get("token", "").strip()
        if not token:
            continue
        sessions[token] = item

    return sessions


def resolve_pdf_source(source: str) -> tuple[str, str | None]:
    if not source:
        raise FileNotFoundError("PDF source is empty.")

    if source.startswith(("http://", "https://")):
        return "remote", source

    candidate = Path(source)
    if not candidate.is_absolute():
        candidate = (ROOT / candidate).resolve()

    if not candidate.exists():
        raise FileNotFoundError(f"Local PDF source not found: {candidate}")

    return "local", str(candidate)


def build_manifest(token: str, session: dict) -> dict:
    expires_at = datetime.now(KST) + timedelta(minutes=30)
    navigation = deepcopy(session.get("navigation", {}))
    navigation.setdefault("detail_url", DEFAULT_CLOSE_URL)
    navigation.setdefault("close_url", DEFAULT_CLOSE_URL)

    book = deepcopy(session.get("book", {}))
    access = deepcopy(session.get("access", {}))
    pdf = {
        "stream_url": f"/api/viewer/session/{urllib.parse.quote(token)}/pdf",
        "page_count": session.get("pdf_page_count", 0),
    }

    manifest = {
        "mode": session.get("mode", "exam"),
        "expires_at": expires_at.isoformat(),
        "book": {
            "title": book.get("title", "전자책 보안 뷰어"),
            "subtitle": book.get("subtitle", "로컬 세션 검수용 뷰어입니다."),
            "author": book.get("author", "KoreanTalk"),
        },
        "access": {
            "member_name": access.get("member_name", "Local QA"),
            "allow_download": bool(access.get("allow_download", False)),
        },
        "navigation": navigation,
        "pdf": pdf,
    }

    if "exam" in session:
        manifest["exam"] = deepcopy(session["exam"])
    if "speaking" in session:
        manifest["speaking"] = deepcopy(session["speaking"])
    if "grammar" in session:
        manifest["grammar"] = deepcopy(session["grammar"])

    return manifest


class SecureViewerHandler(SimpleHTTPRequestHandler):
    server_version = "KoreanTalkSecureViewerDev/1.0"

    def __init__(self, *args, directory=None, sessions=None, **kwargs):
        self.sessions = sessions or {}
        super().__init__(*args, directory=directory, **kwargs)

    def do_GET(self):
        return self.route_request(head_only=False)

    def do_HEAD(self):
        return self.route_request(head_only=True)

    def route_request(self, head_only: bool):
        parsed = urllib.parse.urlparse(self.path)
        path = posixpath.normpath(parsed.path)

        if path == "/":
            self.path = "/secure-viewer-local.html"
            if head_only:
                return super().do_HEAD()
            return super().do_GET()

        if path == "/api/viewer/dev/sessions":
            return self.handle_dev_sessions(head_only=head_only)

        if path.startswith("/api/viewer/session/"):
            return self.handle_session_api(path, head_only=head_only)

        if head_only:
            return super().do_HEAD()
        return super().do_GET()

    def log_message(self, format, *args):
        print(f"[secure-viewer-dev] {self.address_string()} - {format % args}")

    def handle_dev_sessions(self, head_only: bool = False):
        payload = {
            "sessions": [
                {
                    "token": token,
                    "viewer_path": item.get("viewer_path", "/pdf-viewer.html"),
                    "description": item.get("description", ""),
                    "mode": item.get("mode", "exam"),
                    "title": item.get("book", {}).get("title", "전자책 보안 뷰어"),
                }
                for token, item in self.sessions.items()
            ]
        }
        return self.write_json(payload, head_only=head_only)

    def handle_session_api(self, path: str, head_only: bool = False):
        prefix = "/api/viewer/session/"
        suffix = path[len(prefix):]
        if suffix.endswith("/pdf"):
            token = urllib.parse.unquote(suffix[:-4].rstrip("/"))
            return self.handle_pdf_stream(token, head_only=head_only)

        token = urllib.parse.unquote(suffix.strip("/"))
        return self.handle_manifest(token, head_only=head_only)

    def handle_manifest(self, token: str, head_only: bool = False):
        session = self.sessions.get(token)
        if not session:
            return self.write_json({"message": "Unknown viewer session token."}, status=HTTPStatus.NOT_FOUND, head_only=head_only)

        manifest = build_manifest(token, session)
        return self.write_json(manifest, head_only=head_only)

    def handle_pdf_stream(self, token: str, head_only: bool = False):
        session = self.sessions.get(token)
        if not session:
            return self.write_json({"message": "Unknown viewer session token."}, status=HTTPStatus.NOT_FOUND, head_only=head_only)

        source = session.get("pdf_source", "").strip()
        try:
            kind, target = resolve_pdf_source(source)
            if kind == "remote":
                with urllib.request.urlopen(target) as response:
                    data = response.read()
            else:
                data = Path(target).read_bytes()
        except FileNotFoundError as error:
            return self.write_json({"message": str(error)}, status=HTTPStatus.NOT_FOUND, head_only=head_only)
        except urllib.error.URLError as error:
            return self.write_json({"message": f"Failed to fetch remote PDF source: {error.reason}"}, status=HTTPStatus.BAD_GATEWAY, head_only=head_only)

        self.send_response(HTTPStatus.OK)
        self.send_header("Content-Type", "application/pdf")
        self.send_header("Content-Length", str(len(data)))
        self.send_header("Cache-Control", "no-store")
        self.end_headers()
        if not head_only:
            self.wfile.write(data)

    def write_json(self, payload: dict, status: HTTPStatus = HTTPStatus.OK, head_only: bool = False):
        data = json.dumps(payload, ensure_ascii=False, indent=2).encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(data)))
        self.send_header("Cache-Control", "no-store")
        self.end_headers()
        if not head_only:
            self.wfile.write(data)

    def guess_type(self, path):
        mime = super().guess_type(path)
        if mime == "application/octet-stream":
            guessed, _ = mimetypes.guess_type(path)
            if guessed:
                return guessed
        return mime


def main():
    args = build_parser().parse_args()
    sessions = load_sessions(Path(args.config).resolve())

    if not sessions:
        raise SystemExit("No secure viewer sessions found in config.")

    handler = lambda *handler_args, **handler_kwargs: SecureViewerHandler(
        *handler_args,
        directory=str(ROOT),
        sessions=sessions,
        **handler_kwargs,
    )

    with ThreadingHTTPServer((args.host, args.port), handler) as server:
        print(f"Secure viewer dev server: http://{args.host}:{args.port}")
        print(f"Launcher: http://{args.host}:{args.port}/secure-viewer-local.html")
        server.serve_forever()


if __name__ == "__main__":
    main()
