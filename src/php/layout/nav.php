<nav id="nav">
    <?php
        foreach ($pages as $kp => $p) {
            if ($p["nav"]) {
                echo('<div class="nav-tile">');
                    echo('<a class="nav-link" href="'.buildLink($kp).'">');
                    echo(T::t(
                            ($p["nav_title"] ?? $p["title"])
                    ));
                    echo('</a>');
                echo('</div>');
            }
        }

        echo('<a class="btn nav-tile" href="'.Env::getAppUrl().'">'.T::t("NAV.OPEN_APP_BUTTON").'</a>');
    ?>


    <!-- Language Switcher -->
    <div class="lang-switcher nav-tile">
        <a href="<?php echo buildLink(isset($full_path) ? $full_path : $path, 'de'); ?>"
           class="lang-link <?php echo($lang === 'de' ? 'active' : ''); ?>"
           title="Deutsch">
            DE
        </a>
        <span class="lang-separator">|</span>
        <a href="<?php echo buildLink(isset($full_path) ? $full_path : $path, 'en'); ?>"
           class="lang-link <?php echo($lang === 'en' ? 'active' : ''); ?>"
           title="English">
            EN
        </a>
    </div>
</nav>


