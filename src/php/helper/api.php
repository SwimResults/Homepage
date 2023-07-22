<?php

    class Api {


        public static function get($url, $path) {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url.$path);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1); //timeout in seconds

            $response = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($code >= 300) return FALSE;
            curl_close($curl);

            return json_decode($response, TRUE);
        }
    }