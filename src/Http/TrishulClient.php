<?php


namespace TrishulApi\Core\Http;

class TrishulClient implements TrishulClientInterface
{

    function get_mono($url, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "GET", null, $headers);
    }
    function get_flux($url, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "GET", null, $headers);
    }
    function post_mono($url, $data, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "POST", $data, $headers);
    }
    function post_flux($url, array $data, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "POST", $data, $headers);
    }
    function delete_mono($url, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "DELETE", null, $headers);
    }
    function delete_flux($url, array $data, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "DELETE", null, $headers);
    }
    function put_mono($url, $data, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "PUT", $data, $headers);
    }
    function put_flux($url, array $data, TrishulClientHeader $headers) {
        $headers = $headers->get_headers();
        return $this->__makeCurlRequest($url, "PUT", $data, $headers);
    }


    private function __makeCurlRequest($url, $requestType, $data, $headers)
    {


        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        //
        curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        //
        if ($requestType == 'POST') {
            // curl_setopt($curlHandle, CURLOPT_POST, true);
            curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
        ]);
        }
        if ($requestType == 'GET') {
            // curl_setopt($curlHandle, CURLOPT_HTTPGET, true);
            curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
        ]);
        }
        if ($requestType == 'PUT') {
            // curl_setopt($curlHandle, CURLOPT_HTTPGET, true);
            curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_RETURNTRANSFER => true,
        ]);
        }
        if ($requestType == 'DELETE') {
            // curl_setopt($curlHandle, CURLOPT_, true);
            curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => true,
        ]);
        }

        // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

        $curlResponse = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $curlResponse;
    }
}
