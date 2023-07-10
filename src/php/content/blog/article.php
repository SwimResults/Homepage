<?php

    function printArticleForBlogList($post): void {
        echo('<div class="post-list-tile container">');
            echo('<h2>'.$post["title"].'</h2>');
            echo('<div class="post-list-content">');
                echo('<span>von: '.$post["author"].'</span>');
                echo('<div>'.$post["content_html"].'</div>');
            echo('</div>');
        echo('</div>');
    }