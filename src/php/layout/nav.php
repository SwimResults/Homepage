<?php

global $pages;

class NavTile {
    public $name;
    public $title;
    public $description;
    public $navTitle;

    public function __construct($name, $title, $description, $navTitle = "") {
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->navTitle = $title;
        if ($navTitle) {
            $this->navTitle = $navTitle;
        }
    }
}

require_once("php/config/nav.config.php");

$navTiles = array(
    "main",
    "meetings"
)
?>
<nav>
    <?php
        foreach ($navTiles as $tile) {
            $page = $pages[$tile];
            echo('<div class="nav-tile">');
                echo('<a class="nav-link" href="'.$page->name.'">');
                echo(T::t($page->navTitle));
                echo('</a>');
            echo('</div>');
        }
    ?>
</nav>


