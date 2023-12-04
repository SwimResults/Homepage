<?php

    require_once("php/helper/meetings.php");

    $meetings = MeetingsHelper::getMeetings();
?>

<div class="meeting-list">
    <?php

        $year = 0;
        foreach ($meetings as $meeting) {
            if (isset($meeting["unpublished"]) && $meeting["unpublished"]) continue;
            $start_time = strtotime($meeting["date_start"]);
            $y = date("Y", $start_time);
            if ($y != $year) echo('<h2 class="meeting-year">' . $y . '</h2>');
            $year = $y;
            printMeeting($meeting);
        }

    ?>
</div>