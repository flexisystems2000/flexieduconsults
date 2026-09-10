<?php

/*
|--------------------------------------------------------------------------
| Flexi Educational Consult
| News Article Viewer
|--------------------------------------------------------------------------
| Shared layout:
|   includes/head.php
|   includes/header.php
|   includes/footer.php
|
| Page-specific functionality:
|   - Firestore article retrieval
|   - Clean news URLs
|   - SEO metadata
|   - Article rendering
|   - HTML tables
|   - PDF attachments
|   - Comments
|   - Related news
|   - Sharing
|--------------------------------------------------------------------------
*/

$firebaseProjectId = "waec2026jamb2027";

/*
|--------------------------------------------------------------------------
| Get News Identifier
|--------------------------------------------------------------------------
*/

$newsId   = isset($_GET['id']) ? trim($_GET['id']) : '';
$newsSlug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

/*
|--------------------------------------------------------------------------
| Detect Clean URL
|--------------------------------------------------------------------------
|
| Supported:
|   /news/{slug}
|   /news/id/{documentId}
|
*/

$requestPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '',
    PHP_URL_PATH
) ?: '';

if (
    preg_match(
        '#^/news/([a-zA-Z0-9][a-zA-Z0-9_-]*)/?$#',
        $requestPath,
        $matches
    )
) {
    $newsSlug = $matches[1];

} elseif (
    preg_match(
        '#^/news/id/([a-zA-Z0-9_-]+)/?$#',
        $requestPath,
        $matches
    )
) {
    $newsId = $matches[1];
}

/*
|--------------------------------------------------------------------------
| Redirect Old Query URLs to Clean URLs
|--------------------------------------------------------------------------
|
| Example:
|   /news-view.php?id=ABC
|
| becomes:
|   /news/id/ABC
|
| Example:
|   /news-view.php?slug=my-news
|
| becomes:
|   /news/my-news
|
*/

$scriptName = basename(
    $_SERVER['SCRIPT_NAME'] ?? ''
);

