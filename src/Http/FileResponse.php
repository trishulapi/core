<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\ResourceNotFoundException;

class FileResponse{

    public function __construct($content_desc, $content_type, $file, $content_length, $content_disposition = "attachment", $cache_control = "must-revalidate", $pragma = "public", $expires = 0)
    {
        if (file_exists($file)) {
            header('Content-Description: '.$content_desc);
            header('Content-Type: '.$content_type); // or actual MIME type
            header('Content-Disposition: '.$content_disposition.'; filename="' . basename($file) . '"');
            header('Content-Length: ' . $content_length);
            header('Cache-Control: '.$cache_control);
            header('Pragma: '.$pragma);
            header('Expires: '.$expires);
            flush(); // Clean output buffer
            readfile($file);
            exit;
        } else {
            throw new ResourceNotFoundException("File not found");
        }
    }
}