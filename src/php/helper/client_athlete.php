<?php

    require_once("php/helper/api.php");

    class AthleteClient {
        public static string $API_URL;

        public static function init(): void
        {
            self::$API_URL = getenv("SR_ATHLETE_URL");
        }

        public static function getAthleteCount(): int {
            if (!self::$API_URL) return 699;
            $data = Api::get(self::$API_URL, "/athlete/amount");
            if ($data) return $data;
            return 699;
        }



    }

    AthleteClient::init();