<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// HOMEPAGE - SERVER RENDERED VERSION
// All homepage UI, CSS and JavaScript are contained in this file.
// ============================================================

function esc($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function firestoreValueToPhp($value) {
    if (!is_array($value)) return $value;
    if (array_key_exists('stringValue', $value)) return $value['stringValue'];
    if (array_key_exists('integerValue', $value)) return (int)$value['integerValue'];
    if (array_key_exists('doubleValue', $value)) return (float)$value['doubleValue'];
    if (array_key_exists('booleanValue', $value)) return (bool)$value['booleanValue'];
    if (array_key_exists('timestampValue', $value)) return $value['timestampValue'];
    if (array_key_exists('nullValue', $value)) return null;
    if (array_key_exists('referenceValue', $value)) return $value['referenceValue'];
    if (array_key_exists('bytesValue', $value)) return $value['bytesValue'];
    if (array_key_exists('arrayValue', $value)) {
        $result = [];
        foreach (($value['arrayValue']['values'] ?? []) as $item) $result[] = firestoreValueToPhp($item);
        return $result;
    }
    if (array_key_exists('mapValue', $value)) {
        $result = [];
        foreach (($value['mapValue']['fields'] ?? []) as $key => $item) $result[$key] = firestoreValueToPhp($item);
        return $result;
    }
    return null;
}

function firestoreDocumentToPhp($document) {
    $result = [];
    foreach (($document['fields'] ?? []) as $key => $value) $result[$key] = firestoreValueToPhp($value);
    return $result;
}

function firestoreTimestampToUnix($value) {
    if (!$value) return 0;
    if (is_numeric($value)) return (int)$value;
    $timestamp = strtotime((string)$value);
    return $timestamp !== false ? $timestamp : 0;
}

function fetchFirestoreCollection($collectionName, $baseUrl) {
    $url = $baseUrl . rawurlencode($collectionName);
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Accept: application/json\r\nContent-Type: application/json\r\n",
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) return [];
    $decoded = json_decode($response, true);
    if (!is_array($decoded) || !isset($decoded['documents']) || !is_array($decoded['documents'])) return [];
    $documents = [];
    foreach ($decoded['documents'] as $document) $documents[] = firestoreDocumentToPhp($document);
    return $documents;
}

// ============================================================
// FIRESTORE
// ============================================================
$firestoreProjectId = 'waec2026jamb2027';
$firestoreBaseUrl = 'https://firestore.googleapis.com/v1/projects/' . rawurlencode($firestoreProjectId) . '/databases/(default)/documents/';

$serverAnnouncement = null;
$announcementDocuments = fetchFirestoreCollection('announcements', $firestoreBaseUrl);
if (!empty($announcementDocuments)) {
    usort($announcementDocuments, function($a, $b) {
        return firestoreTimestampToUnix($b['createdAt'] ?? null) <=> firestoreTimestampToUnix($a['createdAt'] ?? null);
    });
    $latest = $announcementDocuments[0];
    $title = trim((string)($latest['title'] ?? ''));
    $content = trim((string)($latest['content'] ?? ''));
    $timestamp = firestoreTimestampToUnix($latest['createdAt'] ?? null);
    if ($title !== '' || $content !== '') {
        $serverAnnouncement = [
            'title' => $title,
            'content' => $content,
            'timestamp' => $timestamp,
            'newsLink' => trim((string)($latest['newsLink'] ?? ''))
        ];
    }
}

$serverSystemTickerHtml = '';
if ($serverAnnouncement) {
    $linkStart = !empty($serverAnnouncement['newsLink']) ? '<a class="system-ticker-link" href="' . esc($serverAnnouncement['newsLink']) . '">' : '';
    $linkEnd = !empty($serverAnnouncement['newsLink']) ? '</a>' : '';
    $titleHtml = $serverAnnouncement['title'] !== '' ? '<span class="system-ticker-title">' . esc($serverAnnouncement['title']) . '</span>' : '';
    $contentHtml = $serverAnnouncement['content'] !== '' ? '<span class="system-ticker-content">' . esc($serverAnnouncement['content']) . '</span>' : '';
    $dateHtml = !empty($serverAnnouncement['timestamp']) ? '<span class="system-ticker-date">Posted: ' . esc(date('j M Y', $serverAnnouncement['timestamp'])) . '</span>' : '';
    $item = '<span class="system-ticker-item">' . $linkStart . $titleHtml . $contentHtml . $dateHtml . $linkEnd . '</span>';
    $duplicate = '<span class="system-ticker-item">' . $linkStart . $titleHtml . $contentHtml . $linkEnd . '</span>';
    $serverSystemTickerHtml = '<div class="system-ticker" role="status" aria-label="System Update"><div class="system-ticker-label"><span class="ticker-pulse"></span> SYSTEM UPDATE</div><div class="system-ticker-window"><div class="system-ticker-track">' . $item . $duplicate . '</div></div></div>';
}

// ============================================================
// QUOTES
// ============================================================
$serverQuotes = [];
$quoteDocuments = fetchFirestoreCollection('quotes', $firestoreBaseUrl);
if (!empty($quoteDocuments)) {
    usort($quoteDocuments, function($a, $b) {
        return firestoreTimestampToUnix($b['createdAt'] ?? null) <=> firestoreTimestampToUnix($a['createdAt'] ?? null);
    });
    foreach ($quoteDocuments as $quote) {
        $text = trim((string)($quote['text'] ?? ''));
        if ($text === '') continue;
        $serverQuotes[] = [
            'text' => $text,
            'author' => trim((string)($quote['author'] ?? '')),
            'bgImage' => trim((string)($quote['bgImage'] ?? '')),
            'textColor' => trim((string)($quote['textColor'] ?? '#ffffff')),
            'authorColor' => trim((string)($quote['authorColor'] ?? '#f1f5f9'))
        ];
    }
}