if (
    $scriptName === 'news-view.php' &&
    strpos($requestPath, 'news-view.php') !== false &&
    ($newsSlug || $newsId)
) {
    $cleanUrl = $newsSlug
        ? '/news/' . rawurlencode($newsSlug)
        : '/news/id/' . rawurlencode($newsId);

    header(
        'Location: ' . $cleanUrl,
        true,
        301
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Default SEO Values
|--------------------------------------------------------------------------
*/

$pageTitle =
    'Flexi Tutors | News Update';

$pageDescription =
    'Read the latest educational news, JAMB updates, WAEC notices, admission guides and important educational updates on Flexi Educational Consult.';

$pageImage =
    'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

/*
|--------------------------------------------------------------------------
| Official Website URL
|--------------------------------------------------------------------------
*/

$siteUrl =
    'https://www.flexieduconsult.com.ng';

/*
|--------------------------------------------------------------------------
| Article URL
|--------------------------------------------------------------------------
*/

$pageUrl = $siteUrl . '/';

if ($newsSlug) {

    $pageUrl =
        $siteUrl .
        '/news/' .
        rawurlencode($newsSlug);

} elseif ($newsId) {

    $pageUrl =
        $siteUrl .
        '/news/id/' .
        rawurlencode($newsId);

}

/*
|--------------------------------------------------------------------------
| Article Data
|--------------------------------------------------------------------------
*/

$article = [
    'title'     => '',
    'content'   => '',
    'imageUrl'  => '',
    'tableData' => '',
    'pdfUrl'    => '',
    'timestamp' => null,
    'slug'      => $newsSlug,
    'id'        => $newsId,
];

/*
|--------------------------------------------------------------------------
| Retrieve Article from Firestore
|--------------------------------------------------------------------------
*/

if ($newsId || $newsSlug) {

    $apiUrl =
        "https://firestore.googleapis.com/v1/projects/" .
        $firebaseProjectId .
        "/databases/(default)/documents/news";

    $response = @file_get_contents($apiUrl);

    if ($response) {

        $data = json_decode(
            $response,
            true
        );

        if (
            isset($data['documents']) &&
            is_array($data['documents'])
        ) {

            foreach ($data['documents'] as $doc) {

                $docNameParts =
                    explode(
                        '/',
                        $doc['name'] ?? ''
                    );

                $docId =
                    end($docNameParts);

                $fields =
                    $doc['fields'] ?? [];

                $slug =
                    $fields['slug']['stringValue'] ?? '';

                /*
                |--------------------------------------------------------------------------
                | Match Article
                |--------------------------------------------------------------------------
                */

                $matchesId =
                    $newsId &&
                    $docId === $newsId;

                $matchesSlug =
                    $newsSlug &&
                    $slug === $newsSlug;

                if (
                    !$matchesId &&
                    !$matchesSlug
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SEO Information
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $fields['seoTitle']['stringValue']
                    )
                ) {

                    $pageTitle =
                        $fields['seoTitle']['stringValue'] .
                        ' | Flexi Educational Consult';

                } elseif (
                    !empty(
                        $fields['title']['stringValue']
                    )
                ) {

                    $pageTitle =
                        $fields['title']['stringValue'] .
                        ' | Flexi Educational Consult';
                }

                if (
                    !empty(
                        $fields['metaDescription']['stringValue']
                    )
                ) {

                    $pageDescription =
                        $fields['metaDescription']['stringValue'];

                } elseif (
                    !empty(
                        $fields['content']['stringValue']
                    )
                ) {

                    $plainDescription =
                        trim(
                            preg_replace(
                                '/\s+/',
                                ' ',
                                strip_tags(
                                    $fields['content']['stringValue']
                                )
                            )
                        );

                    $pageDescription =
                        mb_substr(
                            $plainDescription,
                            0,
                            155
                        );

                    if (
                        mb_strlen(
                            $plainDescription
                        ) > 155
                    ) {
                        $pageDescription .= '...';
                    }
                }

                if (
                    !empty(
                        $fields['imageUrl']['stringValue']
                    )
                ) {

                    $pageImage =
                        $fields['imageUrl']['stringValue'];
                }

                /*
                |--------------------------------------------------------------------------
                | Full Article Data
                |--------------------------------------------------------------------------
                */

                $article['title'] =
                    $fields['title']['stringValue']
                    ?? '';

                $article['content'] =
                    $fields['content']['stringValue']
                    ?? (
                        $fields['body']['stringValue']
                        ?? ''
                    );

                $article['imageUrl'] =
                    $fields['imageUrl']['stringValue']
                    ?? '';

                $article['tableData'] =
                    $fields['tableData']['stringValue']
                    ?? '';

                $article['pdfUrl'] =
                    $fields['pdfUrl']['stringValue']
                    ?? '';

                $article['id'] =
                    $docId;

                $article['slug'] =
                    $slug;

                /*
                |--------------------------------------------------------------------------
                | Timestamp
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $fields['timestamp']['timestampValue']
                    )
                ) {

                    $article['timestamp'] =
                        $fields['timestamp']['timestampValue'];
                }

                /*
                |--------------------------------------------------------------------------
                | Canonical URL
                |--------------------------------------------------------------------------
                */

                if ($slug) {

                    $pageUrl =
                        $siteUrl .
                        '/news/' .
                        rawurlencode($slug);

                } else {

                    $pageUrl =
                        $siteUrl .
                        '/news/id/' .
                        rawurlencode($docId);
                }

                break;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Safe HTML Escaping
|--------------------------------------------------------------------------
|
| Deliberately named uniquely so it does not conflict with other
| page-level helper functions.
|--------------------------------------------------------------------------
*/

if (!function_exists('flexiNewsEscape')) {

    function flexiNewsEscape($value)
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Shared Head Configuration
|--------------------------------------------------------------------------
*/

$includeToastify = true;
$includeAdsense = true;

/*
|--------------------------------------------------------------------------
| Load Shared Head
|--------------------------------------------------------------------------
*/

include __DIR__ . '/includes/head.php';

/*
|--------------------------------------------------------------------------
| NewsArticle Structured Data
|--------------------------------------------------------------------------
|
| JSON-LD can safely be rendered in the document body.
|--------------------------------------------------------------------------
*/

if (!empty($article['title'])) {

    $schemaDate =
        !empty($article['timestamp'])
            ? $article['timestamp']
            : date('c');

    $newsSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article['title'],
        'description' => $pageDescription,
        'image' => $pageImage,
        'datePublished' => $schemaDate,
        'dateModified' => $schemaDate,
        'author' => [
            '@type' => 'Organization',
            'name' => 'Flexi Educational Consult',
            'url' => $siteUrl
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Flexi Educational Consult',
            'logo' => [
                '@type' => 'ImageObject',
                'url' =>
                    'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg'
            ]
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $pageUrl
        ]
    ];

    echo '<script type="application/ld+json">' .
        json_encode(
            $newsSchema,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PRETTY_PRINT
        ) .
        '</script>';
}

/*
|--------------------------------------------------------------------------
| Shared Header
|--------------------------------------------------------------------------
*/

$currentPage = 'news-view.php';

include __DIR__ . '/includes/header.php';
?>

<main class="news-view-page">

    <div class="news-view-container">

        <?php if (empty($article['title'])): ?>

            <!-- Article Not Found -->

            <section
                id="loader"
                class="news-not-found"
                aria-labelledby="news-not-found-title"
            >

                <h1 id="news-not-found-title">
                    Article Not Found
                </h1>

                <p>
                    The news update you are looking for could not
                    be found or may no longer be available.
                </p>

                <a
                    href="/index.php"
                    class="news-back-link"
                >
                    ← Back to News Archive
                </a>

            </section>

        <?php else: ?>

            <article
                id="article-container"
                class="news-article"
            >

                <!-- Article Card -->

                <section class="content-card">

                    <?php if (!empty($article['imageUrl'])): ?>

                        <img
                            id="news-image"
                            class="main-img"
                            src="<?= flexiNewsEscape($article['imageUrl']); ?>"
                            alt="<?= flexiNewsEscape($article['title']); ?>"
                            loading="eager"
                        >

                    <?php endif; ?>

                    <div class="article-body">

                        <h1
                            id="news-title"
                            class="article-title"
                        >
                            <?= flexiNewsEscape($article['title']); ?>
                        </h1>

                        <?php if (!empty($article['timestamp'])): ?>

                            <?php
                            $articleTimestamp =
                                strtotime(
                                    $article['timestamp']
                                );
                            ?>

                            <?php if ($articleTimestamp !== false): ?>

                                <div
                                    id="news-date"
                                    class="article-date"
                                >
                                    Posted:
                                    <?= date(
                                        'D M j Y',
                                        $articleTimestamp
                                    ); ?>
                                </div>

                            <?php endif; ?>

                        <?php endif; ?>

                        <!--
                        |--------------------------------------------------------------------------
                        | Full Article Content
                        |--------------------------------------------------------------------------
                        |
                        | Content is escaped and converted from plain text
                        | to HTML line breaks. This preserves the existing
                        | security behaviour of the original page.
                        |--------------------------------------------------------------------------
                        -->

                        <div
                            id="news-content"
                            class="article-text"
                        >
                            <?= nl2br(
                                flexiNewsEscape(
                                    $article['content']
                                )
                            ); ?>
                        </div>

                        <!-- Share -->

                        <div class="share-box">

                            <span class="share-label">
                                Share update:
                            </span>

                            <button
                                type="button"
                                class="share-btn"
                                id="copy-link-btn"
                            >
                                Copy Link
                            </button>

                            <a
                                id="whatsapp-share"
                                href="https://api.whatsapp.com/send?text=<?= rawurlencode(
                                    $article['title'] .
                                    ' - ' .
                                    $pageUrl
                                ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="share-btn whatsapp-share-btn"
                            >
                                WhatsApp
                            </a>

                        </div>

                        <!-- Article Table -->

                        <?php if (!empty($article['tableData'])): ?>

                            <div
                                id="table-container"
                                class="data-table-container"
                            >

                                <?php
                                /*
                                 * tableData is intentionally rendered
                                 * as HTML because the F.E.C admin panel
                                 * stores the table builder output as HTML.
                                 */
                                echo $article['tableData'];
                                ?>

                            </div>

                        <?php endif; ?>

                        <!-- PDF Attachment -->

                        <?php if (!empty($article['pdfUrl'])): ?>

                            <section
                                id="pdf-container"
                                class="pdf-section"
                                aria-labelledby="attached-document-heading"
                            >

                                <div class="pdf-heading-row">

                                    <h2
                                        id="attached-document-heading"
                                        class="pdf-heading"
                                    >
                                        📎 Attached Document
                                    </h2>

                                    <a
                                        id="pdf-download-link"
                                        href="<?= flexiNewsEscape($article['pdfUrl']); ?>"
                                        class="download-btn"
                                        download
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z"/>
                                        </svg>

                                        Download Full PDF

                                    </a>

                                </div>

                                <?php

                                /*
                                |--------------------------------------------------------------------------
                                | Cloudinary PDF Preview
                                |--------------------------------------------------------------------------
                                */

                                $preview =
                                    $article['pdfUrl'];

                                if (
                                    strpos(
                                        $preview,
                                        'res.cloudinary.com'
                                    ) !== false
                                ) {

                                    $preview =
                                        str_replace(
                                            '/upload/',
                                            '/upload/w_800,c_limit,q_auto,f_jpg/pg_1/',
                                            $preview
                                        );

                                    $preview =
                                        preg_replace(
                                            '/\.pdf\b/i',
                                            '.jpg',
                                            $preview
                                        );
                                }

                                ?>

                                <div
                                    id="pdf-viewer-container"
                                    class="pdf-preview"
                                >

                                    <img
                                        src="<?= flexiNewsEscape($preview); ?>"
                                        alt="Preview of attached document"
                                        loading="lazy"
                                    >

                                </div>

                            </section>

                        <?php endif; ?>

                    </div>

                </section>

                <!-- Comments -->

                <section
                    class="comment-section"
                    aria-labelledby="discussion-heading"
                >

                    <h2
                        id="discussion-heading"
                        class="discussion-heading"
                    >
                        Discussion
                    </h2>

                    <div id="comments-list">

                        <p class="no-comments">
                            No comments yet. Be the first!
                        </p>

                    </div>

                    <div class="add-comment">

                        <h3>
                            Leave a Comment
                        </h3>

                        <label
                            for="comm-name"
                            class="comment-label"
                        >
                            Your Name
                        </label>

                        <input
                            type="text"
                            id="comm-name"
                            placeholder="Your Name"
                            autocomplete="name"
                            maxlength="100"
                            required
                        >

                        <label
                            for="comm-text"
                            class="comment-label"
                        >
                            Your Comment
                        </label>

                        <textarea
                            id="comm-text"
                            rows="3"
                            placeholder="Write your thoughts..."
                            maxlength="1000"
                            required
                        ></textarea>

                        <button
                            type="button"
                            class="comment-btn"
                            id="post-comm-btn"
                        >
                            Post Comment
                        </button>

                    </div>

                </section>

                <!-- Related News -->

                <section
                    class="other-news-section"
                    id="other-news-section"
                    style="display:none;"
                    aria-labelledby="other-news-heading"
                >

                    <h2 id="other-news-heading">
                        Other News
                    </h2>

                    <div
                        class="other-news-grid"
                        id="other-news-list"
                    ></div>

                </section>

            </article>

        <?php endif; ?>

    </div>

</main>

<?php

/*
|--------------------------------------------------------------------------
| Shared Footer
|--------------------------------------------------------------------------
*/

include __DIR__ . '/includes/footer.php';

?>

<style>

/*
|--------------------------------------------------------------------------
| NEWS VIEW — PAGE-SPECIFIC CSS ONLY
|--------------------------------------------------------------------------
|
| Shared branding/header/footer CSS is handled by:
|
|   /assets/css/flexi-brand.css
|
| Nothing below styles the shared header or footer.
|--------------------------------------------------------------------------
*/

.news-view-page {
    --news-blue: var(--flexi-primary, #003366);
    --news-green: var(--flexi-secondary, #2E8B57);
    --news-yellow: var(--flexi-accent, #FFD700);
    --news-bg: #f4f7f6;
    --news-text: #333;
    --news-muted: #888;
    --news-radius: 12px;
    --news-shadow: 0 4px 18px rgba(0, 0, 0, .06);

    background: var(--news-bg);
    min-height: 60vh;
    padding: 1px 0 48px;
}

.news-view-container {
    width: 94%;
    max-width: 1040px;
    margin: 24px auto 0;
}

/* Article */

.content-card {
    background: #fff;
    border-radius: var(--news-radius);
    overflow: hidden;
    box-shadow: var(--news-shadow);
    margin-bottom: 24px;
}

.main-img {
    width: 100%;
    max-height: 480px;
    object-fit: contain;
    display: block;
    background: #001f3f;
}

.article-body {
    padding: 28px 36px 36px;
}

.article-title {
    color: var(--news-blue);
    font-size: 1.85rem;
    line-height: 1.3;
    margin: 0 0 10px;
    font-weight: 700;
}

.article-date {
    color: var(--news-muted);
    font-size: 13px;
    margin-bottom: 18px;
    border-bottom: 1px solid #eee;
    padding-bottom: 12px;
}

.article-text {
    color: #333;
    line-height: 1.9;
    font-size: 17px;
    overflow-wrap: anywhere;
}

.article-text img {
    max-width: 100%;
    height: auto;
}

.article-text a {
    color: var(--news-green);
}

/* Share */

.share-box {
    margin-top: 28px;
    padding-top: 18px;
    border-top: 1px solid #eee;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.share-label {
    font-size: 13px;
    font-weight: 700;
    color: #555;
}

.share-btn {
    background: var(--news-green);
    color: #fff;
    border: none;
    padding: 9px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
}

.share-btn:hover {
    opacity: .92;
}

.whatsapp-share-btn {
    background: #25D366;
}

/* Article table */

.data-table-container {
    margin-top: 22px;
    overflow-x: auto;
    border-top: 1px solid #eee;
    padding-top: 16px;
    -webkit-overflow-scrolling: touch;
}

.data-table-container table {
    width: 100%;
    min-width: 500px;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table-container th,
.data-table-container td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.data-table-container th {
    background: var(--news-blue);
    color: #fff;
}

.data-table-container tr:nth-child(even) {
    background: #f9f9f9;
}

/*
|--------------------------------------------------------------------------
| PDF
|--------------------------------------------------------------------------
*/

.pdf-section {
    margin-top: 28px;
    padding-top: 22px;
    border-top: 2px dashed #eee;
}

.pdf-heading-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.pdf-heading {
    color: var(--news-blue);
    margin: 0;
    font-size: 1rem;
}

.pdf-preview {
    width: 100%;
    max-height: 550px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 12px;
    background: #f0f0f0;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.pdf-preview img {
    width: 100%;
    height: auto;
    display: block;
}

.download-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--news-green);
    color: #fff;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 700;
    font-size: 14px;
}

.download-btn:hover {
    opacity: .9;
}

.download-btn svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

/*
|--------------------------------------------------------------------------
| Comments
|--------------------------------------------------------------------------
*/

.comment-section {
    background: #fff;
    border-radius: var(--news-radius);
    padding: 28px 32px;
    box-shadow: var(--news-shadow);
    margin-bottom: 24px;
}

.discussion-heading {
    color: var(--news-blue);
    margin: 0 0 16px;
}

.comment-box {
    border-bottom: 1px solid #eee;
    padding: 12px 0;
}

.comment-name {
    font-weight: 700;
    color: var(--news-blue);
    font-size: 14px;
}

.comment-text {
    font-size: 14px;
    color: #555;
    margin: 4px 0;
    overflow-wrap: anywhere;
}

.comment-date {
    font-size: 11px;
    color: #aaa;
}

.no-comments {
    color: #999;
    font-size: 14px;
}

.add-comment {
    margin-top: 20px;
}

.add-comment h3 {
    margin: 0 0 10px;
    color: var(--news-blue);
}

.comment-label {
    display: block;
    margin: 8px 0 5px;
    font-size: 13px;
    font-weight: 600;
    color: #444;
}

.news-view-page input,
.news-view-page textarea {
    width: 100%;
    padding: 11px 12px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: inherit;
    font-size: 14px;
    background: #fff;
    color: #333;
    -webkit-user-select: text;
    user-select: text;
}

.news-view-page input:focus,
.news-view-page textarea:focus {
    outline: none;
    border-color: var(--news-green);
    box-shadow: 0 0 0 3px rgba(46, 139, 87, .1);
}

.news-view-page textarea {
    resize: vertical;
}

.comment-btn {
    background: var(--news-blue);
    color: #fff;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    font-weight: 700;
    cursor: pointer;
    font-size: 14px;
}

.comment-btn:hover {
    opacity: .92;
}

.comment-btn:disabled {
    opacity: .55;
    cursor: not-allowed;
}

/*
|--------------------------------------------------------------------------
| Article Not Found
|--------------------------------------------------------------------------
*/

.news-not-found {
    text-align: left;
    background: #fff;
    padding: 32px;
    border-radius: var(--news-radius);
    box-shadow: var(--news-shadow);
    color: var(--news-blue);
}

.news-not-found h1 {
    margin-top: 0;
    color: var(--news-blue);
}

.news-not-found p {
    color: #555;
    line-height: 1.6;
}

.news-back-link {
    display: inline-flex;
    align-items: center;
    min-height: 42px;
    padding: 9px 15px;
    border-radius: 7px;
    background: var(--news-green);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
}

.news-back-link:hover {
    opacity: .9;
}

/*
|--------------------------------------------------------------------------
| Other News
|--------------------------------------------------------------------------
*/

.other-news-section {
    background: #fff;
    border-radius: var(--news-radius);
    padding: 28px 32px;
    box-shadow: var(--news-shadow);
    margin-bottom: 24px;
}

.other-news-section h2 {
    color: var(--news-blue);
    margin: 0 0 18px;
    font-size: 1.2rem;
    border-left: 4px solid var(--news-green);
    padding-left: 12px;
}

.other-news-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.other-news-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    border: 1px solid #eee;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    transition:
        background .2s,
        box-shadow .2s,
        transform .15s;
}

.other-news-item:hover {
    background: #f8faf9;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
    transform: translateY(-1px);
}

.other-news-item img {
    width: 88px;
    height: 66px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    flex-shrink: 0;
    background: #001f3f;
}

.other-news-item .on-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--news-blue);
    line-height: 1.4;
    flex: 1;
    overflow-wrap: anywhere;
}

.other-news-item .on-arrow {
    color: #aaa;
    font-size: 16px;
    flex-shrink: 0;
}

/*
|--------------------------------------------------------------------------
| Mobile
|--------------------------------------------------------------------------
*/

@media (max-width: 767px) {

    .news-view-container {
        width: calc(100% - 20px);
        margin-top: 14px;
    }

    .article-body {
        padding: 18px 16px 22px;
    }

    .article-title {
        font-size: 1.35rem;
    }

    .article-text {
        font-size: 15px;
        line-height: 1.8;
    }

    .comment-section,
    .other-news-section {
        padding: 18px 16px;
    }

    .other-news-grid {
        grid-template-columns: 1fr;
    }

    .other-news-item img {
        width: 72px;
        height: 54px;
    }

    .pdf-heading-row {
        align-items: stretch;
        flex-direction: column;
    }

    .download-btn {
        width: 100%;
    }

    .share-box {
        align-items: stretch;
        flex-direction: column;
    }

    .share-label {
        width: 100%;
    }

    .share-btn {
        width: 100%;
    }

    .main-img {
        max-height: 320px;
    }
}

</style>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script type="module">

/*
|--------------------------------------------------------------------------
| Firebase
|--------------------------------------------------------------------------
*/

import {
    initializeApp
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";

import {
    getFirestore,
    collection,
    addDoc,
    getDocs,
    query,
    orderBy,
    serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

/*
|--------------------------------------------------------------------------
| Firebase Configuration
|--------------------------------------------------------------------------
*/

const firebaseConfig = {

    apiKey:
        "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",

    authDomain:
        "auth.flexieduconsult.com.ng",

    projectId:
        "waec2026jamb2027"
};

const app =
    initializeApp(firebaseConfig);

const db =
    getFirestore(app);

/*
|--------------------------------------------------------------------------
| Current Article ID
|--------------------------------------------------------------------------
*/

const currentArticleId =
    <?= json_encode(
        $article['id'] ?? '',
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
    ); ?>;

/*
|--------------------------------------------------------------------------
| HTML Escaping for Dynamic Firestore Content
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {

    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/*
|--------------------------------------------------------------------------
| Copy / Share Article
|--------------------------------------------------------------------------
*/

window.shareArticle = async function () {

    const titleElement =
        document.getElementById('news-title');

    const title =
        titleElement
            ? titleElement.innerText
            : 'Flexi Educational Consult News';

    try {

        if (
            navigator.share &&
            typeof navigator.share === 'function'
        ) {

            await navigator.share({
                title: title,
                url: window.location.href
            });

            return;
        }

        if (
            navigator.clipboard &&
            navigator.clipboard.writeText
        ) {

            await navigator.clipboard.writeText(
                window.location.href
            );

            showToast(
                'Link copied to clipboard!'
            );

            return;
        }

        fallbackCopyLink();

    } catch (error) {

        /*
         * Sharing cancelled by the user should not
         * produce an error notification.
         */

        if (
            error &&
            error.name === 'AbortError'
        ) {
            return;
        }

        console.error(
            'Share error:',
            error
        );

        fallbackCopyLink();
    }
};

/*
|--------------------------------------------------------------------------
| Fallback Copy
|--------------------------------------------------------------------------
*/

function fallbackCopyLink() {

    const temporaryInput =
        document.createElement('input');

    temporaryInput.value =
        window.location.href;

    temporaryInput.style.position =
        'fixed';

    temporaryInput.style.opacity =
        '0';

    document.body.appendChild(
        temporaryInput
    );

    temporaryInput.select();

    try {

        document.execCommand(
            'copy'
        );

        showToast(
            'Link copied to clipboard!'
        );

    } catch (error) {

        console.error(
            'Copy failed:',
            error
        );

        showToast(
            'Unable to copy link. Please copy the URL manually.'
        );
    }

    document.body.removeChild(
        temporaryInput
    );
}

/*
|--------------------------------------------------------------------------
| Toast Helper
|--------------------------------------------------------------------------
*/

function showToast(message) {

    if (typeof Toastify === 'function') {

        Toastify({
            text: message,
            duration: 3000,
            gravity: 'top',
            position: 'right',
            style: {
                background: '#2E8B57'
            }
        }).showToast();

    } else {

        alert(message);
    }
}

/*
|--------------------------------------------------------------------------
| Copy Link Button
|--------------------------------------------------------------------------
*/

const copyLinkButton =
    document.getElementById(
        'copy-link-btn'
    );

if (copyLinkButton) {

    copyLinkButton.addEventListener(
        'click',
        function () {

            if (
                navigator.share &&
                typeof navigator.share === 'function'
            ) {

                window.shareArticle();

            } else {

                fallbackCopyLink();
            }
        }
    );
}

/*
|--------------------------------------------------------------------------
| Load Comments
|--------------------------------------------------------------------------
*/

async function loadComments() {

    if (!currentArticleId) {
        return;
    }

    const commentsList =
        document.getElementById(
            'comments-list'
        );

    if (!commentsList) {
        return;
    }

    try {

        const commentsQuery =
            query(
                collection(
                    db,
                    'news',
                    currentArticleId,
                    'comments'
                ),
                orderBy(
                    'timestamp',
                    'desc'
                )
            );

        const snapshot =
            await getDocs(
                commentsQuery
            );

        if (snapshot.empty) {

            commentsList.innerHTML = `
                <p class="no-comments">
                    No comments yet. Be the first!
                </p>
            `;

            return;
        }

        commentsList.innerHTML = '';

        snapshot.forEach(function (doc) {

            const comment =
                doc.data();

            let dateText =
                'Just now';

            if (
                comment.timestamp &&
                typeof comment.timestamp.toDate ===
                    'function'
            ) {

                try {

                    dateText =
                        comment.timestamp
                            .toDate()
                            .toLocaleString();

                } catch (error) {

                    console.warn(
                        'Comment date error:',
                        error
                    );
                }
            }

            const commentElement =
                document.createElement('div');

            commentElement.className =
                'comment-box';

            commentElement.innerHTML = `
                <div class="comment-name">
                    ${escapeHtml(comment.name || 'Anonymous')}
                </div>

                <div class="comment-text">
                    ${escapeHtml(comment.text || '')}
                </div>

                <div class="comment-date">
                    ${escapeHtml(dateText)}
                </div>
            `;

            commentsList.appendChild(
                commentElement
            );
        });

    } catch (error) {

        console.error(
            'Error loading comments:',
            error
        );
    }
}

/*
|--------------------------------------------------------------------------
| Load Other News
|--------------------------------------------------------------------------
*/

async function loadOtherNews() {

    const section =
        document.getElementById(
            'other-news-section'
        );

    const list =
        document.getElementById(
            'other-news-list'
        );

    if (!section || !list) {
        return;
    }

    try {

        const snapshot =
            await getDocs(
                collection(
                    db,
                    'news'
                )
            );

        if (snapshot.empty) {
            return;
        }

        const items = [];

        snapshot.forEach(function (doc) {

            if (
                doc.id ===
                currentArticleId
            ) {
                return;
            }

            const data =
                doc.data();

            let timestamp = 0;

            if (
                data.timestamp &&
                typeof data.timestamp.toDate ===
                    'function'
            ) {

                try {

                    timestamp =
                        data.timestamp
                            .toDate()
                            .getTime();

                } catch (error) {

                    timestamp = 0;
                }
            }

            items.push({

                id:
                    doc.id,

                title:
                    data.title ||
                    'News Update',

                imageUrl:
                    data.imageUrl ||
                    'https://via.placeholder.com/88x66',

                slug:
                    data.slug ||
                    '',

                timestamp:
                    timestamp
            });
        });

        items.sort(
            function (a, b) {
                return b.timestamp -
                    a.timestamp;
            }
        );

        const topItems =
            items.slice(0, 6);

        if (!topItems.length) {
            return;
        }

        list.innerHTML =
            topItems
                .map(function (item) {

                    const href =
                        item.slug
                            ? '/news/' +
                              encodeURIComponent(
                                  item.slug
                              )
                            : '/news/id/' +
                              encodeURIComponent(
                                  item.id
                              );

                    return `
                        <a
                            class="other-news-item"
                            href="${escapeHtml(href)}"
                        >

                            <img
                                src="${escapeHtml(item.imageUrl)}"
                                alt="${escapeHtml(item.title)}"
                                loading="lazy"
                            >

                            <span class="on-title">
                                ${escapeHtml(item.title)}
                            </span>

                            <span
                                class="on-arrow"
                                aria-hidden="true"
                            >
                                ❯
                            </span>

                        </a>
                    `;
                })
                .join('');

        section.style.display =
            'block';

    } catch (error) {

        console.error(
            'Error loading other news:',
            error
        );
    }
}

/*
|--------------------------------------------------------------------------
| Post Comment
|--------------------------------------------------------------------------
*/

window.postComment = async function () {

    const nameInput =
        document.getElementById(
            'comm-name'
        );

    const textInput =
        document.getElementById(
            'comm-text'
        );

    const button =
        document.getElementById(
            'post-comm-btn'
        );

    if (
        !nameInput ||
        !textInput ||
        !button
    ) {
        return;
    }

    const name =
        nameInput.value.trim();

    const text =
        textInput.value.trim();

    if (!currentArticleId) {

        showToast(
            'This article cannot receive comments right now.'
        );

        return;
    }

    if (!name || !text) {

        showToast(
            'Please fill in both your name and comment.'
        );

        return;
    }

    button.disabled = true;

    button.innerText =
        'Posting...';

    try {

        await addDoc(
            collection(
                db,
                'news',
                currentArticleId,
                'comments'
            ),
            {
                name: name,
                text: text,
                timestamp:
                    serverTimestamp()
            }
        );

        showToast(
            'Comment posted!'
        );

        textInput.value = '';

        await loadComments();

    } catch (error) {

        console.error(
            'Error posting comment:',
            error
        );

        showToast(
            'Error posting comment. Please try again.'
        );

    } finally {

        button.disabled =
            false;

        button.innerText =
            'Post Comment';
    }
};

/*
|--------------------------------------------------------------------------
| Initialise Interactive Features
|--------------------------------------------------------------------------
*/

if (currentArticleId) {

    loadComments();

    loadOtherNews();
}

</script>
