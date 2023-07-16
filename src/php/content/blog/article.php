<?php


    function getImgSrc($post): string {
        if (isset($post["img_src"]) && $post["img_src"]) return $post["img_src"];
        return "images/blog/blog-1.png";
    }

    function getDateString($date): string {
        return date("d.m.Y H:i", strtotime($date));
    }

    function getPostPublishDate($post): string {
        $date = $post["created_at"];
        if ($post["published_at"]) {
            $date = $post["published_at"];
        }

        return getDateString($date);
    }

    function printArticleForBlogList($post): void {
        echo('<div class="post-list-tile container">');
            echo('<img class="post-image" src="'.getImgSrc($post).'" alt="post '.$post["title"].'">');
            echo('<h3 class="post-author">'.$post["author"].'</h3>');
            echo('<h1 class="post-title"><a href="article/'.$post["id"].'">'.$post["title"].'</a></h1>');
        echo('</div>');
    }

    function printArticleFullPage($post): void {
        echo('<div class="post">');
            echo('<img class="post-image" src="'.getImgSrc($post).'" alt="post '.$post["title"].'">');
            echo('<h1 class="post-title title">'.$post["title"].'</h1>');
            echo('<span class="post-date">'.getPostPublishDate($post).'</span>');
            echo('<div class="post-list-content">');
                echo('<div>'.$post["content_html"].'</div><br><br>');
                echo('<span>von: <b>'.$post["author"].'</b></span><br><br>');
                echo('<span>veröffentlicht: <b>'.$post["created_at"].'</b></span><br><br>');
            echo('</div>');
        echo('</div>');
    }