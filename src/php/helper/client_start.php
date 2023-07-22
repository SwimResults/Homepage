<?php

    require_once("php/helper/api.php");

    class StartClient {
        public static string $API_URL;

        public static function init(): void
        {
            self::$API_URL = getenv("SR_START_URL");
        }

        public static function getStartCount(): int {
            if (!self::$API_URL) return 869;
            $data = Api::get(self::$API_URL, "/start");
            if ($data) return count($data);
            return 869;
        }



    }

    StartClient::init();