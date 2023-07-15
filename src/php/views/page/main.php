<?php

    require_once("php/helper/meetings.php");

?>

<div class="section">
    <h1 class="title"><?php T::e("CONTENT.MAIN.TITLE"); ?></h1>
    <blockquote>
        <p><?php T::e("CONTENT.MAIN.UNDER_DEVELOPMENT_INFO_TEXT_1"); ?><a href="https://status.swimresults.de/">status.swimresults.de</a><?php T::e("CONTENT.MAIN.UNDER_DEVELOPMENT_INFO_TEXT_2"); ?></p>
    </blockquote>
    <p><?php T::e("CONTENT.MAIN.INTRO_INFO_TEXT"); ?></p>
    <br>
    <img src="images/sr_laptop_1.png" class="selection-img img-crop img-crop-right" alt="SwimResults Screenshot Laptop">
</div>

<div class="parallax parallax-1"></div>

<div class="section">
    <h1><?php T::e("CONTENT.MAIN.INFOS.MEETINGS_TITLE"); ?></h1>
    <?php
        $meeting = MeetingsHelper::getNextMeeting();
        printMeeting($meeting);
    ?>
    <a class="section-link-big" href="meetings"><?php T::e("CONTENT.MAIN.INFOS.MEETINGS_LINK_TEXT"); ?></a>
</div>
<div class="section section-2">
    <div class="section-split">
        <div class="section-left">
            <img src="images/drawings/features.png" alt="SwimResults Features">
        </div>
        <div class="section-right">
            <h1><?php T::e("CONTENT.MAIN.INFOS.FEATURES_TITLE"); ?></h1>
            <p><?php T::e("CONTENT.MAIN.INFOS.FEATURES_INFO_TEXT"); ?></p>
            <a class="section-link-big" href="features"><?php T::e("CONTENT.MAIN.INFOS.FEATURES_LINK_TEXT"); ?></a>
        </div>
    </div>
</div>
<div class="section">
    <div class="section-split section-split-switch">
        <div class="section-left">
            <h1><?php T::e("CONTENT.MAIN.INFOS.BLOG_TITLE"); ?></h1>
            <p><?php T::e("CONTENT.MAIN.INFOS.BLOG_INFO_TEXT"); ?></p>
            <a class="section-link-big" href="blog"><?php T::e("CONTENT.MAIN.INFOS.BLOG_LINK_TEXT"); ?></a>
        </div>
        <div class="section-right">
            <img src="images/drawings/blog.png" alt="SwimResults Blog">
        </div>
    </div>
</div>

<div class="parallax parallax-4"></div>

<div class="section">
    <div class="section-split">
        <div class="section-left">
            <img src="images/drawings/organizer.png" alt="SwimResults Organizer">
        </div>
        <div class="section-right">
            <h1><?php T::e("CONTENT.MAIN.INFOS.ORGANIZER_TITLE"); ?></h1>
            <p><?php T::e("CONTENT.MAIN.INFOS.ORGANIZER_INFO_TEXT"); ?></p>
            <a class="section-link-big" href="blog/veranstalter"><?php T::e("CONTENT.MAIN.INFOS.ORGANIZER_LINK_TEXT"); ?></a>
        </div>
    </div>
</div>
<div class="section section-2">
    <br><br>
    <div class="section-cols">
        <div class="section-col value-info">
            <span class="value">3</span>
            <span class="description"><?php T::e("CONTENT.MAIN.VALUES.MEETINGS_DESCRIPTION"); ?></span>
        </div>
        <div class="section-col value-info">
            <span class="value">700</span>
            <span class="description"><?php T::e("CONTENT.MAIN.VALUES.ATHLETES_DESCRIPTION"); ?></span>
        </div>
        <div class="section-col value-info">
            <span class="value">896</span>
            <span class="description"><?php T::e("CONTENT.MAIN.VALUES.STARTS_DESCRIPTION"); ?></span>
        </div>
    </div>
    <br><br>
</div>
<div class="section">
    <div class="section-split section-split-switch">
        <div class="section-left">
            <h1><?php T::e("CONTENT.MAIN.INFOS.SERVICE_TITLE"); ?></h1>
            <p><?php T::e("CONTENT.MAIN.INFOS.SERVICE_INFO_TEXT"); ?></p>
            <a class="section-link-big" href="blog/veranstalter"><?php T::e("CONTENT.MAIN.INFOS.SERVICE_LINK_TEXT"); ?></a>
        </div>
        <div class="section-right">
            <img src="images/drawings/service.png" alt="SwimResults Ergebnisdienst">
        </div>
    </div>
</div>

<div class="parallax parallax-3"></div>

<div class="section">
    <img src="images/sr_phone_1.png" class="selection-img" alt="SwimResults Screenshot Phone">
    <h1>Links</h1>
    <div class="link-buttons">
        <a href="https://github.com/SwimResults" class="link-button">
            <img src="images/github.png" alt="GitHub" class="link-button-icon">
        </a>
        <a href="https://my.erzgebirgsschwimmcup.de/about.php" class="link-button">
            <img src="images/myiesc.png" alt="myIESC" class="link-button-icon">
        </a>
        <a href="https://app.swimresults.de/" class="link-button">
            <img src="images/sr.png" alt="SwimResults - App" class="link-button-icon">
        </a>
        <a href="https://instagram.com/swimresults" class="link-button">
            <img src="images/instagram.png" alt="Instagram" class="link-button-icon">
        </a>
        <a href="mailto:kontakt@swimresults.de" class="link-button">
            <img src="images/mail.png" alt="Mail" class="link-button-icon">
        </a>
    </div>
</div>