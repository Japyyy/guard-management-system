import os
import zipfile
from xml.sax.saxutils import escape


TITLE = "Guard Management System Documentation"

SECTIONS = [
    ("Introduction", [
        "The Guard Management System is a web-based application developed to manage security guard records, monitor license validity, and automate license-related notifications. The system reduces manual work by storing personnel information, tracking expiration dates, and using Optical Character Recognition (OCR) to extract details from uploaded guard license images.",
        "The project was designed to support guard administration in a more efficient and organized way. It combines record management, compliance monitoring, dashboard reporting, and automated email reminders in one platform.",
    ]),
    ("Development Model", [
        "The system was developed using the Agile development model.",
        "Agile was chosen because the project required continuous improvement, especially in the OCR scanning, name parsing, and automatic email reminder features. Instead of building the entire system in one fixed sequence, development was completed in small iterations. Each major feature was built, tested, and improved step by step.",
        "This approach made it easier to add features gradually, fix issues immediately after testing, improve OCR accuracy using real scan results, and adapt the system based on user feedback.",
    ]),
    ("Technologies Used", [
        "PHP",
        "Laravel",
        "MySQL",
        "Python",
        "PaddleOCR",
        "OpenCV",
        "JavaScript",
        "Blade Template Engine",
        "HTML and CSS",
        "SMTP or Gmail Mail",
        "DomPDF",
        "Symfony Process",
    ]),
    ("Description of Technologies", [
        "PHP was used as the main server-side programming language for the web application.",
        "Laravel served as the primary web framework. It handled routing, controllers, validation, database operations, scheduling, email sending, and authentication.",
        "MySQL was used as the relational database for storing guard records, company records, license data, and email notification status.",
        "Python was used for the OCR feature because it provides strong support for image processing and AI-based OCR tools.",
        "PaddleOCR is the AI-based OCR engine used to detect and recognize text from uploaded guard license images.",
        "OpenCV was used to preprocess images before OCR, improving scan quality through resizing, enhancement, thresholding, and basic card detection.",
        "JavaScript was used for client-side interactivity, such as sending scan requests and updating form fields dynamically.",
        "Blade is Laravel's templating engine. It was used to create dynamic views for the dashboard, forms, and other pages.",
        "SMTP configuration was used to send automated email reminders to HR or administrators.",
        "DomPDF was used to generate PDF attachments for license expiry memo emails.",
        "Symfony Process was used by Laravel to execute the Python OCR script from the PHP backend.",
    ]),
    ("System Architecture", [
        "The system is composed of three main layers: the presentation layer, the application layer, and the data and AI layer.",
        "The presentation layer includes the user interface built with Blade, HTML, CSS, and JavaScript. It is where users interact with forms, dashboards, and management pages.",
        "The application layer is the Laravel backend. It processes requests, validates data, manages database operations, handles email sending, and connects to the Python OCR module.",
        "The data and AI layer includes the MySQL database and the Python OCR engine. MySQL stores structured records, while Python handles image analysis and text extraction.",
    ]),
    ("Major Features of the System", [
        "Guard record management",
        "Company management",
        "License information tracking",
        "OCR-based auto-fill for guard license details",
        "Dashboard monitoring for license status",
        "Automatic 30-day and 60-day email reminders",
        "Email sent-status tracking on the dashboard",
    ]),
    ("OCR Feature and How It Works", [
        "One of the key features of the system is the ability to scan a guard's license and automatically extract important fields.",
        "The OCR workflow is as follows:",
        "The user uploads a license image in the Add Guard form.",
        "Laravel receives the image and stores it temporarily.",
        "Laravel calls the Python OCR script using Symfony Process.",
        "The Python script preprocesses the image using OpenCV.",
        "PaddleOCR reads and recognizes text from the image.",
        "The script extracts the Last Name, First Name, Middle Name, License Number, and Expiry Date.",
        "The Python script returns the extracted values in JSON format.",
        "Laravel sends the response back to the frontend.",
        "The form fields are automatically filled.",
        "The OCR feature was customized and refined to improve surname extraction and avoid inaccurate parsing from noisy scan results.",
    ]),
    ("AI Component", [
        "The system includes an AI component through the use of PaddleOCR.",
        "PaddleOCR is a machine learning-based Optical Character Recognition tool that identifies and reads printed text from images. In this project, it was used to recognize the text on guard licenses and convert it into structured data.",
        "While the entire system is not purely an AI system, it integrates AI in a practical way for document scanning and text extraction.",
    ]),
    ("Automatic Email Reminder System", [
        "The application includes an automated reminder system for expiring guard licenses.",
        "The system checks each guard's license validity date and sends email reminders when the license is exactly 60 days before expiration and exactly 30 days before expiration.",
        "To prevent duplicate reminders, the system tracks whether the email has already been sent using the notified_60_days and notified_30_days database flags.",
        "Once an email is sent, the corresponding flag is updated.",
        "For testing and presentation purposes, the scheduled task can be configured to run every minute. In production, the schedule can be changed to a more suitable interval.",
    ]),
    ("Dashboard Monitoring", [
        "The dashboard provides a quick overview of operational and compliance-related information.",
        "It displays the total number of guards, number of active licenses, number of expired licenses, number of licenses expiring in 30 days, number of licenses expiring in 60 days, birthday alerts, license alerts, and email reminder status for 30-day and 60-day notices.",
        "This allows administrators to monitor important guard-related information in real time.",
    ]),
    ("Database Structure", [
        "The central table in the system is the guards table. It stores the company ID, last name, first name, middle name, civil status, birthdate, date hired, address, license number, license validity date, government reference numbers, and notification flags.",
        "A related companies table stores company information associated with each guard.",
    ]),
    ("Development Process", [
        "The project was developed in the following general sequence:",
        "Set up the Laravel project.",
        "Create the MySQL database and migrations.",
        "Develop guard and company CRUD functions.",
        "Implement authentication.",
        "Build the dashboard.",
        "Add automatic email reminder logic.",
        "Configure the Laravel scheduler.",
        "Develop the Python OCR script.",
        "Connect Laravel to Python OCR.",
        "Test scanning using actual license images.",
        "Improve OCR parsing and accuracy.",
        "Finalize the user interface and workflow.",
    ]),
    ("Challenges Encountered", [
        "Several challenges were encountered during development, including OCR misreading names due to noisy image text, incorrect surname extraction caused by OCR line merging, timeouts when running Python OCR from Laravel, old configuration paths causing script execution issues, balancing scan accuracy and speed, and ensuring emails were not sent repeatedly.",
        "These challenges were resolved through iterative debugging, parser improvements, process timeout adjustments, and better environment configuration.",
    ]),
    ("Conclusion", [
        "The Guard Management System was successfully developed as a web-based platform for managing security guard records and license compliance. It combines traditional web technologies with an AI-powered OCR module to reduce manual encoding and improve efficiency.",
        "The use of Laravel, MySQL, Python, PaddleOCR, and OpenCV made it possible to create a system that is both functional and practical. Through Agile development, the system was improved continuously until its core features worked reliably.",
        "Overall, the project demonstrates how modern web development and AI-based OCR can be combined to solve real administrative problems in guard management.",
    ]),
]


