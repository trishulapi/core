<?php


namespace TrishulApi\Core\Enums;

enum FileType: string
{
    // Text & Markup
    case TXT = 'text/plain';
    case HTML = 'text/html';
    case CSS = 'text/css';
    case JS = 'application/javascript';
    case JSON = 'application/json';
    case XML = 'application/xml';
    case CSV = 'text/csv';
    case MARKDOWN = 'text/markdown';
    case YAML = 'text/yaml';
    case YML = 'application/x-yaml';
    case PHP = 'application/x-httpd-php';
    case SH = 'application/x-sh';
    case PY = 'text/x-python';
    case JAVA = 'text/x-java-source';
    case CPP = 'text/x-c++';
    case C = 'text/x-c';
    case RB = 'application/x-ruby';
    case TS = 'application/x-typescript';

    // Documents
    case PDF = 'application/pdf';
    case DOC = 'application/msword';
    case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case XLS = 'application/vnd.ms-excel';
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case PPT = 'application/vnd.ms-powerpoint';
    case PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    case RTF = 'application/rtf';
    case ODT = 'application/vnd.oasis.opendocument.text';
    case EPUB = 'application/epub+zip';
    case MOBI = 'application/x-mobipocket-ebook';
    case AZW = 'application/vnd.amazon.ebook';

    // Images
    case JPG = 'image/jpeg';
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case BMP = 'image/bmp';
    case WEBP = 'image/webp';
    case SVG = 'image/svg+xml';
    case ICO = 'image/x-icon';
    case PSD = 'image/vnd.adobe.photoshop';

    // Audio
    case MP3 = 'audio/mpeg';
    case WAV = 'audio/wav';
    case OGG_AUDIO = 'audio/ogg';
    case AAC = 'audio/aac';
    case MIDI = 'audio/midi';

    // Video
    case MP4 = 'video/mp4';
    case WEBM = 'video/webm';
    case OGG_VIDEO = 'video/ogg';
    case AVI = 'video/x-msvideo';
    case MPEG = 'video/mpeg';

    // Fonts
    case TTF = 'font/ttf';
    case OTF = 'font/otf';
    case WOFF = 'font/woff';
    case WOFF2 = 'font/woff2';
    case EOT = 'application/vnd.ms-fontobject';

    // Archives
    case ZIP = 'application/zip';
    case GZ = 'application/gzip';
    case TAR = 'application/x-tar';
    case RAR = 'application/vnd.rar';
    case SEVEN_ZIP = 'application/x-7z-compressed';

    // App & Executables
    case APK = 'application/vnd.android.package-archive';
    case IPA = 'application/octet-stream';
    case BIN = 'application/octet-stream';
    case EXE = 'application/vnd.microsoft.portable-executable';

    // Web / Media
    case SWF = 'application/x-shockwave-flash';
    case TORRENT = 'application/x-bittorrent';
    case ICS = 'text/calendar';
    case VCF = 'text/vcard';

    // 3D / CAD / Design
    case STL = 'model/stl';
    case OBJ = 'model/obj';
    case DAE = 'model/vnd.collada+xml';
    case GLTF = 'model/gltf+json';
    case GLB = 'model/gltf-binary';
    case AI = 'application/postscript';
    case EPS = 'application/postscript';
    case SKETCH = 'application/x-sketch'; // unofficial

    // Fallback / Unknown
    case UNKNOWN = 'application/octet-stream';
}
