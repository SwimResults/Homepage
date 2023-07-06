<html lang="de">
    <head>
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

        if (isset($_GET["path"]))
            $path = $_GET["path"];
        else $path = "main";

        if ($path[-1] == "/") $path = substr($path, 0, -1);

        if (!array_key_exists($path, $pages)) {
            $path = "main";
        }

        $page = $pages[$path];
    ?>
	<title>SwimResults | Wettkampf-App für Schwimmer, Trainer und co.</title>
	<!-- <meta name="description" content="SwimResults ist eine Online-Plattform für Schwimmwettkämpfe, welche Daten von teilnehmenden Veranstaltungen aufbereitet und strukturiert zur Verfügung stellt. Mit SwimResults können Sportler, Training, Familie und Freunde Meldungen, Ergebnisse, sowie Livetimings und Auswertungen für verschiedene Sportler, Vereine und Veranstaltungen einsehen."> -->
	<meta name="description" content="Ergebnisse, Meldungen, Livetiming, Platzierungen und Auswertungen für Schwimmwettkämpfe – Das Tool für Schwimmer, Trainer und Bekannte">
</head>
<body>
    <?php include("php/layout/header.php"); ?>
    <?php if (array_key_exists("banner", $page) && $page["banner"]): ?>
        <div class="background">
            <span class="background-text">
                <?php echo(T::t('STARTPAGE.BANNER.MAIN.INFO_TEXT')); ?>
            </span>
        </div>
    <?php else: ?>
        <div class="background banner-small"></div>
    <?php endif; ?>
	<div class="page-content">
        <?php
            if ($page["title"]) echo('<h1>'.T::t($page["title"]).'</h1>');

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