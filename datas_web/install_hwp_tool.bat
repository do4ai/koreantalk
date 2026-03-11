@echo off
chcp 65001 > nul
echo ========================================
echo HWP 변환 도구 설치
echo ========================================
echo.
echo Python 라이브러리 설치 중...
pip install olefile
echo.
echo ========================================
echo 설치 완료!
echo ========================================
echo.
echo 사용 방법:
echo   hwp_convert.bat [HWP파일]
echo.
echo 예시:
echo   hwp_convert.bat document.hwp
echo.
pause