$serverQuoteHtml = '';
$serverQuoteDotsHtml = '';
foreach ($serverQuotes as $index => $quote) {
    $active = $index === 0 ? ' active' : '';
    $bg = $quote['bgImage'] !== '' ? "background-image:url('" . esc($quote['bgImage']) . "');" : '';
    $serverQuoteHtml .= '<div class="quote-slide' . $active . '" style="' . $bg . '"><div class="quote-overlay"></div><div class="quote-content-box"><div class="quote-mark">“</div><blockquote style="color:' . esc($quote['textColor']) . ';">' . esc($quote['text']) . '</blockquote>' . ($quote['author'] !== '' ? '<cite style="color:' . esc($quote['authorColor']) . ';">— ' . esc($quote['author']) . '</cite>' : '') . '</div></div>';
    $serverQuoteDotsHtml .= '<span class="dot' . ($index === 0 ? ' active-dot' : '') . '"></span>';
}

// ============================================================
// SUPABASE NEWS
// ============================================================
$supabaseUrl = 'https://ryvauylmymcvbvvlaceb.supabase.co';
$supabaseKey = 'sb_publishable_zF84MIhSPOZ3MXth_LLqDA_yQ4pIvp6';
$newsApiUrl = $supabaseUrl . '/rest/v1/news?select=id,title,image_url,slug,timestamp&order=timestamp.desc';
$perPage = 10;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$allNews = [];
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "apikey: {$supabaseKey}\r\nAuthorization: Bearer {$supabaseKey}\r\nAccept: application/json\r\n",
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);
$response = @file_get_contents($newsApiUrl, false, $context);
if ($response !== false) {
    $data = json_decode($response, true);
    if (is_array($data)) {
        foreach ($data as $row) {
            $allNews[] = [
                'id' => $row['id'] ?? '',
                'title' => $row['title'] ?? 'News Update',
                'imageUrl' => $row['image_url'] ?? 'https://via.placeholder.com/100x72',
                'slug' => $row['slug'] ?? '',
                'timestamp' => !empty($row['timestamp']) ? strtotime($row['timestamp']) : 0
            ];
        }
    }
}
usort($allNews, function($a, $b) { return $b['timestamp'] <=> $a['timestamp']; });
$totalItems = count($allNews);
$totalPages = max(1, (int)ceil($totalItems / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$newsForPage = array_slice($allNews, $offset, $perPage);

$serverNewsHtml = '';
if (empty($newsForPage)) {
    $serverNewsHtml = '<div class="news-empty">No news updates available at the moment.</div>';
} else {
    foreach ($newsForPage as $item) {
        $targetUrl = $item['slug'] ? '/news/' . rawurlencode($item['slug']) : '/news/id/' . rawurlencode($item['id']);
        $serverNewsHtml .= '<a class="news-item" href="' . esc($targetUrl) . '">';
        $serverNewsHtml .= '<div class="news-thumb-wrap"><img src="' . esc($item['imageUrl']) . '" alt="' . esc($item['title']) . '" class="td-img" loading="lazy"></div>';
        $serverNewsHtml .= '<div class="news-copy"><div class="news-title">' . esc($item['title']) . '</div>';
        if (!empty($item['timestamp'])) $serverNewsHtml .= '<div class="news-date">' . esc(date('j M Y', $item['timestamp'])) . '</div>';
        $serverNewsHtml .= '</div><div class="news-arrow" aria-hidden="true">→</div></a>';
    }
}

function buildPagination($currentPage, $totalPages) {
    if ($totalPages <= 1) return '';
    $html = '<nav class="pagination-bar" aria-label="News pages">';
    if ($currentPage > 1) $html .= '<a href="?page=' . ($currentPage - 1) . '" class="pg-btn" aria-label="Previous page">‹</a>';
    else $html .= '<span class="pg-btn disabled">‹</span>';
    $range = 2;
    $start = max(1, $currentPage - $range);
    $end = min($totalPages, $currentPage + $range);
    if ($start > 1) {
        $html .= '<a href="?page=1" class="pg-btn">1</a>';
        if ($start > 2) $html .= '<span class="pg-ellipsis">…</span>';
    }
    for ($i = $start; $i <= $end; $i++) {
        $html .= $i == $currentPage ? '<span class="pg-btn pg-active">' . $i . '</span>' : '<a href="?page=' . $i . '" class="pg-btn">' . $i . '</a>';
    }
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) $html .= '<span class="pg-ellipsis">…</span>';
        $html .= '<a href="?page=' . $totalPages . '" class="pg-btn">' . $totalPages . '</a>';
    }
    if ($currentPage < $totalPages) $html .= '<a href="?page=' . ($currentPage + 1) . '" class="pg-btn" aria-label="Next page">›</a>';
    else $html .= '<span class="pg-btn disabled">›</span>';
    return $html . '</nav>';
}
$paginationHtml = buildPagination($currentPage, $totalPages);
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.">
<meta name="keywords" content="flexieduconsult, Flexi Educational Consult, Flexi Tutors, JAMB, WAEC, NECO, CBT, jamb tutorials, waec tutorials, tutorials in shomolu, tutorials in bariga">
<meta property="og:title" content="Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria">
<meta property="og:description" content="Flexi Educational Consult — CBT preparation, tutorials, past questions and student resources.">
<meta property="og:image" content="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<meta property="og:url" content="https://flexieduconsult.com.ng/">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<link rel="canonical" href="https://flexieduconsult.com.ng/<?php echo $currentPage > 1 ? '?page=' . $currentPage : ''; ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180" crossorigin="anonymous"></script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"EducationalOrganization",
  "@id":"https://flexieduconsult.com.ng/#organization",
  "name":"Flexi Educational Consult",
  "alternateName":"Flexi Tutors",
  "url":"https://flexieduconsult.com.ng",
  "logo":"https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg",
  "image":"https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg",
  "description":"Educational platform and tutoring provider in Nigeria offering UTME/JAMB CBT preparation, WAEC/NECO tutorials, and computer-based test assessments.",
  "address":{"@type":"PostalAddress","addressCountry":"NG"},
  "areaServed":"NG",
  "sameAs":["https://www.facebook.com/share/1HgB8GWwZ1/","https://www.linkedin.com/company/flexi-educational-consult"]
}
</script>
<style>
:root{--blue:#003366;--blue2:#071f3d;--green:#2e8b57;--green2:#22a06b;--yellow:#ffd700;--bg:#f3f6f8;--white:#fff;--text:#263442;--muted:#718096;--line:#e5ebef;--radius:16px;--shadow:0 10px 30px rgba(0,32,63,.08);--shadow-lg:0 20px 55px rgba(0,32,63,.14)}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{margin:0;background:var(--bg);color:var(--text);font-family:'Segoe UI',system-ui,-apple-system,BlinkMacSystemFont,sans-serif}
a{text-decoration:none;color:inherit}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.top-header{height:72px;background:linear-gradient(110deg,#002b55,#003d70);color:#fff;position:sticky;top:0;z-index:1000;border-bottom:3px solid var(--green);box-shadow:0 4px 18px rgba(0,0,0,.13)}
.header-inner{height:100%;max-width:1320px;width:calc(100% - 40px);margin:auto;display:flex;align-items:center;justify-content:space-between;gap:30px}
.brand{display:flex;align-items:center;gap:12px;min-width:0}.logo-img{width:42px;height:42px;border-radius:10px;object-fit:cover;background:#fff;box-shadow:0 3px 12px rgba(0,0,0,.2)}.brand-copy{min-width:0}.brand-name{font-size:16px;font-weight:800;letter-spacing:.2px}.brand-tag{font-size:11px;color:#b8d4e9;margin-top:2px}
.desktop-nav{display:flex;align-items:center;gap:4px;margin-left:auto}.desktop-nav>a,.nav-dropdown>button{border:0;background:transparent;color:#eaf4fb;padding:11px 12px;border-radius:10px;font:inherit;font-size:13px;font-weight:650;cursor:pointer;transition:.2s}.desktop-nav>a:hover,.nav-dropdown>button:hover{background:rgba(255,255,255,.1);color:#fff}
.nav-dropdown{position:relative}.nav-dropdown>button{display:flex;align-items:center;gap:6px}.chevron{font-size:10px;transition:transform .2s}.nav-dropdown:hover .chevron{transform:rotate(180deg)}
.dropdown-panel{position:absolute;right:0;top:calc(100% + 10px);width:240px;background:#fff;border:1px solid var(--line);border-radius:14px;padding:8px;box-shadow:var(--shadow-lg);opacity:0;visibility:hidden;transform:translateY(-5px);transition:.2s}.nav-dropdown:hover .dropdown-panel{opacity:1;visibility:visible;transform:translateY(0)}.dropdown-panel a{display:flex;align-items:center;gap:10px;padding:11px 12px;border-radius:9px;color:var(--text);font-size:13px}.dropdown-panel a:hover{background:#eef8f3;color:var(--green)}
.menu-container{position:relative}.menu-btn{width:42px;height:42px;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.06);border-radius:11px;display:none;flex-direction:column;justify-content:center;align-items:center;gap:5px;cursor:pointer}.menu-btn span{width:20px;height:2px;background:#fff;border-radius:2px;transition:.2s}
.mobile-menu{display:none;position:absolute;right:0;top:52px;width:290px;max-width:calc(100vw - 24px);background:#fff;border:1px solid var(--line);border-radius:16px;padding:8px;box-shadow:var(--shadow-lg);z-index:2000}.mobile-menu.open{display:block}.mobile-menu a,.mobile-study-toggle{display:flex;align-items:center;justify-content:space-between;width:100%;padding:13px 14px;border:0;border-radius:10px;background:transparent;color:var(--text);font:inherit;font-size:14px;text-align:left;cursor:pointer}.mobile-menu a:hover,.mobile-study-toggle:hover{background:#eef8f3;color:var(--green)}.mobile-study-toggle span:first-child{display:flex;align-items:center;gap:9px}.mobile-submenu{display:none;padding:2px 0 5px 12px}.mobile-submenu.open{display:block}.mobile-submenu a{font-size:13px;padding:10px 14px;color:#526273}.menu-divider{height:1px;background:var(--line);margin:6px 4px}
.page-shell{max-width:1320px;width:calc(100% - 40px);margin:28px auto 0}.welcome{display:none;background:#fff;border:1px solid #dce9e2;border-left:5px solid var(--green);border-radius:14px;padding:14px 18px;margin-bottom:18px;box-shadow:var(--shadow)}
.system-ticker{display:flex;align-items:stretch;overflow:hidden;background:#eaf8ef;border:1px solid #ccebd7;border-left:5px solid var(--green);border-radius:14px;box-shadow:var(--shadow);margin-bottom:20px}.system-ticker-label{flex:0 0 auto;display:flex;align-items:center;padding:0 16px;background:#d7f1df;color:#17633b;font-size:11px;font-weight:850;letter-spacing:.7px;white-space:nowrap}.ticker-pulse{width:7px;height:7px;border-radius:50%;background:#21a365;margin-right:7px;box-shadow:0 0 0 4px rgba(33,163,101,.13)}.system-ticker-window{overflow:hidden;flex:1;min-width:0;height:46px;display:flex;align-items:center}.system-ticker-track{display:inline-flex;white-space:nowrap;min-width:max-content;animation:systemTickerMove 24s linear infinite}.system-ticker-track:hover{animation-play-state:paused}.system-ticker-item{display:inline-flex;align-items:center;font-size:13px;padding-right:100px}.system-ticker-link{display:inline-flex;align-items:center;color:inherit}.system-ticker-title{font-weight:800;color:#187044;margin-right:8px}.system-ticker-content{color:#24553a}.system-ticker-date{font-size:11px;color:#64836e;margin-left:12px}@keyframes systemTickerMove{0%{transform:translateX(100%)}100%{transform:translateX(-100%)}}
.quote-slider-container{position:relative;min-height:190px;border-radius:18px;overflow:hidden;background:#002b55;box-shadow:var(--shadow-lg);margin-bottom:22px}.quote-slide{position:absolute;inset:0;display:flex;align-items:center;background-size:cover;background-position:center;opacity:0;transition:opacity .8s}.quote-slide.active{opacity:1}.quote-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(0,25,50,.86),rgba(0,35,70,.45),rgba(0,0,0,.18))}.quote-content-box{position:relative;z-index:1;width:min(850px,90%);padding:25px 34px}.quote-mark{font-family:Georgia,serif;color:rgba(255,255,255,.35);font-size:60px;height:38px;line-height:1}.quote-content-box blockquote{margin:0 0 10px;font-size:1.15rem;line-height:1.55;font-style:italic;text-shadow:0 2px 8px rgba(0,0,0,.35)}.quote-content-box cite{font-size:.9rem;font-style:normal;font-weight:700}
.main-grid{display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:24px;align-items:start}.main-column,.side-column{min-width:0}.hero-slider{position:relative;height:320px;border-radius:18px;overflow:hidden;background:#001f3f;box-shadow:var(--shadow-lg);margin-bottom:24px}.slider-slide{position:absolute;inset:0;opacity:0;pointer-events:none;transition:opacity .65s}.slider-slide.active{opacity:1;pointer-events:auto}.slider-slide img{width:100%;height:100%;object-fit:cover;display:block}.slider-slide::after{content:'';position:absolute;inset:auto 0 0;height:42%;background:linear-gradient(transparent,rgba(0,0,0,.55));pointer-events:none}.slider-dots{position:absolute;bottom:13px;left:0;width:100%;text-align:center;z-index:3}.dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.48);margin:0 4px;transition:.2s}.active-dot{background:#fff;transform:scale(1.2)}
.quick-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:18px;box-shadow:var(--shadow);margin-bottom:20px}.quick-title{font-size:12px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:800;margin-bottom:13px}.quick-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px}.quick-link{display:flex;align-items:center;gap:9px;padding:11px 10px;border:1px solid var(--line);border-radius:11px;color:var(--blue);font-size:12px;font-weight:700;transition:.2s}.quick-link:hover{border-color:#b9dcc8;background:#f1faf5;transform:translateY(-1px)}.quick-icon{width:28px;height:28px;display:grid;place-items:center;border-radius:8px;background:#eaf7f0;color:var(--green);font-size:14px;flex:none}
.section-heading{display:flex;align-items:center;gap:10px;color:var(--blue);font-size:1.22rem;margin:0 0 13px;font-weight:800}.section-heading::before{content:'';width:5px;height:25px;background:var(--green);border-radius:5px}.section-subtitle{font-size:12px;color:var(--muted);margin:-5px 0 14px 15px}
.news-card{background:#fff;border:1px solid var(--line);border-radius:18px;overflow:hidden;box-shadow:var(--shadow)}.news-item{display:grid;grid-template-columns:92px minmax(0,1fr) 30px;gap:14px;align-items:center;padding:14px 16px;border-bottom:1px solid var(--line);transition:.2s}.news-item:last-child{border-bottom:0}.news-item:hover{background:#f8fbfa}.news-thumb-wrap{width:92px;height:66px;overflow:hidden;border-radius:10px;background:#edf2f5}.td-img{width:100%;height:100%;object-fit:cover;display:block}.news-title{font-size:14px;font-weight:700;color:var(--blue);line-height:1.4}.news-date{font-size:11px;color:var(--muted);margin-top:5px}.news-arrow{text-align:right;color:#9aa8b4;font-size:20px;transition:.2s}.news-item:hover .news-arrow{color:var(--green);transform:translateX(3px)}.news-empty{padding:20px;color:var(--muted);font-size:14px}.pagination-bar{display:flex;justify-content:center;align-items:center;gap:6px;margin:18px 0 28px;flex-wrap:wrap}.pg-btn{min-width:34px;height:34px;border:1px solid #d9e1e6;background:#fff;color:var(--blue);border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:750;box-shadow:0 2px 6px rgba(0,0,0,.03)}.pg-btn:hover{border-color:var(--green);color:var(--green)}.pg-active{background:var(--blue);color:#fff;border-color:var(--blue)}.pg-btn.disabled{opacity:.35}.pg-ellipsis{padding:0 2px;color:#8a99a6}
.sidebar-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:19px;box-shadow:var(--shadow);margin-bottom:20px}.sidebar-title{margin:0 0 13px;color:var(--blue);font-size:15px;font-weight:800}.resource-list{display:grid;gap:7px}.resource-link{display:flex;align-items:center;gap:10px;padding:11px 10px;border-radius:10px;background:#f7f9fa;color:#33475b;font-size:13px;font-weight:650;transition:.2s}.resource-link:hover{background:#edf8f2;color:var(--green);padding-left:13px}.resource-link .r-icon{width:26px;height:26px;border-radius:8px;background:#e7f4ed;color:var(--green);display:grid;place-items:center;font-size:13px;flex:none}.download-card{background:linear-gradient(145deg,#003366,#006447);color:#fff;border-radius:18px;padding:22px;box-shadow:var(--shadow-lg);position:relative;overflow:hidden}.download-card::after{content:'';position:absolute;width:130px;height:130px;border-radius:50%;background:rgba(255,255,255,.08);right:-45px;top:-45px}.download-card h3{margin:0 0 7px;font-size:18px}.download-card p{margin:0 0 17px;color:#d8ece4;font-size:12px;line-height:1.55}.download-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#fff;color:#075c3c;padding:11px 14px;border-radius:10px;font-weight:800;font-size:13px;position:relative;z-index:1}.download-btn:hover{background:#f0fff7}
.testimonials-section{margin-top:30px}.testimonial-slider-container{position:relative;height:164px;overflow:hidden;background:#fff;border:1px solid var(--line);border-radius:18px;box-shadow:var(--shadow)}.testimonial-slide{position:absolute;inset:0;padding:22px 24px;display:flex;flex-direction:column;justify-content:center;transform:translateX(100%);transition:transform .6s cubic-bezier(.25,1,.5,1)}.testimonial-slide.active{transform:translateX(0)}.testimonial-slide.exit{transform:translateX(-100%)}.student-info{display:flex;align-items:center;gap:11px;margin-bottom:9px}.student-avatar{width:40px;height:40px;border-radius:50%;background:#e7f0f4;color:var(--blue);display:grid;place-items:center;font-weight:800;font-size:12px;border:2px solid var(--green)}.student-details h4{margin:0;color:var(--blue);font-size:14px}.student-details span{font-size:11px;color:var(--muted)}.testimonial-text{margin:0;font-size:13px;color:#526170;line-height:1.55;font-style:italic}
.customer-card{background:#fff;border:1px solid var(--line);border-top:4px solid var(--green);border-radius:18px;padding:24px;box-shadow:var(--shadow);margin-top:24px}.customer-card h3{margin:0 0 15px;color:var(--blue);font-size:17px}.customer-card input,.customer-card textarea{width:100%;padding:12px 13px;border:1px solid #d5dfe5;border-radius:10px;margin-bottom:11px;font:inherit;font-size:13px;outline:none}.customer-card input:focus,.customer-card textarea:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(46,139,87,.1)}.btn-green{width:100%;border:0;background:var(--green);color:#fff;border-radius:10px;padding:13px;font-weight:800;cursor:pointer}.btn-green:hover{background:#24784a}
.footer{background:linear-gradient(135deg,#011627,#032038);color:#dce5ed;padding:52px 20px 25px;margin-top:45px;border-top:4px solid var(--green)}.footer-grid{max-width:1320px;width:calc(100% - 40px);margin:auto;display:grid;grid-template-columns:1.7fr 1.2fr 1.25fr 1fr;gap:38px}.footer h4{color:var(--yellow);font-size:12px;text-transform:uppercase;letter-spacing:1.2px;margin:0 0 16px}.footer-about{line-height:1.75;color:#94a3b8;font-size:13px;margin:0}.footer-links-list{list-style:none;padding:0;margin:0}.footer-links-list li{margin-bottom:9px}.footer a{color:#cbd5e0;font-size:13px}.footer a:hover{color:#fff}.whatsapp-channel-link{color:#25d366!important;font-weight:700}.contact-group{margin-bottom:15px}.contact-group h5{color:#fff;font-size:11px;text-transform:uppercase;letter-spacing:.8px;margin:0 0 6px}.contact-group a{display:block;color:#94a3b8;margin-bottom:4px}.footer-bottom{max-width:1320px;width:calc(100% - 40px);margin:35px auto 0;padding-top:18px;border-top:1px solid rgba(255,255,255,.09);text-align:center;color:#64748b;font-size:12px}
#support-widget{position:fixed;right:22px;bottom:22px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:9px}#support-tooltip{background:var(--blue);color:#fff;padding:9px 13px;border-radius:18px 18px 3px 18px;font-size:12px;box-shadow:0 5px 18px rgba(0,0,0,.18);animation:fadeIn .5s}#support-btn{background:var(--green);border:0;width:58px;height:58px;border-radius:50%;cursor:pointer;box-shadow:0 6px 20px rgba(0,0,0,.25);display:flex;align-items:center;justify-content:center;transition:.25s}#support-btn:hover{transform:scale(1.08)}
@keyframes fadeIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:1050px){.desktop-nav{gap:0}.desktop-nav>a,.nav-dropdown>button{padding:10px 8px;font-size:12px}.main-grid{grid-template-columns:minmax(0,1fr) 300px}.hero-slider{height:280px}.footer-grid{grid-template-columns:1.5fr 1fr 1fr 1fr}}
@media(max-width:850px){.top-header{height:62px}.header-inner{width:calc(100% - 24px)}.desktop-nav{display:none}.menu-btn{display:flex}.brand-tag{display:none}.brand-name{font-size:14px}.logo-img{width:38px;height:38px}.page-shell{width:calc(100% - 24px);margin-top:16px}.main-grid{display:block}.side-column{display:block}.hero-slider{height:230px}.quote-slider-container{min-height:180px}.quick-card{margin-top:20px}.quick-grid{grid-template-columns:repeat(4,1fr)}.sidebar-card{display:none}.download-card{margin-top:20px}.footer-grid{grid-template-columns:1fr 1fr}.customer-card{margin-top:22px}}
@media(max-width:560px){.system-ticker-label{padding:0 10px;font-size:9px}.system-ticker-item{font-size:12px}.page-shell{width:calc(100% - 20px)}.hero-slider{height:145px;border-radius:14px}.quote-slider-container{min-height:190px;border-radius:14px}.quote-content-box{padding:20px}.quote-content-box blockquote{font-size:1rem}.quick-grid{grid-template-columns:1fr 1fr}.news-item{grid-template-columns:72px minmax(0,1fr) 20px;padding:11px 12px;gap:10px}.news-thumb-wrap{width:72px;height:54px}.news-title{font-size:13px}.news-date{font-size:10px}.news-arrow{font-size:17px}.section-heading{font-size:1.08rem}.testimonial-slider-container{height:174px}.customer-card{padding:18px}.footer{padding-top:40px}.footer-grid{grid-template-columns:1fr;gap:27px}.footer-bottom{width:calc(100% - 20px)}#support-widget{right:14px;bottom:14px}#support-tooltip{font-size:11px}}
@media(min-width:851px){.side-column{position:sticky;top:96px}.main-column .testimonials-section{margin-top:30px}}
</style>
</head>
<body>
<header class="top-header">
    <div class="header-inner">
        <a href="/index.php" class="brand" aria-label="Flexi Educational Consult home">
            <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Flexi Educational Consult Official Logo" class="logo-img">
            <div class="brand-copy"><div class="brand-name">Flexi Educational Consult</div><div class="brand-tag">Learn • Practice • Succeed</div></div>
        </a>

        <nav class="desktop-nav" aria-label="Primary navigation">
            <a href="/index.php">Home</a>
            <a href="/videos.html">Video Lessons</a>
            <div class="nav-dropdown">
                <button type="button">Study <span class="chevron">▼</span></button>
                <div class="dropdown-panel">
                    <a href="/study.php"><span>📚</span> Past Questions</a>
                    <a href="/novel.php"><span>📖</span> Novels</a>
                    <a href="/scholarship.php"><span>🎓</span> Scholarship</a>
                </div>
            </div>
            <a href="/cbt.html">CBT Simulator</a>
            <a href="/groups.html">Classroom</a>
            <a href="/download-app.php">Download Flexi</a>
            <a href="/login.html" id="desktop-auth-btn">Login</a>
        </nav>

        <div class="menu-container">
            <button class="menu-btn" id="menuBtn" type="button" onclick="toggleMenu()" aria-label="Open navigation menu" aria-expanded="false"><span></span><span></span><span></span></button>
            <div class="mobile-menu" id="squareMenu">
                <a href="/index.php">Home</a>
                <a href="/videos.html">Watch Video Lessons</a>
                <div class="menu-divider"></div>
                <button class="mobile-study-toggle" type="button" onclick="toggleStudySubmenu()" aria-expanded="false"><span>📚 Study</span><span id="studyChevron">⌄</span></button>
                <div class="mobile-submenu" id="studySubmenu">
                    <a href="/study.php">Past Questions</a>
                    <a href="/novel.php">Novels</a>
                    <a href="/scholarship.php">Scholarship</a>
                </div>
                <a href="/syllabus.html">JAMB &amp; WAEC Syllabus</a>
                <a href="/brochure.html">JAMB Brochure</a>
                <a href="/cbt.html">CBT Simulator</a>
                <a href="/groups.html">Classroom</a>
                <a href="/purchase.html">Purchase Scratch Cards</a>
                <a href="/pdf.html">PDFs</a>
                <a href="/location.html">Tutorial Centres</a>
                <a href="/profile.html">User Profile</a>
                <a href="/download-app.php">⬇ Download Flexi</a>
                <div class="menu-divider"></div>
                <a href="/login.html" id="auth-menu-btn">Login</a>
            </div>
        </div>
    </div>
</header>

<main class="page-shell">
    <h1 class="sr-only">Flexi Educational Consult - Online JAMB WAEC CBT Exam Practice Portal</h1>
    <div id="welcome-banner" class="welcome"><span id="user-greeting" style="font-weight:700;color:var(--blue);font-size:1rem"></span></div>
    <?php echo $serverSystemTickerHtml; ?>

    <?php if (!empty($serverQuotes)): ?>
    <section id="quote-slider-container" class="quote-slider-container" aria-label="Motivational quotes">
        <?php echo $serverQuoteHtml; ?>
        <div id="quote-dots" class="slider-dots"><?php echo $serverQuoteDotsHtml; ?></div>
    </section>
    <?php endif; ?>

    <div class="main-grid">
        <div class="main-column">
            <section class="hero-slider" aria-label="Featured Flexi resources">
                <a href="/download-app.php" class="slider-slide active"><img src="https://i.postimg.cc/XvkqQc3F/20260418-185555-2.jpg" alt="JAMB UTME CBT Online Exam Practice Banner"></a>
                <a href="/purchase.html" class="slider-slide"><img src="https://i.postimg.cc/pXBjLFpj/20260418-190953-2.jpg" alt="WAEC NECO SSCE Preparation Tutorials Banner"></a>
                <a href="/purchase.html" class="slider-slide"><img src="https://i.postimg.cc/76p3Srkc/Screenshot-20260418-191139-2.png" alt="Flexi Educational Consult Academic Registration Banner"></a>
                <div class="slider-dots"><span class="dot active-dot"></span><span class="dot"></span><span class="dot"></span></div>
            </section>

            <section aria-labelledby="news-heading">
                <h2 class="section-heading" id="news-heading">News Updates</h2>
                <div class="section-subtitle">Stay informed about important academic and admission updates.</div>
                <div class="news-card"><?php echo $serverNewsHtml; ?></div>
                <?php echo $paginationHtml; ?>
            </section>

            <section class="testimonials-section" aria-labelledby="testimonials-heading">
                <h2 class="section-heading" id="testimonials-heading">What Our Students Say</h2>
                <div class="testimonial-slider-container">
                    <div class="testimonial-slide active"><div class="student-info"><div class="student-avatar">CO</div><div class="student-details"><h4>Chidi O.</h4><span>JAMB Candidate (Score: 312)</span></div></div><p class="testimonial-text">“The CBT Simulator on Flexi Tutors is the closest thing to the actual JAMB exam. It gave me the speed and accuracy I needed to score over 300!”</p></div>
                    <div class="testimonial-slide"><div class="student-info"><div class="student-avatar">AA</div><div class="student-details"><h4>Amina A.</h4><span>WAEC Student (5 A1s)</span></div></div><p class="testimonial-text">“I downloaded all my past questions in PDF here. The online classroom groups kept me accountable during my final revisions. Thank you, Flexi!”</p></div>
                    <div class="testimonial-slide"><div class="student-info"><div class="student-avatar">TE</div><div class="student-details"><h4>Tunde E.</h4><span>Post-UTME Student</span></div></div><p class="testimonial-text">“Highly recommended! The instant admission updates kept me from missing important post-UTME screening dates.”</p></div>
                </div>
            </section>

            <section class="customer-card">
                <h3>Customer Care</h3>
                <form id="contact-form">
                    <input type="email" name="email" id="contact-email" placeholder="Your Email Address" required>
                    <textarea name="message" rows="3" placeholder="How can we help you?" required></textarea>
                    <button type="submit" id="submit-btn" class="btn-green">Submit Inquiry</button>
                </form>
            </section>
        </div>

        <aside class="side-column" aria-label="Flexi resources">
            <div class="quick-card">
                <div class="quick-title">Quick Access</div>
                <div class="quick-grid">
                    <a class="quick-link" href="/study.php"><span class="quick-icon">📚</span>Past Questions</a>
                    <a class="quick-link" href="/novel.php"><span class="quick-icon">📖</span>Novels</a>
                    <a class="quick-link" href="/scholarship.php"><span class="quick-icon">🎓</span>Scholarship</a>
                    <a class="quick-link" href="/download-app.php"><span class="quick-icon">⬇</span>Download</a>
                </div>
            </div>

            <div class="download-card">
                <h3>Get Flexi on your device</h3>
                <p>Access your learning resources, practice and Flexi services more conveniently.</p>
                <a class="download-btn" href="/download-app.php">Download Flexi →</a>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title">Explore Flexi</h3>
                <div class="resource-list">
                    <a class="resource-link" href="/syllabus.html"><span class="r-icon">✓</span>JAMB &amp; WAEC Syllabus</a>
                    <a class="resource-link" href="/brochure.html"><span class="r-icon">▤</span>JAMB Brochure</a>
                    <a class="resource-link" href="/cbt.html"><span class="r-icon">⌨</span>CBT Simulator</a>
                    <a class="resource-link" href="/groups.html"><span class="r-icon">👥</span>Classroom</a>
                    <a class="resource-link" href="/pdf.html"><span class="r-icon">PDF</span>PDF Resources</a>
                    <a class="resource-link" href="/location.html"><span class="r-icon">⌖</span>Tutorial Centres</a>
                </div>
            </div>
        </aside>
    </div>
</main>

<footer class="footer">
    <div class="footer-grid">
        <div><p class="footer-about">We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.</p></div>
        <div><h4>Quick Links</h4><ul class="footer-links-list">
            <li><a href="/index.php">Home</a></li>
            <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
            <li><a href="/study.php">Study / Past Questions</a></li>
            <li><a href="/novel.php">Novels</a></li>
            <li><a href="/scholarship.php">Scholarship</a></li>
            <li><a href="/download-app.php">Download Flexi</a></li>
            <li><a href="/cbt.html">CBT Simulator</a></li>
        </ul></div>
        <div><h4>Support &amp; Community</h4>
            <div class="contact-group"><a class="whatsapp-channel-link" href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" target="_blank" rel="noopener">Join our WhatsApp Channel</a></div>
            <div class="contact-group"><h5>Contact Us</h5><a href="tel:+2349034159839">(+234) 903 415 9839</a><a href="tel:+2347033855206">(+234) 703 385 5206</a></div>
            <div class="contact-group"><h5>Email Us</h5><a href="mailto:support@flexieduconsult.com.ng">support@flexieduconsult.com.ng</a><a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a></div>
        </div>
        <div><h4>Follow Us</h4><ul class="footer-links-list">
            <li><a href="https://www.facebook.com/profile.php?id=61589793118693" target="_blank" rel="noopener">Facebook @flexieduconsult</a></li>
            <li><a href="https://instagram.com/flexieduconsult2000" target="_blank" rel="noopener">Instagram @flexieduconsult2000</a></li>
            <li><a href="https://www.tiktok.com/@flexieduconsult" target="_blank" rel="noopener">TikTok @flexieduconsult</a></li>
        </ul></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo $currentYear; ?> Flexi Educational Consult. All Rights Reserved.</div>
</footer>

<div id="support-widget">
    <div id="support-tooltip">Chat with Jarvis AI for support</div>
    <button id="support-btn" onclick="window.location.href='/contactsupport.html'" aria-label="Support Chat"><svg width="29" height="29" viewBox="0 0 24 24" fill="white"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

const firebaseConfig={apiKey:"AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",authDomain:"auth.flexieduconsult.com.ng",projectId:"waec2026jamb2027"};
const app=initializeApp(firebaseConfig);const auth=getAuth(app);

// Keep the existing copy/cut/paste protection behavior.
document.addEventListener('copy',e=>e.preventDefault());
document.addEventListener('cut',e=>e.preventDefault());
document.addEventListener('paste',e=>e.preventDefault());

onAuthStateChanged(auth,user=>{
    const welcome=document.getElementById('welcome-banner'), greeting=document.getElementById('user-greeting');
    const authBtn=document.getElementById('auth-menu-btn'), desktopAuth=document.getElementById('desktop-auth-btn');
    const email=document.getElementById('contact-email');
    if(user){
        welcome.style.display='block';
        const name=user.displayName||user.email.split('@')[0];
        greeting.innerHTML=`Welcome back, <span style="color:var(--green)">${name}</span> 👋`;
        if(authBtn){authBtn.textContent='Logout';authBtn.style.color='#c62828';authBtn.onclick=async e=>{e.preventDefault();await signOut(auth);window.location.reload();};}
        if(desktopAuth){desktopAuth.textContent='Logout';desktopAuth.onclick=async e=>{e.preventDefault();await signOut(auth);window.location.reload();};}
        if(email) email.value=user.email;
    }else{
        welcome.style.display='none';
        if(authBtn){authBtn.textContent='Login';authBtn.style.color='';authBtn.onclick=()=>window.location.href='/login.html';}
        if(desktopAuth){desktopAuth.textContent='Login';desktopAuth.onclick=()=>window.location.href='/login.html';}
    }
});

const form=document.getElementById('contact-form'),submit=document.getElementById('submit-btn');
form.addEventListener('submit',async e=>{
    e.preventDefault();submit.disabled=true;submit.textContent='Sending...';
    try{
        const response=await fetch('https://formspree.io/f/xojywaeg',{method:'POST',body:new FormData(form),headers:{Accept:'application/json'}});
        if(!response.ok) throw new Error();
        Toastify({text:'Inquiry sent successfully!',duration:4000,gravity:'top',position:'right',style:{background:'#2E8B57'}}).showToast();form.reset();
    }catch(error){Toastify({text:'Failed to send. Try again.',duration:4000,gravity:'top',position:'right',style:{background:'#b22222'}}).showToast();}
    finally{submit.disabled=false;submit.textContent='Submit Inquiry';}
});

window.toggleMenu=()=>{
    const menu=document.getElementById('squareMenu'),btn=document.getElementById('menuBtn');
    const open=menu.classList.toggle('open');btn.setAttribute('aria-expanded',open?'true':'false');
};
window.toggleStudySubmenu=()=>{
    const sub=document.getElementById('studySubmenu'),toggle=document.querySelector('.mobile-study-toggle');
    const open=sub.classList.toggle('open');toggle.setAttribute('aria-expanded',open?'true':'false');document.getElementById('studyChevron').textContent=open?'⌃':'⌄';
};
document.addEventListener('click',e=>{
    const menu=document.getElementById('squareMenu'),btn=document.getElementById('menuBtn');
    if(menu.classList.contains('open')&&!menu.contains(e.target)&&!btn.contains(e.target)){menu.classList.remove('open');btn.setAttribute('aria-expanded','false');}
});

const slides=document.querySelectorAll('.slider-slide'),dots=document.querySelectorAll('.hero-slider .slider-dots .dot');let currentSlide=0;
function showSlide(index){slides.forEach(s=>s.classList.remove('active'));dots.forEach(d=>d.classList.remove('active-dot'));if(slides[index])slides[index].classList.add('active');if(dots[index])dots[index].classList.add('active-dot');}
if(slides.length>1)setInterval(()=>{currentSlide=(currentSlide+1)%slides.length;showSlide(currentSlide)},5000);

const quoteSlides=document.querySelectorAll('.quote-slider-container .quote-slide'),quoteDots=document.querySelectorAll('#quote-dots .dot');let currentQuoteIndex=0;
if(quoteSlides.length>1)setInterval(()=>{quoteSlides[currentQuoteIndex].classList.remove('active');if(quoteDots[currentQuoteIndex])quoteDots[currentQuoteIndex].classList.remove('active-dot');currentQuoteIndex=(currentQuoteIndex+1)%quoteSlides.length;quoteSlides[currentQuoteIndex].classList.add('active');if(quoteDots[currentQuoteIndex])quoteDots[currentQuoteIndex].classList.add('active-dot')},5000);

const tSlides=document.querySelectorAll('.testimonial-slide');let currentTSlide=0;
function showNextTestimonial(){if(tSlides.length<2)return;const old=tSlides[currentTSlide];old.classList.remove('active');old.classList.add('exit');currentTSlide=(currentTSlide+1)%tSlides.length;const next=tSlides[currentTSlide];next.classList.remove('exit');next.classList.add('active');setTimeout(()=>old.classList.remove('exit'),650)}
if(tSlides.length>1)setInterval(showNextTestimonial,6000);

window.addEventListener('load',()=>setTimeout(()=>{const tooltip=document.getElementById('support-tooltip');if(tooltip){tooltip.style.transition='opacity .5s ease';tooltip.style.opacity='0';setTimeout(()=>tooltip.style.display='none',500)}},5000));
</script>
</body>
</html>
