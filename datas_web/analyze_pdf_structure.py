
import fitz  # PyMuPDF

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"

try:
    doc = fitz.open(pdf_path)
    print(f"Total pages: {len(doc)}")
    
    # Analyze page 21 specifically to see option formatting
    i = 20 # Page 21 is index 20
    page = doc.load_page(i)
    text = page.get_text()
    print(f"--- Page {i+1} Raw Text ---")
    print(text)
    print("\n--- JSON Block for detailed position ---")
    print(page.get_text("blocks")[:5]) # See blocks for layout analysis
        
except Exception as e:
    print(f"Error: {e}")
