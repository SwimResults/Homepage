<?php
    require_once("php/helper/blog.php");
    require_once("php/content/blog/article.php");

    $blog_path = $full_path;
    $article_path = "";
    if (str_contains($blog_path, "/"))
        $article_path = substr($blog_path, strpos($blog_path, "/") + 1);

    if (!$article_path) {
        $post = BlogHelper::getRandomBlogPost();
    } else {
        if (str_contains($article_path, "-"))
            $article_id = substr($article_path, 0, strpos($article_path, "-"));
        else
            $article_id = $article_path;

        $post = BlogHelper::getBlogPostById($article_id);
    }

    $author = BlogHelper::getAuthorInfo($post["author"]);

    $base_url = "https://swimresults.de/";
    $article_url = $base_url."article/";

    echo('
        <meta property="og:title" content="'.$post["title"].'" />
        <meta property="og:url" content="'.$article_url.$post["id"].'-'.getArticleAlias($post["title"]).'" />
        <meta property="og:image" content="'.$base_url.getImgSrc($post).'" />
        <meta property="og:site_name" content="SwimResults" />
        <meta property="og:type" content="article" />
        <meta property="article:published_time" content="'.getPostPublishDate($post).'" />
        <meta property="article:modified_time" content="'.$post["updated_at"].'" />
        <meta property="article:author" content="'.$author["displayName"].'" />
    ');

    echo('

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": "'.$article_url.$post["title"].'",
      "image": [
        "'.$article_url.getImgSrc($post).'"
       ],
      "datePublished": "'.getPostPublishDate($post).'",
      "dateModified": "'.$post["updated_at"].'",
      "author": [{
          "@type": "Person",
          "name": "'.$author["displayName"].'"
      }]
    }
    </script>

    ');