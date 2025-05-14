<?php

    require_once("php/helper/blog.php");
    require_once("php/content/blog/article.php");


    if (!$post) {
        echo('<div>');
        echo('<h1>'.T::t("CONTENT.BLOG.ARTICLE_NOT_FOUND_TITLE").'</h1>');
        echo('<img class="img-gray img-page-center" src="images/drawings/blog_not_found.png">');
        echo('<p class="center"><i>'.T::t("CONTENT.BLOG.ARTICLE_NOT_FOUND_TEXT").'</i></p>');
        echo('</div>');
    } else {
        if (isset($post["title"])) {
            echo('<title>'. strip_tags($post["title"]) .' | SwimResults</title>');
        }
        printArticleFullPage($post);
        $posts = BlogHelper::getRandomBlogPosts(4, $post["id"]);
        echo('<br><br><hr><br><br>');
        echo('<h1>'.T::t("CONTENT.BLOG.MORE_ARTICLES").'</h1>');
        echo('<div class="post-list">');
        foreach ($posts as $post) {
            printArticleForBlogList($post);
        }
        echo('</div>');
    }



?>