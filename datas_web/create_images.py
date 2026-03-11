from PIL import Image, ImageDraw, ImageFont
import os

# images 폴더가 없으면 생성
images_dir = 'images'
if not os.path.exists(images_dir):
    os.makedirs(images_dir)

# 이미지 설정
width, height = 800, 500
images_data = [
    {'filename': 'hospital.jpg', 'text': '병원', 'color': '#4A90E2'},
    {'filename': 'restaurant.jpg', 'text': '식당', 'color': '#E27D4A'},
    {'filename': 'school.jpg', 'text': '학교', 'color': '#7FBA57'}
]

# 각 이미지 생성
for data in images_data:
    # 새 이미지 생성
    img = Image.new('RGB', (width, height), color=data['color'])
    draw = ImageDraw.Draw(img)

    # 텍스트 추가
    try:
        # 한글 폰트 시도 (Windows)
        font = ImageFont.truetype('malgun.ttf', 100)
    except:
        try:
            # 대체 폰트
            font = ImageFont.truetype('arial.ttf', 100)
        except:
            # 기본 폰트
            font = ImageFont.load_default()

    # 텍스트 위치 계산 (중앙)
    text = data['text']
    bbox = draw.textbbox((0, 0), text, font=font)
    text_width = bbox[2] - bbox[0]
    text_height = bbox[3] - bbox[1]
    x = (width - text_width) // 2
    y = (height - text_height) // 2

    # 텍스트 그리기
    draw.text((x, y), text, fill='white', font=font)

    # 부제목 추가
    subtitle = 'KoreanTalk'
    try:
        subtitle_font = ImageFont.truetype('malgun.ttf', 30)
    except:
        try:
            subtitle_font = ImageFont.truetype('arial.ttf', 30)
        except:
            subtitle_font = ImageFont.load_default()

    bbox_sub = draw.textbbox((0, 0), subtitle, font=subtitle_font)
    sub_width = bbox_sub[2] - bbox_sub[0]
    sub_x = (width - sub_width) // 2
    sub_y = y + text_height + 30
    draw.text((sub_x, sub_y), subtitle, fill='white', font=subtitle_font)

    # 이미지 저장
    img.save(os.path.join(images_dir, data['filename']), quality=85)
    print(f'{data["filename"]} 생성 완료')

print('\n모든 이미지가 성공적으로 생성되었습니다!')
