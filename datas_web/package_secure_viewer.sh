#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DIST_DIR="${1:-$ROOT_DIR/dist/secure-viewer}"

mkdir -p "$DIST_DIR" "$DIST_DIR/css" "$DIST_DIR/js" "$DIST_DIR/docs"
rm -f \
  "$DIST_DIR/viewer-listening-reading.html" \
  "$DIST_DIR/viewer-speaking.html" \
  "$DIST_DIR/viewer-grammar.html" \
  "$DIST_DIR/pdf-viewer.html" \
  "$DIST_DIR/css/security-viewer.css" \
  "$DIST_DIR/js/security-viewer-app.js" \
  "$DIST_DIR/docs/SECURE_VIEWER_INTEGRATION.md" \
  "$DIST_DIR/docs/SECURE_VIEWER_DEPLOY_CHECKLIST.md" \
  "$DIST_DIR/docs/VIEWER_SESSION_MANIFEST.example.json"

cp "$ROOT_DIR/viewer-listening-reading.html" "$DIST_DIR/viewer-listening-reading.html"
cp "$ROOT_DIR/viewer-speaking.html" "$DIST_DIR/viewer-speaking.html"
cp "$ROOT_DIR/viewer-grammar.html" "$DIST_DIR/viewer-grammar.html"
cp "$ROOT_DIR/pdf-viewer.html" "$DIST_DIR/pdf-viewer.html"
cp "$ROOT_DIR/css/security-viewer.css" "$DIST_DIR/css/security-viewer.css"
cp "$ROOT_DIR/js/security-viewer-app.js" "$DIST_DIR/js/security-viewer-app.js"
cp "$ROOT_DIR/SECURE_VIEWER_INTEGRATION.md" "$DIST_DIR/docs/SECURE_VIEWER_INTEGRATION.md"
cp "$ROOT_DIR/SECURE_VIEWER_DEPLOY_CHECKLIST.md" "$DIST_DIR/docs/SECURE_VIEWER_DEPLOY_CHECKLIST.md"
cp "$ROOT_DIR/VIEWER_SESSION_MANIFEST.example.json" "$DIST_DIR/docs/VIEWER_SESSION_MANIFEST.example.json"

cat <<INFO
Secure viewer package created:
  $DIST_DIR

Included files:
  - viewer-listening-reading.html
  - viewer-speaking.html
  - viewer-grammar.html
  - pdf-viewer.html
  - css/security-viewer.css
  - js/security-viewer-app.js
  - docs/SECURE_VIEWER_INTEGRATION.md
  - docs/SECURE_VIEWER_DEPLOY_CHECKLIST.md
  - docs/VIEWER_SESSION_MANIFEST.example.json
INFO
