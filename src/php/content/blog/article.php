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

    function timeAgo($date) {
        $timestamp = strtotime($date);

        $strTimeSg = array("einer Sekunde", "einer Minute", "einer Stunde", "einem Tag", "einem Monat", "einem Jahr");
        $strTimePl = array("Sekunden", "Minuten", "Stunden", "Tagen", "Monaten", "Jahren");
        $length = array("60","60","24","30","12","10");

        $currentTime = time();
        if($currentTime >= $timestamp) {
            $diff     = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);

            $out = "vor ";
            if ($diff == 1) {
                $out .= $strTimeSg[$i];
            } else {
                $out .= $diff;
                $out .= " ".$strTimePl[$i];
            }
            return $out;
        }
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
            $author = BlogHelper::getAuthorInfo($post["author"]);
            if ($author) {
                echo('<div class="author">');
                    echo('<img src="'.$author["thumbnailUrl"].'" alt="author image for '.$author["name"]["formatted"].'" class="author-img">');
                    echo('<div class="post-info">');
                        echo('<span class="author-name">');
                            echo($author["name"]["formatted"]);
                        echo('</span><br>');
                        echo('<span class="post-date">');
                        echo(getPostPublishDate($post));
                        if ($post["updated_at"]) {
                            echo(' · aktualisiert: ');
                            echo('<span title="'.getDateString($post["updated_at"]).'">');
                            echo(timeAgo($post["updated_at"]));
                            echo('</span>');
                        }
                        echo('</span>');
                    echo('</div>');
                echo('</div>');
            }
            echo('<div class="post-content">');
                echo('<div>'.$post["content_html"].'</div>');
            echo('</div>');
        echo('</div>');
    }