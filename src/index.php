<html lang="de">
    <head>
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

		require_once("php/helper/translation.php");

    ?>
	<?php require("php/head.php"); ?>
	<title>SwimResults | Wettkampf-App für Schwimmer, Trainer und co.</title>
	<!-- <meta name="description" content="SwimResults ist eine Online-Plattform für Schwimmwettkämpfe, welche Daten von teilnehmenden Veranstaltungen aufbereitet und strukturiert zur Verfügung stellt. Mit SwimResults können Sportler, Training, Familie und Freunde Meldungen, Ergebnisse, sowie Livetimings und Auswertungen für verschiedene Sportler, Vereine und Veranstaltungen einsehen."> -->
	<meta name="description" content="Ergebnisse, Meldungen, Livetiming, Platzierungen und Auswertungen für Schwimmwettkämpfe – Das Tool für Schwimmer, Trainer und Bekannte">
</head>
<body>
    <?php include("php/layout/header.php"); ?>
	<div class="background">
        <span class="background-text">
            <?php echo(T::t('STARTPAGE.BANNER.MAIN.INFO_TEXT')); ?>
        </span>
    </div>
	<div class="page-content">

	</div>

    <?php include("php/layout/footer.php"); ?>

</body></html>