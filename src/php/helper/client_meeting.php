<?php

    require_once("php/helper/api.php");

    class MeetingClient {
        public static string $API_URL;

        public static function init(): void
        {
            self::$API_URL = getenv("SR_MEETING_URL");
        }

        public static function getMeetingCount(): int {
            if (!self::$API_URL) return 3;
            $data = Api::get(self::$API_URL, "/meeting");
            if ($data) return count($data);
            return 3;
        }



    }

    MeetingClient::init();