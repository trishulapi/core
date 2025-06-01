<?php

namespace TrishulApi\Core\Http;

interface TrishulClientInterface 
{

    function get_mono($url, TrishulClientHeader $headers);
    function get_flux($url, TrishulClientHeader $headers);
    function post_mono($url, $data, TrishulClientHeader $headers);
    function post_flux($url , array $data, TrishulClientHeader $headers);
    function delete_mono($url, TrishulClientHeader $headers);
    function delete_flux($url, array $data, TrishulClientHeader $headers);
    function put_mono($url, $data, TrishulClientHeader $headers);
    function put_flux($url, array $data, TrishulClientHeader $headers);
}