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

        echo('<a class="btn nav-tile" href="'.Env::getAppUrl().'">'.T::t("NAV.OPEN_APP_BUTTON").'</a>');
    ?>
</nav>


