<?php require_once("php/config/nav.config.php");

$footerNavTiles = array(
"impressum",
"contact"
)
?>
<footer>
		<span>
			© 2023 - logilutions.de
		</span>
    <div class="links">
        <?php
            foreach ($footerNavTiles as $tile) {
                $page = $pages[$tile];
                echo('<a href="'.$page->name.'">');
                echo(T::t($page->navTitle));
                echo('</a>');
            }
        ?>
    </div>
</footer>
