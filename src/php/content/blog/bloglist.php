<?php
    require_once("php/helper/blog.php");
    require_once("php/content/blog/article.php");

    $posts = BlogHelper::getBlogPosts();

    foreach ($posts as $post) {
        $pub = strtotime($post["published_at"]);
        if ($pub > time()) continue;
        printArticleForBlogList($post);
    }