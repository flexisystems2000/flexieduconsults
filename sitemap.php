<?php
/**
 * Flexi Educational Consult XML sitemap.
 *
 * Use one canonical host throughout the sitemap and site metadata.
 * Static pages intentionally omit lastmod because deployment dates are
 * not the same as meaningful content modification dates.
 */
header('Content-Type: application/xml; charset=utf-8');

$baseUrl = 'https://flexieduconsult.com.ng';
$urls = [];

// Homepage.
$urls[] = [
    'loc' => $baseUrl . '/',
    'priority' => '1.0'
];

// Public, index-worthy pages. Account, payment, and utility pages are excluded.
$staticPages = [
    '/about-us.php'    => '0.8',
    '/syllabus.php'    => '0.8',
    '/brochure.php'    => '0.7',
    '/cbt.php'         => '0.7',
    '/pdf.php'         => '0.6',
    '/videos.php'      => '0.6',
    '/novel.php'       => '0.8',
    '/scholarship.php' => '0.8',
    '/download-app.php'=> '0.6',
    '/policy.php'      => '0.3',
    '/terms.php'       => '0.3',
    '/corrections.html'=> '0.3'
];

foreach ($staticPages as $path => $priority) {
    $urls[] = [
        'loc' => $baseUrl . $path,
        'priority' => $priority
    ];
}

// News is currently stored in the public Firestore news collection.
$firebaseProjectId = 'waec2026jamb2027';
$apiUrl = 'https://firestore.googleapis.com/v1/projects/'
    . rawurlencode($firebaseProjectId)
    . '/databases/(default)/documents/news';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Accept: application/json\r\n",
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($apiUrl, false, $context);

if ($response !== false) {
    $data = json_decode($response, true);

    if (is_array($data) && !empty($data['documents']) && is_array($data['documents'])) {
        foreach ($data['documents'] as $doc) {
            $fields = $doc['fields'] ?? [];
            $docName = (string)($doc['name'] ?? '');
            $parts = explode('/', $docName);
            $docId = (string)end($parts);

            if ($docId === '') {
                continue;
            }

            $slug = trim((string)($fields['slug']['stringValue'] ?? ''));
            $loc = $slug !== ''
                ? $baseUrl . '/news/' . rawurlencode($slug)
                : $baseUrl . '/news/id/' . rawurlencode($docId);

            $entry = [
                'loc' => $loc,
                'priority' => '0.8'
            ];

            $timestamp = $fields['timestamp']['timestampValue'] ?? null;
            if (is_string($timestamp) && $timestamp !== '') {
                $unixTime = strtotime($timestamp);
                if ($unixTime !== false) {
                    $entry['lastmod'] = gmdate('Y-m-d', $unixTime);
                }
            }

            $urls[] = $entry;
        }
    }
}

// Remove duplicate locations before output.
$unique = [];
foreach ($urls as $url) {
    $unique[$url['loc']] = $url;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($unique as $url) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";

    if (!empty($url['lastmod'])) {
        echo '    <lastmod>' . htmlspecialchars($url['lastmod'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
    }

    echo '    <priority>' . htmlspecialchars($url['priority'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
