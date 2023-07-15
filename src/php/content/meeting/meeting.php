<?php

function printMeeting($meeting): void
{
    $start_time = strtotime($meeting["date_start"]);
    $end_time = strtotime($meeting["date_end"]);
    $y = date("Y", $start_time);

    $month_start = date("M", $start_time);
    $month_end = date("M", $end_time);

    $day_start = date("d", $start_time);
    $day_end = date("d", $end_time);

    $day = ($day_start == $day_end && $month_start == $month_end) ? $day_start : $day_start . "/" . $day_end;
    $month = ($month_start == $month_end) ?
        MeetingsHelper::getMonthName($month_start)
        :
        MeetingsHelper::getMonthName($month_start) . "/" . MeetingsHelper::getMonthName($month_end);

    $title = "";
    if (isset($meeting["iteration"]) && $meeting["iteration"] != 0) $title .= $meeting["iteration"] . '. ';
    $title .= $meeting["series"]["name_full"];
    $title .= ' ' . $y;

    echo('<div class="meeting-list-tile container">');
    echo('<div class="meeting-left">');
    echo('<div class="meeting-date">');
    echo('<span class="meeting-date-month">' . $month . '</span>');
    echo('<span class="meeting-date-day">' . $day . '</span>');
    echo('</div>');
    echo('</div>');
    echo('<div class="meeting-right">');
    echo('<h2 class="meeting-title">' . $title . '</h2>');
    if (!isset($meeting["state"]) || $meeting["state"] != "UNPUBLISHED") {
        echo('<div class="meeting-links">');
            echo('<a class="meeting-link btn" href="' . Env::getAppUrl() . '/m/' . $meeting["meet_id"] . '">In SwimResults öffnen</a>');
        echo('</div>');
    }
    echo('</div>');
    echo('</div>');
}