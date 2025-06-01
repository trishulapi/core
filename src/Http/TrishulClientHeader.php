<?php

namespace TrishulApi\Core\Http;


class TrishulClientHeader {

    private array $headers = [];

    public function add($key, $value){
        $this->_push($this->make_header($key, $value));
    }

    private function make_header($key, $value)
    {
        $header = [$key.":".$value];
        return $header;

    }

    private function _push($header)
    {
        array_push($this->headers, $header);
    }


    public function get_headers():array
    {
        return $this->headers;
    }
}