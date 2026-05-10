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

// Initialize sitemap
$sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

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

// Add static pages from pages.json
$added_pages = [];
foreach ($pages_config as $slug => $page) {
    // Add pages that should be in nav or footer
    if ((isset($page["nav"]) && $page["nav"]) || (isset($page["footer"]) && $page["footer"])) {
        $url = $sitemap->addChild('url');
        $url->addChild('loc', $base_url . '/' . $slug);
        $url->addChild('priority', isset($page["nav"]) && $page["nav"] ? '1.0' : '0.8');
        $url->addChild('lastmod', date('Y-m-d'));
        $added_pages[] = $slug;
    }
}

// Add main page if not already added
if (!in_array('main', $added_pages)) {
    $url = $sitemap->addChild('url');
    $url->addChild('loc', $base_url);
    $url->addChild('priority', '1.0');
    $url->addChild('lastmod', date('Y-m-d'));
}

echo "[Sitemap] Added " . count($added_pages) . " static pages\n";

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
                    
                    $url = $sitemap->addChild('url');
                    $url->addChild('loc', $base_url . '/article/' . $post["id"] . '-' . $slug);
                    
                    // Use updated_at if available, otherwise published_at, otherwise created_at
                    $last_mod = $post["updated_at"] ?: ($post["published_at"] ?: $post["created_at"]);
                    $url->addChild('lastmod', date('Y-m-d', strtotime($last_mod)));
                    
                    $url->addChild('priority', '0.8');
                    
                    $article_count++;
                }
                
                echo "[Sitemap] Added $article_count blog articles from database\n";
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
        echo "[Sitemap] Total URLs: " . (count($added_pages) + $article_count + 1) . "\n";
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
