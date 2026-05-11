<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="content-language" content="<?php echo($lang === 'en' ? 'en-US' : 'de-DE'); ?>">

<?php
    // Generate canonical and hreflang URLs
    $base_url = "https://swimresults.de";
    $current_path = isset($full_path) ? $full_path : $path;
    
    // Remove trailing slash for clean URLs
    $current_path = rtrim($current_path, '/');
    
    // Canonical URL (always includes the language prefix if English, no prefix for German)
    if ($lang === 'en') {
        $canonical_url = $base_url . '/en/' . $current_path;
    } else {
        $canonical_url = $base_url . '/' . $current_path;
    }
    
    // hreflang URLs
    $hreflang_de = $base_url . '/' . $current_path;
    $hreflang_en = $base_url . '/en/' . $current_path;
    
    echo('<link rel="canonical" href="' . $canonical_url . '" />' . "\n");
    echo('<link rel="alternate" hreflang="de" href="' . $hreflang_de . '" />' . "\n");
    echo('<link rel="alternate" hreflang="en" href="' . $hreflang_en . '" />' . "\n");
    echo('<link rel="alternate" hreflang="x-default" href="' . $hreflang_de . '" />' . "\n");
?>

<?php
    if ($lang === 'en') {
        echo('<meta name="keywords" content="swim results, swimming results, competition results, live timing, swimmer, swimming competitions">');
    } else {
        echo('<meta name="keywords" content="swim results, wettkampfergebnisse, schwimmwettkampf, livetiming, schwimmen, ergebnisse">');
    }
?>
<meta name="theme-color" content="#204988">

<!-- Schema.org markup for Organization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "SwimResults",
  "url": "https://swimresults.de",
  "logo": "https://swimresults.de/images/logo.svg",
  <?php
    if ($lang === 'en') {
        echo('"description": "SwimResults is a platform providing Swim Results, competition results, and swim competition management tools for swimmers, coaches, and event organizers.",');
    } else {
        echo('"description": "SwimResults ist eine Plattform für Swim Results, Wettkampfdaten und Management-Tools für Schwimmwettkämpfe.",');
    }
  ?>
  "sameAs": [],
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Service",
    "url": "https://swimresults.de/contact"
  }
}
</script>

<!-- Schema.org markup for WebSite (for site search) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "SwimResults",
  "url": "https://swimresults.de",
  <?php
    if ($lang === 'en') {
        echo('"description": "Platform for swim results, swimming competition results, and live timing"');
    } else {
        echo('"description": "Plattform für Swim Results, Wettkampfdaten und Livetiming"');
    }
  ?>
}
</script>

<link rel="preload" href="font/SwimResults.ttf" as="font" type="font/ttf" crossorigin>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/main.css?v=1.1">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/meeting.css">
<link rel="stylesheet" href="css/parallax.css">
<link rel="stylesheet" href="css/blog.css">
<link rel="stylesheet" href="css/features.css">
<link rel="stylesheet" href="css/quiz.css">

<script src="js/scroll.js"></script>
<script src="js/quiz-engine.js"></script>

<?php if (getenv("SR_HOMEPAGE_ENV") == "PRODUCTION") : ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4RPVEYN2NN"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-4RPVEYN2NN');
    </script>
<?php endif; ?>

<?php

    // TODO: print meta data, fetch articles if needed

?>

<?php if (file_exists("apple-touch-icon.png")): ?>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#204988">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
<?php else: ?>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#204988">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
<?php endif; ?>