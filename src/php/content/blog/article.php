<?php

    function printArticleForBlogList($post): void {
        echo('<div class="post-list-tile">');
            echo('<h2>'.$post["title"].'</h2>');
            echo('<div class="post-list-content">');
                echo('<span>von: '.$post["author"].'</span>');
            echo('</div>');
        echo('</div>');
    }