<?php
/**
 * Flexi Educational Consult XML sitemap.
 *
 * Canonical host: https://www.flexieduconsult.com.ng
 * News URLs are sourced from the same Supabase table used by the homepage
 * and news article viewer, so newly published articles are discoverable.
 */
header('Content-Type: application/xml; charset=utf-8');

$baseUrl = 'https://www.flexieduconsult.com.ng';
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

// Add each Literature summary page as a separate indexable resource.
$literatureBooks = [
    'a_man_for_all_seasons',
    'an_inspector_calls',
    'anthony_and_cleopatra',
    'marriage_of_anansewa',
    'once_upon_an_elephant',
    'path_of_lucas',
    'redemption_road',
    'so_the_path_does_not_die',
    'to_kill_a_mockingbird',
    'the_lekki_headmaster'
];

foreach ($literatureBooks as $slug) {
    $urls[] = [
        'loc' => $baseUrl . '/novel.php?book=' . rawurlencode($slug) . '&view=summary',
        'priority' => '0.7'
    ];
}

// Fetch news from Supabase, matching the live homepage/article data source.
$supabaseUrl = 'https://ryvauylmymcvbvvlaceb.supabase.co';
$supabaseKey = 'sb_publishable_zF84MIhSPOZ3MXth_LLqDA_yQ4pIvp6';
$offset = 0;
$pageSize = 1000;
$maxPages = 50;

for ($page = 0; $page < $maxPages; $page++) {
    $apiUrl = $supabaseUrl . '/rest/v1/news'
        . '?select=id,slug,timestamp'
        . '&order=timestamp.desc'
        . '&limit=' . $pageSize
        . '&offset=' . $offset;

    $rows = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $supabaseKey,
                'Accept: application/json'
            ],
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => 15
        ]);

        $response = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response !== false && $status >= 200 && $status < 300) {
            $decoded = json_decode($response, true);
            if (is_array($decoded)) {
                $rows = $decoded;
            }
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "apikey: {$supabaseKey}\r\n"
                    . "Authorization: Bearer {$supabaseKey}\r\n"
                    . "Accept: application/json\r\n",
                'timeout' => 15,
                'ignore_errors' => true
            ]
        ]);

        $response = @file_get_contents($apiUrl, false, $context);
        if ($response !== false) {
            $decoded = json_decode($response, true);
            if (is_array($decoded)) {
                $rows = $decoded;
            }
        }
    }

    if (!is_array($rows) || count($rows) === 0) {
        break;
    }

    foreach ($rows as $row) {
        $docId = trim((string)($row['id'] ?? ''));
        if ($docId === '') {
            continue;
        }

        $slug = trim((string)($row['slug'] ?? ''));
        $loc = $slug !== ''
            ? $baseUrl . '/news/' . rawurlencode($slug)
            : $baseUrl . '/news/id/' . rawurlencode($docId);

        $entry = [
            'loc' => $loc,
            'priority' => '0.8'
        ];

        $timestamp = $row['timestamp'] ?? null;
        if (is_string($timestamp) && $timestamp !== '') {
            $unixTime = strtotime($timestamp);
            if ($unixTime !== false) {
                $entry['lastmod'] = gmdate('Y-m-d', $unixTime);
            }
        }

        $urls[] = $entry;
    }

    $received = count($rows);
    $offset += $received;

    if ($received < $pageSize) {
        break;
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