def paragraph(text: str, bold: bool = False, center: bool = False) -> str:
    text = escape(text)
    justification = '<w:jc w:val="center"/>' if center else ""
    run_props = "<w:b/>" if bold else ""
    return (
        "<w:p>"
        f"<w:pPr>{justification}</w:pPr>"
        "<w:r>"
        f"<w:rPr>{run_props}</w:rPr>"
        f"<w:t xml:space=\"preserve\">{text}</w:t>"
        "</w:r>"
        "</w:p>"
    )


def blank_paragraph() -> str:
    return "<w:p/>"


def build_document_xml() -> str:
    body = []
    body.append(paragraph(TITLE, bold=True, center=True))
    body.append(blank_paragraph())

    for index, (heading, paragraphs) in enumerate(SECTIONS, start=1):
        body.append(paragraph(f"{index}. {heading}", bold=True))
        for item in paragraphs:
            if heading in {"Technologies Used", "Major Features of the System"}:
                body.append(paragraph(f"- {item}"))
            elif heading == "Development Process" and item != "The project was developed in the following general sequence:":
                body.append(paragraph(f"- {item}"))
            else:
                body.append(paragraph(item))
        body.append(blank_paragraph())

    sect = (
        "<w:sectPr>"
        "<w:pgSz w:w=\"11906\" w:h=\"16838\"/>"
        "<w:pgMar w:top=\"1440\" w:right=\"1440\" w:bottom=\"1440\" w:left=\"1440\" "
        "w:header=\"708\" w:footer=\"708\" w:gutter=\"0\"/>"
        "</w:sectPr>"
    )
    return (
        "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
        "<w:document xmlns:wpc=\"http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas\" "
        "xmlns:mc=\"http://schemas.openxmlformats.org/markup-compatibility/2006\" "
        "xmlns:o=\"urn:schemas-microsoft-com:office:office\" "
        "xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\" "
        "xmlns:m=\"http://schemas.openxmlformats.org/officeDocument/2006/math\" "
        "xmlns:v=\"urn:schemas-microsoft-com:vml\" "
        "xmlns:wp14=\"http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing\" "
        "xmlns:wp=\"http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing\" "
        "xmlns:w10=\"urn:schemas-microsoft-com:office:word\" "
        "xmlns:w=\"http://schemas.openxmlformats.org/wordprocessingml/2006/main\" "
        "xmlns:w14=\"http://schemas.microsoft.com/office/word/2010/wordml\" "
        "xmlns:wpg=\"http://schemas.microsoft.com/office/word/2010/wordprocessingGroup\" "
        "xmlns:wpi=\"http://schemas.microsoft.com/office/word/2010/wordprocessingInk\" "
        "xmlns:wne=\"http://schemas.microsoft.com/office/word/2006/wordml\" "
        "xmlns:wps=\"http://schemas.microsoft.com/office/word/2010/wordprocessingShape\" "
        "mc:Ignorable=\"w14 wp14\">"
        "<w:body>"
        + "".join(body)
        + sect
        + "</w:body></w:document>"
    )


def create_docx(output_path: str) -> None:
    content_types = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>
"""
    rels = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
"""
    app = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
            xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>OpenAI Codex</Application>
</Properties>
"""
    core = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                   xmlns:dc="http://purl.org/dc/elements/1.1/"
                   xmlns:dcterms="http://purl.org/dc/terms/"
                   xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Guard Management System Documentation</dc:title>
  <dc:creator>OpenAI Codex</dc:creator>
</cp:coreProperties>
"""

    os.makedirs(os.path.dirname(output_path), exist_ok=True)

    with zipfile.ZipFile(output_path, "w", compression=zipfile.ZIP_DEFLATED) as docx:
        docx.writestr("[Content_Types].xml", content_types)
        docx.writestr("_rels/.rels", rels)
        docx.writestr("docProps/app.xml", app)
        docx.writestr("docProps/core.xml", core)
        docx.writestr("word/document.xml", build_document_xml())


if __name__ == "__main__":
    target = os.path.join("storage", "app", "docs", "Guard-Management-System-Documentation.docx")
    create_docx(target)
    print(os.path.abspath(target))
