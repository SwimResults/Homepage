<?php
/**
 * Sitemap API Endpoint
 * 
 * Regenerates the sitemap by calling the CLI generator.
 * The sitemap is automatically built during Docker build and startup.
 * Use this endpoint for manual regeneration after content updates.
 * 
 * Usage: curl https://swimresults.de/api/sitemap
 */

header('Content-Type: application/json');

$script_path = dirname(__DIR__) . '/../../generate-sitemap.php';

if (!file_exists($script_path)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Generator script not found']);
    exit;
}

$output = [];
$return_code = 0;
exec('php ' . escapeshellarg($script_path), $output, $return_code);

if ($return_code === 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Sitemap regenerated',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Generation failed',
        'details' => implode("\n", $output)
    ]);
}
?>

if (file_put_contents($sitemap_path, $dom->saveXML())) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Sitemap generated successfully',
        'file' => $sitemap_path,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to write sitemap file'
    ]);
}
?>
