<?php


    function getImgSrc($post): string {
        if (isset($post["img_src"]) && $post["img_src"]) return $post["img_src"];
        return "images/blog/blog-1.png";
    }

    function getDateString($date): string {
        return date("d.m.Y", strtotime($date));
    }

    function getTimeString($date): string {
        return date("H:i", strtotime($date));
    }

    function getDateTimeString($date): string {
        return date("d.m.Y H:i", strtotime($date));
    }

    function getPostPublishDate($post): string {
        $date = $post["created_at"];
        if ($post["published_at"]) {
            $date = $post["published_at"];
        }

        return $date;
    }

    function timeAgo($date) {
        $timestamp = strtotime($date);

        $strTimeSg = array("einer Sekunde", "einer Minute", "einer Stunde", "einem Tag", "einem Monat", "einem Jahr");
        $strTimePl = array("Sekunden", "Minuten", "Stunden", "Tagen", "Monaten", "Jahren");
        $timeKeys = array("SECONDS", "MINUTES", "HOURS", "DAYS", "MONTHS", "YEARS");
        $length = array("60","60","24","30","12","10");

        $currentTime = time();
        if($currentTime >= $timestamp) {
            $diff     = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);

            $out = T::t("COMMON.DATE.AGO.BEFORE_TEXT")." ";
            if ($diff == 1) {
                $out .= T::t("COMMON.DATE.AGO.UNIT.SINGLE.".$timeKeys[$i]);
            } else {
                $out .= $diff;
                $out .= " ".T::t("COMMON.DATE.AGO.UNIT.PLURAL.".$timeKeys[$i]);
            }
            $out .= " ".T::t("COMMON.DATE.AGO.AFTER_TEXT");
            return $out;
        }
    }

    function getArticleAlias($s) {
        $s = trim($s);
        $s = str_replace(' ', '-', $s);
        $s = str_replace('<wbr>', '', $s);
        $s = str_replace('<br>', '', $s);
        $s = strtolower($s);
        return $s;
    }

    function printArticleForBlogList($post): void {
        echo('<div class="post-list-tile container">');
            echo('<img class="post-image" src="'.getImgSrc($post).'" alt="post '.$post["title"].'">');
            $author = BlogHelper::getAuthorInfo($post["author"]);
            if ($author) {
                echo('<h3 class="post-author">' . $author["name"]["formatted"] . '</h3>');
            }
            echo('<h1 class="post-title"><a href="article/'.$post["id"].'-'.getArticleAlias($post["title"]).'">'.$post["title"].'</a></h1>');
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
                            echo('<span title="'.getDateTimeString(getPostPublishDate($post)).'">');
                                echo(getDateString(getPostPublishDate($post)));
                                echo(" · ");
                                echo(getTimeString(getPostPublishDate($post)));
                            echo('</span>');
                            if ($post["updated_at"]) {
                                echo('<br>'.T::t("CONTENT.BLOG.UPDATED_AT").': ');
                                echo('<span title="'.getDateTimeString($post["updated_at"]).'">');
                                echo(timeAgo($post["updated_at"]));
                                echo('</span>');
                            }
                        echo('</span>');
                    echo('</div>');
                echo('</div>');
            }
            echo('<div class="post-content">');
                if (isset($post["content_md"])) {
                    $pd = new Parsedown();
                    $pd->printMarkdown($post["content_md"]);
                } else {
                    echo('<div>'.$post["content_html"].'</div>');
                }
                if ($author)
                    echo('<p class="post-author-info">'.T::t("CONTENT.BLOG.WRITTEN_BY").': <b>'.$author["name"]["formatted"].'</b></p>');
            echo('</div>');
        echo('</div>');
    }