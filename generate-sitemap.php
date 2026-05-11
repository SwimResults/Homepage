#!/usr/bin/env php
<?php
/**
 * Sitemap Generator CLI
 * 
 * Generates sitemap.xml from pages.json and optionally database blog articles.
 * Used during Docker build and container startup.
 * 
 * Usage:
 *   php generate-sitemap.php [--static-only]
 * 
 * --static-only: Generate only from pages.json (used during build)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set("Europe/Berlin");

// Get arguments
$static_only = in_array('--static-only', $argv);

// Get base URL
$env = getenv("SR_HOMEPAGE_ENV");

switch ($env) {
    case "PRODUCTION":
        $base_url = "https://swimresults.de";
        break;
    case "DEVELOPMENT":
        $base_url = "https://dev.swimresults.de";
        break;
    case "LOCALHOST":
        $base_url = "http://localhost:4300";
        break;
    default:
        echo "[WARNING] Unknown environment: $env. Defaulting base URL to https://swimresults.de\n";
        $base_url = "https://swimresults.de";
}

echo "[Sitemap] Generating sitemap...\n";
echo "[Sitemap] Base URL: $base_url\n";
echo "[Sitemap] Static only: " . ($static_only ? "yes" : "no") . "\n";

// Initialize sitemap with namespace for hreflang
$sitemap_xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"></urlset>';
$sitemap = new SimpleXMLElement($sitemap_xml);

// Determine correct path based on context
// In Docker: /var/www/html/php/config/pages.json
// In development: ./src/php/config/pages.json
$pages_config_file = null;
if (file_exists('/var/www/html/php/config/pages.json')) {
    $pages_config_file = '/var/www/html/php/config/pages.json';
} elseif (file_exists(__DIR__ . '/src/php/config/pages.json')) {
    $pages_config_file = __DIR__ . '/src/php/config/pages.json';
}

if (!file_exists($pages_config_file)) {
    echo "[ERROR] pages.json not found at: $pages_config_file\n";
    exit(1);
}

$pages_config = json_decode(file_get_contents($pages_config_file), true);
if (!is_array($pages_config)) {
    echo "[ERROR] Failed to parse pages.json\n";
    exit(1);
}

echo "[Sitemap] Found " . count($pages_config) . " pages in config\n";

// Add static pages from pages.json (both languages)
$added_pages = [];
foreach ($pages_config as $slug => $page) {
    // Add pages that should be in nav or footer
    if ((isset($page["nav"]) && $page["nav"]) || (isset($page["footer"]) && $page["footer"])) {
        $priority = (isset($page["nav"]) && $page["nav"]) ? '1.0' : '0.8';
        $last_mod = date('Y-m-d');
        
        // German version (no /de/ prefix)
        $url_de = $sitemap->addChild('url');
        $url_de->addChild('loc', $base_url . '/' . $slug);
        $url_de->addChild('lastmod', $last_mod);
        $url_de->addChild('priority', $priority);
        
        // Add hreflang links
        $xhtml_ns = 'http://www.w3.org/1999/xhtml';
        $link_de = $url_de->addChild('xhtml:link', null, $xhtml_ns);
        $link_de->addAttribute('rel', 'alternate');
        $link_de->addAttribute('hreflang', 'de');
        $link_de->addAttribute('href', $base_url . '/' . $slug);
        
        $link_en = $url_de->addChild('xhtml:link', null, $xhtml_ns);
        $link_en->addAttribute('rel', 'alternate');
        $link_en->addAttribute('hreflang', 'en');
        $link_en->addAttribute('href', $base_url . '/en/' . $slug);
        
        // English version (with /en/ prefix)
        $url_en = $sitemap->addChild('url');
        $url_en->addChild('loc', $base_url . '/en/' . $slug);
        $url_en->addChild('lastmod', $last_mod);
        $url_en->addChild('priority', $priority);
        
        // Add hreflang links
        $link_de_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
        $link_de_en->addAttribute('rel', 'alternate');
        $link_de_en->addAttribute('hreflang', 'de');
        $link_de_en->addAttribute('href', $base_url . '/' . $slug);
        
        $link_en_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
        $link_en_en->addAttribute('rel', 'alternate');
        $link_en_en->addAttribute('hreflang', 'en');
        $link_en_en->addAttribute('href', $base_url . '/en/' . $slug);
        
        $added_pages[] = $slug;
    }
}

// Add main page (homepage) if not already added
if (!in_array('main', $added_pages)) {
    $xhtml_ns = 'http://www.w3.org/1999/xhtml';
    
    // German homepage
    $url_de = $sitemap->addChild('url');
    $url_de->addChild('loc', $base_url . '/');
    $url_de->addChild('priority', '1.0');
    $url_de->addChild('lastmod', date('Y-m-d'));
    
    $link_de = $url_de->addChild('xhtml:link', null, $xhtml_ns);
    $link_de->addAttribute('rel', 'alternate');
    $link_de->addAttribute('hreflang', 'de');
    $link_de->addAttribute('href', $base_url . '/');
    
    $link_en = $url_de->addChild('xhtml:link', null, $xhtml_ns);
    $link_en->addAttribute('rel', 'alternate');
    $link_en->addAttribute('hreflang', 'en');
    $link_en->addAttribute('href', $base_url . '/en/');
    
    // English homepage
    $url_en = $sitemap->addChild('url');
    $url_en->addChild('loc', $base_url . '/en/');
    $url_en->addChild('priority', '1.0');
    $url_en->addChild('lastmod', date('Y-m-d'));
    
    $link_de_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
    $link_de_en->addAttribute('rel', 'alternate');
    $link_de_en->addAttribute('hreflang', 'de');
    $link_de_en->addAttribute('href', $base_url . '/');
    
    $link_en_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
    $link_en_en->addAttribute('rel', 'alternate');
    $link_en_en->addAttribute('hreflang', 'en');
    $link_en_en->addAttribute('href', $base_url . '/en/');
}

echo "[Sitemap] Added " . (count($added_pages) * 2 + 2) . " static page entries (both languages)\n";

// Add blog articles from database if not static-only mode
$article_count = 0;
if (!$static_only) {
    try {
        // Load database helper - check both paths
        $db_helper = null;
        if (file_exists('/var/www/html/php/helper/database.php')) {
            $db_helper = '/var/www/html/php/helper/database.php';
        } elseif (file_exists(__DIR__ . '/src/php/helper/database.php')) {
            $db_helper = __DIR__ . '/src/php/helper/database.php';
        }
        
        if ($db_helper && file_exists($db_helper)) {
            require_once($db_helper);
            
            $pdo = DatabaseHelper::getPDO();
            
            if ($pdo) {
                echo "[Sitemap] Connecting to database...\n";
                
                // Get articles, matching the same logic as bloglist.php:
                // Include articles where published_at is NULL or published_at <= NOW()
                $sql = "SELECT id, title, updated_at, created_at, published_at 
                        FROM blog 
                        WHERE published_at IS NULL OR published_at <= NOW()
                        ORDER BY id ASC";
                
                foreach ($pdo->query($sql) as $post) {
                    // Generate URL slug (same logic as getArticleAlias in article.php)
                    $slug = trim($post["title"]);
                    $slug = str_replace(' ', '-', $slug);
                    $slug = str_replace('<wbr>', '', $slug);
                    $slug = str_replace('<br>', '', $slug);
                    $slug = strtolower($slug);
                    
                    // Use updated_at if available, otherwise published_at, otherwise created_at
                    $last_mod = $post["updated_at"] ?: ($post["published_at"] ?: $post["created_at"]);
                    $last_mod_formatted = date('Y-m-d', strtotime($last_mod));
                    
                    $xhtml_ns = 'http://www.w3.org/1999/xhtml';
                    $article_url = '/article/' . $post["id"] . '-' . $slug;
                    
                    // German version (no /de/ prefix)
                    $url_de = $sitemap->addChild('url');
                    $url_de->addChild('loc', $base_url . $article_url);
                    $url_de->addChild('lastmod', $last_mod_formatted);
                    $url_de->addChild('priority', '0.8');
                    
                    // Add hreflang links
                    $link_de = $url_de->addChild('xhtml:link', null, $xhtml_ns);
                    $link_de->addAttribute('rel', 'alternate');
                    $link_de->addAttribute('hreflang', 'de');
                    $link_de->addAttribute('href', $base_url . $article_url);
                    
                    $link_en = $url_de->addChild('xhtml:link', null, $xhtml_ns);
                    $link_en->addAttribute('rel', 'alternate');
                    $link_en->addAttribute('hreflang', 'en');
                    $link_en->addAttribute('href', $base_url . '/en' . $article_url);
                    
                    // English version (with /en/ prefix)
                    $url_en = $sitemap->addChild('url');
                    $url_en->addChild('loc', $base_url . '/en' . $article_url);
                    $url_en->addChild('lastmod', $last_mod_formatted);
                    $url_en->addChild('priority', '0.8');
                    
                    // Add hreflang links
                    $link_de_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
                    $link_de_en->addAttribute('rel', 'alternate');
                    $link_de_en->addAttribute('hreflang', 'de');
                    $link_de_en->addAttribute('href', $base_url . $article_url);
                    
                    $link_en_en = $url_en->addChild('xhtml:link', null, $xhtml_ns);
                    $link_en_en->addAttribute('rel', 'alternate');
                    $link_en_en->addAttribute('hreflang', 'en');
                    $link_en_en->addAttribute('href', $base_url . '/en' . $article_url);
                    
                    $article_count++;
                }
                
                echo "[Sitemap] Added " . ($article_count * 2) . " blog article entries from database (both languages)\n";
            } else {
                echo "[Sitemap] Database not available (PDO is null)\n";
            }
        } else {
            echo "[Sitemap] Database helper not found, skipping articles\n";
        }
    } catch (Exception $e) {
        echo "[Sitemap] Warning: Could not load articles - " . $e->getMessage() . "\n";
        echo "[Sitemap] Continuing with static pages only...\n";
    }
}

// Save sitemap to file
// Determine correct output path
$sitemap_path = null;
if (is_writable('/var/www/html')) {
    $sitemap_path = '/var/www/html/sitemap.xml';
} else {
    $sitemap_path = __DIR__ . '/src/sitemap.xml';
}

$xml_content = $sitemap->asXML();

// Pretty print XML
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

try {
    $dom->loadXML($xml_content);
    
    if (file_put_contents($sitemap_path, $dom->saveXML())) {
        echo "[Sitemap] ✓ Sitemap saved successfully to: $sitemap_path\n";
        // Total URLs = (pages * 2) + (articles * 2)
        // added_pages * 2 = each page in both languages
        // article_count = already counted as articles * 2 in the echo above
        echo "[Sitemap] Total URLs: " . ((count($added_pages) + 1) * 2 + $article_count * 2) . "\n";
        exit(0);
    } else {
        echo "[ERROR] Failed to write sitemap to: $sitemap_path\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "[ERROR] XML processing error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
