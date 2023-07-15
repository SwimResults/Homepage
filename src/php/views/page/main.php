<?php

    require_once("php/helper/meetings.php");

?>

<div class="section">
    <h1 class="title"><?php T::e("CONTENT.MAIN.TITLE"); ?></h1>
    <blockquote>
        <p>SwimResults befindet sich momentan in der Entwicklung! Der Release ist für Ende 2023 geplant. Der aktuelle Stand der Entwicklung kann unter <a href="https://status.swimresults.de/">status.swimresults.de</a> eingesehen werden.</p>
    </blockquote>
    <p><?php T::e("CONTENT.MAIN.INTRO_INFO_TEXT"); ?></p>
    <br>
    <img src="images/sr_laptop_1.png" class="selection-img img-crop img-crop-right" alt="SwimResults Screenshot Laptop">
</div>

<div class="parallax parallax-1"></div>

<div class="section">
    <h1>Nächste Veranstaltung</h1>
    <?php
        $meeting = MeetingsHelper::getNextMeeting();
        printMeeting($meeting);
    ?>
    <a class="section-link-big" href="meetings">weitere Wettkämpfe</a>
</div>
<div class="section section-2">
    <div class="section-split">
        <div class="section-left">
            <img src="images/drawings/features.png" alt="SwimResults Features">
        </div>
        <div class="section-right">
            <h1>Das kann SwimResults!</h1>
            <p>Livetiming, Platzierungen, Bahnbelegungen, Zeitpläne und Wettkampffolgen oder Dateisammlungen: SwimResults bietet zahlreiche Funktionen und Inhalte.</p>
            <a class="section-link-big" href="blog/veranstalter">Funktionsumfang</a>
        </div>
    </div>
</div>
<div class="section">
    <div class="section-split">
        <div class="section-left">
            <h1>Up to date bleiben</h1>
            <p>In regelmäßigen Blog-Posts informieren die Entwickler oder Nutzer von SwimResults über neue Funktionen, Tipps zur Nutzung oder Beantworten häufig gestellte Fragen.</p>
            <a class="section-link-big" href="blog/veranstalter">Zum Blog</a>
        </div>
        <div class="section-right">
            <img src="images/drawings/blog.png" alt="SwimResults Features">
        </div>
    </div>
</div>

<div class="parallax parallax-4"></div>

<div class="section">
    <div class="section-split">
        <div class="section-left">

        </div>
        <div class="section-right">
            <h1>SwimResults für deine Veranstaltung</h1>
            <p>Du bist Veranstalter eines Schwimmwettkampfes und möchtest SwimResults bei deiner Veranstaltung anbieten?</p>
            <a class="section-link-big" href="blog/veranstalter">Infos für Veranstalter</a>
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
            <span class="value">513</span>
            <span class="description"><?php T::e("CONTENT.MAIN.VALUES.ATHLETES_DESCRIPTION"); ?></span>
        </div>
        <div class="section-col value-info">
            <span class="value">1560</span>
            <span class="description"><?php T::e("CONTENT.MAIN.VALUES.STARTS_DESCRIPTION"); ?></span>
        </div>
    </div>
    <br><br>
</div>
<div class="section">
    <div class="section-split">
        <div class="section-left">
            <h1>Enge Zusammenarbeit mit Auswertern</h1>
            <p>Als Betreiber eines Ergebnisdienstes hast du die Möglichkeit Veranstaltern von Schwimmwettkämpfen Unterstützung von SwimResults anzubieten.</p>
            <a class="section-link-big" href="blog/veranstalter">Infos für Ergebnisdienste</a>
        </div>
        <div class="section-right">

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