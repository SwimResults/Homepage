<nav id="nav">
    <?php
        foreach ($pages as $kp => $p) {
            if ($p["nav"]) {
                echo('<div class="nav-tile">');
                    echo('<a class="nav-link" href="'.$kp.'">');
                    echo(T::t(
                            ($p["nav_title"] ?? $p["title"])
                    ));
                    echo('</a>');
                echo('</div>');
            }
        }

        $app_url = getenv("SR_APP_URL");
        if (!$app_url) $app_url = "https://app.swimresults.de";
        echo('<a class="btn nav-tile" href="'.$app_url.'">'.T::t("NAV.OPEN_APP_BUTTON").'</a>');
    ?>
</nav>


