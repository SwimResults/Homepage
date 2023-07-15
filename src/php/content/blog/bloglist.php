<?php
    require_once("php/helper/blog.php");
    require_once("php/content/blog/article.php");

    $posts = BlogHelper::getBlogPosts();

    echo('<div class="post-list">');
    foreach ($posts as $post) {
        if ($post["published_at"]) {
            $pub = strtotime($post["published_at"]);
            if ($pub > time()) continue;
        }
        printArticleForBlogList($post);
    }
    echo('</div>');