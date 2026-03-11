@echo off
chcp 65001 > nul
echo ========================================
echo KoreanTalk 웹 서버 시작
echo ========================================
echo.
echo 브라우저에서 다음 주소를 여세요:
echo   http://localhost:8000/pdf-viewer.html
echo.
echo 또는
echo   http://localhost:8000/pdf-manager.html
echo.
echo 서버를 종료하려면 이 창을 닫거나 Ctrl+C를 누르세요.
echo ========================================
echo.
python -m http.server 8000
pause
