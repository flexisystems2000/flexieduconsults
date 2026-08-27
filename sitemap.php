<?php
header('Content-Type: application/xml; charset=utf-8');

$firebaseProjectId = "waec2026jamb2027";
$baseUrl = "https://www.flexieduconsult.com.ng";

$urls = [];

// Homepage
$urls[] = [
    'loc' => $baseUrl . '/',
    'priority' => '1.0',
    'lastmod' => date('c')
];

// Fetch news from Firestore
$apiUrl = "https://firestore.googleapis.com/v1/projects/{$firebaseProjectId}/databases/(default)/documents/news";
$response = @file_get_contents($apiUrl);

if ($response) {
    $data = json_decode($response, true);

    if (isset($data['documents'])) {
        foreach ($data['documents'] as $doc) {
            $fields = $doc['fields'] ?? [];
            $docNameParts = explode('/', $doc['name']);
            $docId = end($docNameParts);

            $slug = $fields['slug']['stringValue'] ?? '';
            $timestamp = $fields['timestamp']['timestampValue'] ?? null;

            // Prefer clean slug URL
            if (!empty($slug)) {
                $loc = $baseUrl . '/news/' . rawurlencode($slug);
            } else {
                $loc = $baseUrl . '/news/id/' . $docId;
            }

            $urls[] = [
                'loc' => $loc,
                'priority' => '0.8',
                'lastmod' => $timestamp ? date('c', strtotime($timestamp)) : date('c')
            ];
        }
    }
}

// Optional: Add other important static pages
$staticPages = [
    '/syllabus.html'   => '0.7',
    '/brochure.html'   => '0.7',
    '/cbt.html'        => '0.7',
    '/pdf.html'        => '0.6',
    '/videos.html'     => '0.6',
    '/location.html'   => '0.5',
];

foreach ($staticPages as $path => $priority) {
    $urls[] = [
        'loc' => $baseUrl . $path,
        'priority' => $priority,
        'lastmod' => date('c')
    ];
}

// Build XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as $url) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
    if (!empty($url['lastmod'])) {
        echo "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
    }
    echo "    <priority>" . $url['priority'] . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
