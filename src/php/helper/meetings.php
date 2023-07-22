<?php

    require_once("php/helper/translation.php");
    require_once("php/content/meeting/meeting.php");

    class MeetingsHelper {

        public static function getMeetings() {
            $meetings = MeetingClient::getMeetings();
            if ($meetings) return $meetings;

            $data = file_get_contents("data/meetings.json");
            return json_decode($data, true);
        }

        // TODO: check start date
        public static function getNextMeeting() {
            $meetings = MeetingClient::getMeetings();
            if ($meetings) return $meetings[0];

            $data = file_get_contents("data/meetings.json");
            $json = json_decode($data, true);
            return $json[0];
        }

        public static function getMonthName($month): string {
            return T::t("COMMON.DATE.MONTH.".strtoupper($month));
        }
    }