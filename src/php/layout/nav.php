<nav id="nav">
    <?php
        foreach ($pages as $kp => $p) {
            if ($p["nav"]) {
                echo('<div class="nav-tile">');
                    echo('<a class="nav-link" href="'.$kp.'">');
                    echo(T::t($p["title"]));
                    echo('</a>');
                echo('</div>');
            }
        }
    ?>
</nav>


