<footer>
    <span>
        © 2024 - Konrad Weiß | swimresults.de
    </span>
    <div class="links">
        <?php
            foreach ($pages as $kp => $p) {
                if (array_key_exists("footer", $p) && $p["footer"]) {
                    echo('<a href="' . $kp . '">');
                    echo(T::t($p["title"]));
                    echo('</a>');
                }
            }
        ?>
    </div>
</footer>
