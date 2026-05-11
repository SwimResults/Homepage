<?php
    session_start();
    date_default_timezone_set("Europe/Berlin");
    if (isset($_REQUEST["lang"])) {
        $_SESSION["lang"] = $_REQUEST["lang"];
    }

    if (getenv("SR_HOMEPAGE_ENV") === "PRODUCTION") {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    } else {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    $functions = scandir("php/helper");

    foreach ($functions as $func_file) {
        if (str_contains($func_file, ".php")) {
            require_once("php/helper/".$func_file);
        }
    }

    // Redirect to language-prefixed URL if language preference requires it
    // e.g., if user has English preference but accessed /feature, redirect to /en/feature
    $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
    if ($lang === 'en' && (strpos($request_uri, '/en/') !== 0 && $request_uri !== '/en')) {
        // Get the current path without leading slash
        $current_path = ltrim($request_uri, '/');
        
        // Redirect to /en/ version
        if ($current_path) {
            header("Location: /en/" . $current_path, true, 302);
        } else {
            header("Location: /en/", true, 302);
        }
        exit;
    }

    $pages = json_decode(file_get_contents("php/config/pages.json"), TRUE);

    if (isset($_GET["path"])) {
        $full_path = $_GET["path"];
        $path = $full_path;
        if (str_contains($full_path, "/")) $path = substr($full_path, 0, strpos($full_path, "/"));
    }
    else $path = "main";

    if ($path[-1] == "/") $path = substr($path, 0, -1);

    if (!array_key_exists($path, $pages)) {
        header("Location: /");
    }

    $page = $pages[$path];

    // Handle quiz page with slug validation
    $quiz_slug = null;
    if ($path === "quiz") {
        $quiz_slug = isset($full_path) && str_contains($full_path, "/") 
            ? substr($full_path, strpos($full_path, "/") + 1) 
            : null;
        
        if (!$quiz_slug) {
            header("Location: /compatibility-quiz");
            exit;
        }
        
        // Validate quiz exists
        if (!QuizHelper::getQuizBySlug($quiz_slug)) {
            header("Location: /compatibility-quiz");
            exit;
        }
    }
?>
<html lang="<?php echo($lang); ?>">
    <head>
        <?php
            if (getenv("SR_HOMEPAGE_ENV") === 'LOCALHOST')
                echo('<base href="/swimresults/src/">');
            else
                echo('<base href="/">');
        ?>

    <?php
        require("php/head.php");

        if ($path == "article") {
            require("php/article_head.php");
        }

        $page_seo = $page["seo"] ?? [];

        if ($path == "main") {
            if ($lang === 'en') {
                echo('<title>SwimResults | Swim Results for Swimming Competitions</title>');
                if (isset($page_seo["meta_en"])) {
                    echo('<meta name="description" content="'.$page_seo["meta_en"].'">');
                }
                echo('<meta property="og:title" content="SwimResults - Swim Results & Swimming Competition Platform">');
                echo('<meta property="og:description" content="Access live swim results, competition data, timings, and rankings. SwimResults brings swim competition results to your fingertips.">');
            } else {
                echo('<title>SwimResults | Schwimmwettkampf-App für Trainer, Schwimmer und Veranstalter</title>');
                if (isset($page_seo["meta_de"])) {
                    echo('<meta name="description" content="'.$page_seo["meta_de"].'">');
                }
                echo('<meta property="og:title" content="SwimResults - Swim Results & Wettkampf-Plattform">');
                echo('<meta property="og:description" content="Zugriff auf Swim Results, Livetiming, Wettkampfdaten und Auswertungen. SwimResults bringt Swim Results in deine Hand.">');
            }
        } else {
            if (isset($page["title"])) {
                $pageTitle = T::t($page["title"]);
                if ($lang === 'en') {
                    $seoTitle = $pageTitle . ' | SwimResults - Swim Results & Swimming';
                } else {
                    $seoTitle = $pageTitle . ' | SwimResults - Swim Results';
                }
                echo('<title>'.$seoTitle.'</title>');
            }

            if ($lang === 'en' && isset($page_seo["meta_en"])) {
                echo('<meta name="description" content="'.$page_seo["meta_en"].'">');
            } elseif ($lang === 'de' && isset($page_seo["meta_de"])) {
                echo('<meta name="description" content="'.$page_seo["meta_de"].'">');
            }
        }
    ?>
</head>
<body>
    <?php include("php/layout/header.php"); ?>

    <?php if (array_key_exists("banner", $page) && $page["banner"]): ?>
        <div class="background">
            <span class="background-text">
                <?php echo(T::t('CONTENT.BANNER.MAIN.INFO_TEXT')); ?>
                <span class="banner-mobile-app-btn"></span>
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