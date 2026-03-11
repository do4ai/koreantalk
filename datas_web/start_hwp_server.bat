@echo off
chcp 65001 > nul
echo ========================================
echo HWP 변환 웹 서버 시작
echo ========================================
echo.
echo 필요한 라이브러리 설치 중...
pip install flask flask-cors olefile
echo.
echo ========================================
echo 서버 시작 중...
echo ========================================
echo.
echo 브라우저에서 다음 주소를 여세요:
echo   http://localhost:5000
echo.
echo 서버를 종료하려면 이 창을 닫거나 Ctrl+C를 누르세요.
echo.
python hwp_server.py
pause
