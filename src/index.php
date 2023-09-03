<?php
    session_start();
    date_default_timezone_set("Europe/Berlin");
    if (isset($_REQUEST["lang"])) {
        $_SESSION["lang"] = $_REQUEST["lang"];
    }

?>
<html lang="de">
    <head>
        <?php
            if ($_SERVER["SERVER_NAME"] == "localhost")
                echo('<base href="/swimresults/src/">');
            else
                echo('<base href="/">');
        ?>

    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $functions = scandir("php/helper");

        foreach ($functions as $func_file) {
            if (str_contains($func_file, ".php")) {
                require_once("php/helper/".$func_file);
            }
        }


        require("php/head.php");


        $pages = json_decode(file_get_contents("php/config/pages.json"), TRUE);

        if (isset($_GET["path"])) {
            $full_path = $_GET["path"];
            $path = $full_path;
            if (str_contains($full_path, "/")) $path = substr($full_path, 0, strpos($full_path, "/"));
        }
        else $path = "main";

        if ($path[-1] == "/") $path = substr($path, 0, -1);

        if (!array_key_exists($path, $pages)) {
            $path = "main";
        }

        $page = $pages[$path];

        if ($path == "main"):
    ?>
	<title>SwimResults | Wettkampf-App für Schwimmer, Trainer und co.</title>
	<!-- <meta name="description" content="SwimResults ist eine Online-Plattform für Schwimmwettkämpfe, welche Daten von teilnehmenden Veranstaltungen aufbereitet und strukturiert zur Verfügung stellt. Mit SwimResults können Sportler, Training, Familie und Freunde Meldungen, Ergebnisse, sowie Livetimings und Auswertungen für verschiedene Sportler, Vereine und Veranstaltungen einsehen."> -->
	<meta name="description" content="Ergebnisse, Meldungen, Livetiming, Platzierungen und Auswertungen für Schwimmwettkämpfe – Das Tool für Schwimmer, Trainer und Freunde">

    <?php
        else:

            echo('<title>'.T::t($page["title"]).'</title>');

        endif;

    ?>
</head>
<body>
    <?php include("php/layout/header.php"); ?>
    <?php if (array_key_exists("banner", $page) && $page["banner"]): ?>
        <div class="background">
            <span class="background-text">
                <?php echo(T::t('CONTENT.BANNER.MAIN.INFO_TEXT')); ?>
                <span class="banner-mobile-app-btn">
                    <?php echo('<a class="btn" href="'.Env::getAppUrl().'">'.T::t("NAV.OPEN_APP_BUTTON").'</a>'); ?>
                </span>
            </span>
        </div>
    <?php else: ?>
        <div class="background banner-small"></div>
    <?php endif; ?>
	<div class="page-content <?php if (isset($page["style"])) echo('page-'.$page["style"]); ?>">
        <?php
            if (isset($page["title"])) echo('<h1 class="title">'.T::t($page["title"]).'</h1>');

            $error = FALSE;
            if ($page["permission"]) {
                if ($page["permission"] > 0) {
                    echo("Du bist nicht berechtigt auf diese Seite zuzugreifen!");
                    $error = TRUE;
                }
            }
            if (!$error)
                include("php/views/".$page["type"]."/".$path.".php");

        ?>
	</div>

    <?php include("php/layout/footer.php"); ?>

</body></html>