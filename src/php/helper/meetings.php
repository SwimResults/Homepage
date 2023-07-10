<?php

    require_once("php/helper/translation.php");

    class MeetingsHelper {

        public static function getMeetings() {
            $data = file_get_contents("data/meetings.json");
            return json_decode($data, true);
        }

        public static function getMonthName($month): string {
            return T::t("COMMON.DATE.MONTH.".strtoupper($month));
        }
    }