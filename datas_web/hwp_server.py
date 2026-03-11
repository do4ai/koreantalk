#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
HWP 파일 변환 웹 서버
브라우저에서 HWP 파일 업로드 → 자동으로 페이지별 TXT 변환
"""

from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
import os
import tempfile
import subprocess

app = Flask(__name__)
CORS(app)

# HTML 페이지
HTML_TEMPLATE = """
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HWP → TXT 변환 서버</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        .upload-area:hover {
            background: #e8f4f8;
            border-color: #764ba2;
        }
        .upload-area.dragover {
            background: #d0e8ff;
            transform: scale(1.02);
        }
        .upload-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }
        .upload-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: transform 0.3s;
        }
        .upload-button:hover {
            transform: translateY(-2px);
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            background: #e8f4f8;
            border-radius: 10px;
            display: none;
        }
        .result.show { display: block; }
        .result h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .result pre {
            background: white;
            padding: 15px;
            border-radius: 5px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .download-btn {
            background: #34a853;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
        }
        .download-btn:hover {
            background: #2d8e47;
        }
        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        .loading.show { display: block; }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error {
            color: #dc3545;
            padding: 15px;
            background: #f8d7da;
            border-radius: 5px;
            margin-top: 15px;
            display: none;
        }
        .error.show { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📄 HWP → TXT 변환</h1>
        <p class="subtitle">HWP 파일을 페이지별 텍스트 파일로 자동 변환합니다</p>

        <div class="upload-area" id="uploadArea">
            <div class="upload-icon">📁</div>
            <p>HWP 파일을 드래그 앤 드롭하거나 클릭하여 선택하세요</p>
            <input type="file" id="fileInput" accept=".hwp" style="display: none;">
            <button class="upload-button" onclick="document.getElementById('fileInput').click()">
                파일 선택
            </button>
        </div>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>변환 중...</p>
        </div>

        <div class="error" id="error"></div>

        <div class="result" id="result">
            <h3>✅ 변환 완료!</h3>
            <pre id="resultText"></pre>
            <button class="download-btn" id="downloadBtn">📥 TXT 파일 다운로드</button>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const loading = document.getElementById('loading');
        const result = document.getElementById('result');
        const resultText = document.getElementById('resultText');
        const downloadBtn = document.getElementById('downloadBtn');
        const errorDiv = document.getElementById('error');

        let convertedText = '';
        let fileName = '';

        // 드래그 앤 드롭
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file) handleFile(file);
        });

        // 파일 선택
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) handleFile(file);
        });

        // 파일 처리
        async function handleFile(file) {
            if (!file.name.endsWith('.hwp')) {
                showError('HWP 파일만 업로드할 수 있습니다.');
                return;
            }

            fileName = file.name.replace('.hwp', '');
            result.classList.remove('show');
            errorDiv.classList.remove('show');
            loading.classList.add('show');

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('/convert', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                loading.classList.remove('show');

                if (data.success) {
                    convertedText = data.text;
                    resultText.textContent = data.text.substring(0, 1000) +
                                            (data.text.length > 1000 ? '\\n\\n... (전체 내용은 다운로드하여 확인)' : '');
                    result.classList.add('show');
                } else {
                    showError(data.error || '변환 중 오류가 발생했습니다.');
                }
            } catch (error) {
                loading.classList.remove('show');
                showError('서버 오류: ' + error.message);
            }

            fileInput.value = '';
        }

        // 다운로드
        downloadBtn.addEventListener('click', () => {
            const blob = new Blob([convertedText], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName + '.txt';
            a.click();
            URL.revokeObjectURL(url);
        });

        function showError(message) {
            errorDiv.textContent = '❌ ' + message;
            errorDiv.classList.add('show');
        }
    </script>
</body>
</html>
"""

def hwp_to_text_pyhwp(hwp_file):
    """pyhwp를 사용하여 HWP를 텍스트로 변환"""
    try:
        import hwp5
        from hwp5 import plat

        hwp5file = hwp5.open(hwp_file)
        result = []

        for sec in hwp5file.bodytext:
            for para in sec.paragraphs:
                text = para.get_text()
                if text and text.strip():
                    result.append(text.strip())

        return '\n\n'.join(result) if result else None

    except Exception as e:
        print(f"pyhwp 오류: {e}")
        return None

def hwp_to_text_olefile(hwp_file):
    """olefile을 사용하여 HWP에서 텍스트 추출 (개선된 버전)"""
    try:
        import olefile
        import zlib
        import struct

        f = olefile.OleFileIO(hwp_file)
        dirs = f.listdir()

        # BodyText 섹션 찾기
        sections = [d for d in dirs if d[0] == "BodyText"]
        sections.sort()

        all_text = []

        for section in sections:
            try:
                section_name = "/".join(section)
                data = f.openstream(section_name).read()
                unpacked = zlib.decompress(data, -15)

                # 텍스트 추출
                i = 0
                section_text = []

                while i < len(unpacked):
                    if i + 4 > len(unpacked):
                        break

                    record_id = struct.unpack('<H', unpacked[i:i+2])[0]
                    record_len = struct.unpack('<H', unpacked[i+2:i+4])[0]
                    i += 4

                    if i + record_len > len(unpacked):
                        break

                    record_data = unpacked[i:i+record_len]
                    i += record_len

                    # HWPTAG_PARA_TEXT (0x0050)
                    if record_id == 0x0050:
                        try:
                            text = record_data.decode('utf-16le', errors='ignore')
                            text = ''.join(c for c in text if ord(c) >= 32 or c in '\n\r\t')
                            if text.strip():
                                section_text.append(text.strip())
                        except:
                            pass

                if section_text:
                    all_text.append('\n'.join(section_text))

            except Exception as e:
                print(f"섹션 처리 오류: {e}")
                continue

        f.close()

        if all_text:
            return '\n\n'.join(all_text)
        return None

    except Exception as e:
        print(f"olefile 오류: {e}")
        return None

@app.route('/')
def index():
    """메인 페이지"""
    return render_template_string(HTML_TEMPLATE)

@app.route('/convert', methods=['POST'])
def convert():
    """HWP 파일을 텍스트로 변환"""
    try:
        if 'file' not in request.files:
            return jsonify({'success': False, 'error': '파일이 없습니다.'})

        file = request.files['file']

        if file.filename == '':
            return jsonify({'success': False, 'error': '파일이 선택되지 않았습니다.'})

        if not file.filename.endswith('.hwp'):
            return jsonify({'success': False, 'error': 'HWP 파일만 업로드 가능합니다.'})

        # 임시 파일로 저장
        with tempfile.NamedTemporaryFile(delete=False, suffix='.hwp') as tmp:
            file.save(tmp.name)
            tmp_path = tmp.name

        try:
            # olefile 사용하여 텍스트 추출
            text = hwp_to_text_olefile(tmp_path)

            if not text or len(text.strip()) == 0:
                return jsonify({
                    'success': False,
                    'error': 'HWP 파일에서 텍스트를 추출할 수 없습니다. 이미지만 있거나 암호화된 파일일 수 있습니다.'
                })

            # 페이지별로 구분 (간단하게 단락 기준)
            paragraphs = [p.strip() for p in text.split('\n\n') if p.strip()]

            # 페이지별 텍스트 생성
            output_lines = []
            for i, para in enumerate(paragraphs, 1):
                output_lines.append(f"=== 페이지 {i} ===")
                output_lines.append(para)
                output_lines.append("")

            result_text = '\n'.join(output_lines)

            return jsonify({
                'success': True,
                'text': result_text,
                'pages': len(paragraphs)
            })

        finally:
            # 임시 파일 삭제
            try:
                os.unlink(tmp_path)
            except:
                pass

    except Exception as e:
        return jsonify({
            'success': False,
            'error': f'서버 오류: {str(e)}'
        })

if __name__ == '__main__':
    print("=" * 60)
    print("HWP → TXT 변환 웹 서버 시작")
    print("=" * 60)
    print("\n브라우저에서 다음 주소를 여세요:")
    print("  http://localhost:5000")
    print("\n서버를 종료하려면 Ctrl+C를 누르세요.")
    print("=" * 60)

    app.run(host='0.0.0.0', port=5000, debug=True)
