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

        private static function getSortedMeetingsByStartDate(): array {
            $meetings = self::getMeetings();

            usort($meetings, static function ($a, $b) {
                $startA = strtotime($a["date_start"] ?? "") ?: 0;
                $startB = strtotime($b["date_start"] ?? "") ?: 0;

                if ($startA === $startB) {
                    $endA = strtotime($a["date_end"] ?? "") ?: 0;
                    $endB = strtotime($b["date_end"] ?? "") ?: 0;
                    return $endA <=> $endB;
                }

                return $startA <=> $startB;
            });

            return $meetings;
        }

        private static function isCurrentMeeting(array $meeting, int $now): bool {
            $start = strtotime($meeting["date_start"] ?? "");
            $end = strtotime($meeting["date_end"] ?? "");

            if ($start === false || $end === false) return false;
            return $start <= $now && $end >= $now;
        }

        public static function getCurrentMeetings(): array {
            $now = time();
            $meetings = self::getSortedMeetingsByStartDate();

            return array_values(array_filter($meetings, static function ($meeting) use ($now) {
                return self::isCurrentMeeting($meeting, $now);
            }));
        }

        public static function getUpcomingMeetings(): array {
            $now = time();
            $meetings = self::getSortedMeetingsByStartDate();

            return array_values(array_filter($meetings, static function ($meeting) use ($now) {
                $start = strtotime($meeting["date_start"] ?? "");
                return $start !== false && $start > $now;
            }));
        }

        public static function getNextMeeting() {
            $upcomingMeetings = self::getUpcomingMeetings();
            if ($upcomingMeetings) return $upcomingMeetings[0];

            $currentMeetings = self::getCurrentMeetings();
            if ($currentMeetings) return $currentMeetings[0];

            $meetings = self::getSortedMeetingsByStartDate();
            if ($meetings) return $meetings[count($meetings) - 1];

            return array();
        }

        public static function getMonthName($month): string {
            return T::t("COMMON.DATE.MONTH.".strtoupper($month));
        }
    }