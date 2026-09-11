<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// NEWS ARTICLE VIEWER
//
// Supports:
//   **Bold text**
//   *Italic text*
//   ✳️ Sub-header
//   # Main heading
//   ## Sub-heading
//   ### Small heading
//   > Important notice
//   • Bullet points
//   - Bullet points
//   1. Numbered lists
//   --- Divider
//   [[TABLE_1]]
//   [[TABLE_2]]
//   etc.
//
// Legacy articles with the old tableData format are also supported.
// ============================================================


$firebaseProjectId = "waec2026jamb2027";


// ============================================================
// GET ARTICLE ID / SLUG
// ============================================================

$newsId   = isset($_GET['id'])   ? trim($_GET['id'])   : '';
$newsSlug = isset($_GET['slug']) ? trim($_GET['slug']) : '';


// ============================================================
// SUPPORT CLEAN PATH URLS
//
// /news/{slug}
// /news/id/{docId}
// ============================================================

$requestPath =
    parse_url(
        $_SERVER['REQUEST_URI'] ?? '',
        PHP_URL_PATH
    ) ?: '';


if (
    preg_match(
        '#^/news/([a-zA-Z0-9][a-zA-Z0-9_-]*)/?$#',
        $requestPath,
        $m
    )
) {

    $newsSlug = $m[1];

} elseif (
    preg_match(
        '#^/news/id/([a-zA-Z0-9_-]+)/?$#',
        $requestPath,
        $m
    )
) {

    $newsId = $m[1];

}


// ============================================================
// REDIRECT OLD QUERY-STYLE URLS
// ============================================================

$script =
    basename(
        $_SERVER['SCRIPT_NAME'] ?? ''
    );


if (
    $script === 'news-view.php' &&
    strpos(
        $requestPath,
        'news-view.php'
    ) !== false &&
    ($newsSlug || $newsId)
) {

    $clean = $newsSlug
        ? '/news/' . rawurlencode($newsSlug)
        : '/news/id/' . rawurlencode($newsId);

    header(
        'Location: ' . $clean,
        true,
        301
    );

    exit;
}


// ============================================================
// DEFAULT SEO VALUES
// ============================================================

$pageTitle =
    "Flexi Tutors | News Update";

$pageDesc =
    "Read the latest educational news, JAMB updates, WAEC notices, and admission guides on Flexi Educational Consult.";

$pageImage =
    "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg";


$host =
    $_SERVER['HTTP_HOST']
    ?? 'flexieduconsult.com.ng';

$scheme = 'https';

$pageUrl =
    $scheme .
    '://' .
    $host .
    ($_SERVER['REQUEST_URI'] ?? '/');


// ============================================================
// ARTICLE DATA
// ============================================================

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


// ============================================================
// FETCH FIRESTORE NEWS
// ============================================================

if ($newsId || $newsSlug) {

    $apiUrl =
        "https://firestore.googleapis.com/v1/projects/" .
        $firebaseProjectId .
        "/databases/(default)/documents/news";


    $response =
        @file_get_contents(
            $apiUrl
        );


    if ($response) {

        $data =
            json_decode(
                $response,
                true
            );


        if (
            isset(
                $data['documents']
            )
        ) {

            foreach (
                $data['documents']
                as $doc
            ) {

                $docNameParts =
                    explode(
                        '/',
                        $doc['name']
                    );


                $docId =
                    end(
                        $docNameParts
                    );


                $fields =
                    $doc['fields']
                    ?? [];


                $slug =
                    $fields['slug']['stringValue']
                    ?? '';


                if (
                    (
                        $newsId &&
                        $docId === $newsId
                    )
                    ||
                    (
                        $newsSlug &&
                        $slug === $newsSlug
                    )
                ) {

                    // ------------------------------------------------
                    // SEO TITLE
                    // ------------------------------------------------

                    if (
                        !empty(
                            $fields['seoTitle']['stringValue']
                        )
                    ) {

                        $pageTitle =
                            $fields['seoTitle']['stringValue']
                            . " | Flexi Educational Consult";

                    } elseif (
                        !empty(
                            $fields['title']['stringValue']
                        )
                    ) {

                        $pageTitle =
                            $fields['title']['stringValue']
                            . " | Flexi Educational Consult";

                    }


                    // ------------------------------------------------
                    // META DESCRIPTION
                    // ------------------------------------------------

                    if (
                        !empty(
                            $fields['metaDescription']['stringValue']
                        )
                    ) {

                        $pageDesc =
                            $fields['metaDescription']['stringValue'];

                    } elseif (
                        !empty(
                            $fields['content']['stringValue']
                        )
                    ) {

                        $cleanDescription =
                            preg_replace(
                                '/\[\[TABLE_\d+\]\]/',
                                '',
                                $fields['content']['stringValue']
                            );


                        $cleanDescription =
                            preg_replace(
                                '/[*✳️#>]/u',
                                '',
                                $cleanDescription
                            );


                        $pageDesc =
                            mb_substr(
                                trim(
                                    strip_tags(
                                        $cleanDescription
                                    )
                                ),
                                0,
                                155
                            )
                            . "...";

                    }


                    // ------------------------------------------------
                    // IMAGE
                    // ------------------------------------------------

                    if (
                        !empty(
                            $fields['imageUrl']['stringValue']
                        )
                    ) {

                        $pageImage =
                            $fields['imageUrl']['stringValue'];

                    }


                    // ------------------------------------------------
                    // FULL ARTICLE DATA
                    // ------------------------------------------------

                    $article['title'] =
                        $fields['title']['stringValue']
                        ?? '';


                    $article['content'] =
                        $fields['content']['stringValue']
                        ??
                        (
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


                    // ------------------------------------------------
                    // TIMESTAMP
                    // ------------------------------------------------

                    if (
                        !empty(
                            $fields['timestamp']['timestampValue']
                        )
                    ) {

                        $article['timestamp'] =
                            $fields['timestamp']['timestampValue'];

                    }


                    // ------------------------------------------------
                    // CANONICAL URL
                    // ------------------------------------------------

                    if ($slug) {

                        $pageUrl =
                            $scheme .
                            '://' .
                            $host .
                            '/news/' .
                            rawurlencode(
                                $slug
                            );

                    } else {

                        $pageUrl =
                            $scheme .
                            '://' .
                            $host .
                            '/news/id/' .
                            rawurlencode(
                                $docId
                            );

                    }


                    break;

                }

            }

        }

    }

}


// ============================================================
// BASIC HTML ESCAPE
// ============================================================

function h($str) {

    return htmlspecialchars(
        (string)$str,
        ENT_QUOTES,
        'UTF-8'
    );

}


// ============================================================
// INLINE FORMATTER
//
// IMPORTANT:
// Raw article HTML is NEVER allowed.
//
// Text is escaped first.
// Then only our supported formatting
// syntax is converted into safe HTML.
// ============================================================

function flexi_inline_format($text) {

    $text =
        htmlspecialchars(
            (string)$text,
            ENT_QUOTES,
            'UTF-8'
        );


    // ----------------------------------------------------------
    // Inline code
    // ----------------------------------------------------------

    $text =
        preg_replace(
            '/`([^`]+)`/',
            '<code>$1</code>',
            $text
        );


    // ----------------------------------------------------------
    // Bold
    //
    // **text**
    // ----------------------------------------------------------

    $text =
        preg_replace(
            '/\*\*(.+?)\*\*/s',
            '<strong>$1</strong>',
            $text
        );


    // ----------------------------------------------------------
    // Italic
    //
    // *text*
    // ----------------------------------------------------------

    $text =
        preg_replace(
            '/(^|[^\*])\*([^*\n]+)\*(?!\*)/',
            '$1<em>$2</em>',
            $text
        );


    return $text;

}


// ============================================================
// EXTRACT NEW TABLES
//
// New admin format:
//
// <!--FLEXI_TABLE:TABLE_1-->
// <table>...</table>
// <!--END_FLEXI_TABLE-->
//
// <!--FLEXI_TABLE:TABLE_2-->
// ...
// ============================================================

function flexi_extract_tables($tableData) {

    $tables = [];


    if (
        empty($tableData) ||
        !is_string($tableData)
    ) {

        return $tables;

    }


    preg_match_all(

        '/<!--FLEXI_TABLE:(TABLE_\d+)-->([\s\S]*?)<!--END_FLEXI_TABLE-->/',

        $tableData,

        $matches,
        PREG_SET_ORDER

    );


    foreach (
        $matches
        as $match
    ) {

        $tableId =
            $match[1];


        $tableHtml =
            $match[2];


        /*
         * The table HTML is generated
         * by the admin table builder.
         *
         * We still strip dangerous script/
         * iframe/object tags if somebody somehow
         * manages to put them into tableData.
         */

        $tableHtml =
            preg_replace(
                '/<\s*(script|iframe|object|embed|form)[^>]*>[\s\S]*?<\s*\/\s*\1\s*>/i',
                '',
                $tableHtml
            );


        $tables[$tableId] =
            $tableHtml;

    }


    return $tables;

}


// ============================================================
// RENDER ARTICLE CONTENT
//
// Supports:
//
// ✳️ Header
// # Header
// ## Header
// ### Header
// > Notice
// • bullet
// - bullet
// 1. numbered
// ---
// [[TABLE_1]]
// [[TABLE_2]]
// **bold**
// *italic*
// `code`
// ============================================================

function flexi_render_article_content(
    $content,
    $tableData = ''
) {

    if (
        trim(
            (string)$content
        ) === ''
    ) {

        return '';

    }


    $tables =
        flexi_extract_tables(
            $tableData
        );


    /*
     * Determine whether this is a new-format
     * article containing table markers.
     */

    $hasTableMarkers =
        preg_match(
            '/\[\[TABLE_\d+\]\]/',
            $content
        );


    $lines =
        preg_split(
            "/\r\n|\r|\n/",
            $content
        );


    $html = '';

    $inUL = false;
    $inOL = false;


    // ----------------------------------------------------------
    // CLOSE LISTS
    // ----------------------------------------------------------

    $closeLists =
        function() use (
            &$html,
            &$inUL,
            &$inOL
        ) {

            if ($inUL) {

                $html .= '</ul>';

                $inUL = false;

            }


            if ($inOL) {

                $html .= '</ol>';

                $inOL = false;

            }

        };


    // ----------------------------------------------------------
    // PROCESS EACH LINE
    // ----------------------------------------------------------

    foreach (
        $lines
        as $line
    ) {

        $rawLine =
            trim(
                $line
            );


        // ------------------------------------------------------
        // EMPTY LINE
        // ------------------------------------------------------

        if (
            $rawLine === ''
        ) {

            $closeLists();

            continue;

        }


        // ------------------------------------------------------
        // TABLE MARKER
        //
        // [[TABLE_1]]
        // ------------------------------------------------------

        if (
            preg_match(
                '/^\[\[(TABLE_\d+)\]\]$/',
                $rawLine,
                $tableMatch
            )
        ) {

            $closeLists();


            $tableId =
                $tableMatch[1];


            if (
                isset(
                    $tables[$tableId]
                )
            ) {

                $html .=
                    '<div class="article-table">' .
                    $tables[$tableId] .
                    '</div>';

            } else {

                /*
                 * Don't expose broken marker
                 * prominently to readers.
                 */

                $html .=
                    '<div class="missing-table">' .
                    'Table unavailable' .
                    '</div>';

            }


            continue;

        }


        // ------------------------------------------------------
        // DIVIDER
        //
        // ---
        // ***
        // ------------------------------------------------------

        if (
            $rawLine === '---' ||
            $rawLine === '***'
        ) {

            $closeLists();

            $html .= '<hr>';

            continue;

        }


        // ------------------------------------------------------
        // ✳️ SUB HEADER
        // ------------------------------------------------------

        if (
            mb_substr(
                $rawLine,
                0,
                3
            ) === '✳️ '
        ) {

            $closeLists();


            $heading =
                mb_substr(
                    $rawLine,
                    3
                );


            $html .=
                '<h2>' .
                flexi_inline_format(
                    $heading
                ) .
                '</h2>';


            continue;

        }


        // ------------------------------------------------------
        // ### SMALL HEADING
        //
        // Must be checked before ##
        // and #
        // ------------------------------------------------------

        if (
            substr(
                $rawLine,
                0,
                4
            ) === '### '
        ) {

            $closeLists();


            $heading =
                substr(
                    $rawLine,
                    4
                );


            $html .=
                '<h4>' .
                flexi_inline_format(
                    $heading
                ) .
                '</h4>';


            continue;

        }


        // ------------------------------------------------------
        // ## SUB HEADING
        // ------------------------------------------------------

        if (
            substr(
                $rawLine,
                0,
                3
            ) === '## '
        ) {

            $closeLists();


            $heading =
                substr(
                    $rawLine,
                    3
                );


            $html .=
                '<h3>' .
                flexi_inline_format(
                    $heading
                ) .
                '</h3>';


            continue;

        }


        // ------------------------------------------------------
        // # MAIN ARTICLE HEADING
        // ------------------------------------------------------

        if (
            substr(
                $rawLine,
                0,
                2
            ) === '# '
        ) {

            $closeLists();


            $heading =
                substr(
                    $rawLine,
                    2
                );


            $html .=
                '<h2>' .
                flexi_inline_format(
                    $heading
                ) .
                '</h2>';


            continue;

        }


        // ------------------------------------------------------
        // BLOCKQUOTE
        //
        // > Notice
        // ------------------------------------------------------

        if (
            substr(
                $rawLine,
                0,
                2
            ) === '> '
        ) {

            $closeLists();


            $notice =
                substr(
                    $rawLine,
                    2
                );


            $html .=
                '<blockquote>' .
                flexi_inline_format(
                    $notice
                ) .
                '</blockquote>';


            continue;

        }


        // ------------------------------------------------------
        // BULLET
        //
        // • Item
        // ------------------------------------------------------

        if (
            mb_substr(
                $rawLine,
                0,
                2
            ) === '• '
        ) {

            if (!$inUL) {

                if ($inOL) {

                    $html .= '</ol>';

                    $inOL = false;

                }

                $html .= '<ul>';

                $inUL = true;

            }


            $item =
                mb_substr(
                    $rawLine,
                    2
                );


            $html .=
                '<li>' .
                flexi_inline_format(
                    $item
                ) .
                '</li>';


            continue;

        }


        // ------------------------------------------------------
        // DASH BULLET
        //
        // - Item
        // ------------------------------------------------------

        if (
            substr(
                $rawLine,
                0,
                2
            ) === '- '
        ) {

            if (!$inUL) {

                if ($inOL) {

                    $html .= '</ol>';

                    $inOL = false;

                }

                $html .= '<ul>';

                $inUL = true;

            }


            $item =
                substr(
                    $rawLine,
                    2
                );


            $html .=
                '<li>' .
                flexi_inline_format(
                    $item
                ) .
                '</li>';


            continue;

        }


        // ------------------------------------------------------
        // NUMBERED LIST
        //
        // 1. Item
        // 2. Item
        // ------------------------------------------------------

        if (
            preg_match(
                '/^\d+\.\s+(.+)$/',
                $rawLine,
                $numberMatch
            )
        ) {

            if (!$inOL) {

                if ($inUL) {

                    $html .= '</ul>';

                    $inUL = false;

                }

                $html .= '<ol>';

                $inOL = true;

            }


            $item =
                $numberMatch[1];


            $html .=
                '<li>' .
                flexi_inline_format(
                    $item
                ) .
                '</li>';


            continue;

        }


        // ------------------------------------------------------
        // NORMAL PARAGRAPH
        // ------------------------------------------------------

        $closeLists();


        $html .=
            '<p>' .
            flexi_inline_format(
                $rawLine
            ) .
            '</p>';

    }


    // ----------------------------------------------------------
    // CLOSE ANY REMAINING LIST
    // ----------------------------------------------------------

    $closeLists();


    // ==========================================================
    // LEGACY TABLE SUPPORT
    //
    // Older articles used:
    //
    // content = article
    // tableData = table
    //
    // If no [[TABLE_X]] marker exists,
    // keep the old table at the bottom.
    // ==========================================================

    if (
        !$hasTableMarkers &&
        !empty($tableData)
    ) {

        /*
         * If tableData is the old plain HTML
         * format, render it as-is.
         *
         * If it is the new marker format,
         * render all extracted tables.
         */

        if (
            !empty($tables)
        ) {

            foreach (
                $tables
                as $legacyTable
            ) {

                $html .=
                    '<div class="article-table legacy-table">' .
                    $legacyTable .
                    '</div>';

            }

        } else {

            /*
             * Remove dangerous container tags
             * while preserving the existing
             * table HTML.
             */

            $legacyTable =
                preg_replace(
                    '/<\s*(script|iframe|object|embed|form)[^>]*>[\s\S]*?<\s*\/\s*\1\s*>/i',
                    '',
                    $tableData
                );


            $html .=
                '<div class="article-table legacy-table">' .
                $legacyTable .
                '</div>';

        }

    }


    return $html;

}


// ============================================================
// RENDER ARTICLE BODY
// ============================================================

$renderedArticleContent =
    flexi_render_article_content(
        $article['content'],
        $article['tableData']
    );


// ============================================================
// PDF PREVIEW
// ============================================================

$pdfPreview = '';

if (
    !empty(
        $article['pdfUrl']
    )
) {

    $pdfPreview =
        $article['pdfUrl'];


    if (
        strpos(
            $pdfPreview,
            'res.cloudinary.com'
        ) !== false
    ) {

        $pdfPreview =
            str_replace(
                '/upload/',
                '/upload/w_800,c_limit,q_auto,f_jpg/pg_1/',
                $pdfPreview
            );


        $pdfPreview =
            preg_replace(
                '/\.pdf\b/i',
                '.jpg',
                $pdfPreview
            );

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">


    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180"
        crossorigin="anonymous">
    </script>


    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
    >

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
    >


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title id="page-title">
        <?php echo h($pageTitle); ?>
    </title>


    <meta
        name="description"
        content="<?php echo h($pageDesc); ?>"
    >


    <link
        rel="canonical"
        href="<?php echo h($pageUrl); ?>"
    >


    <!-- ======================================================
         OPEN GRAPH
    ======================================================= -->

    <meta
        property="og:title"
        content="<?php echo h($pageTitle); ?>"
    >

    <meta
        property="og:description"
        content="<?php echo h($pageDesc); ?>"
    >

    <meta
        property="og:image"
        content="<?php echo h($pageImage); ?>"
    >

    <meta
        property="og:url"
        content="<?php echo h($pageUrl); ?>"
    >

    <meta
        property="og:type"
        content="article"
    >


    <!-- ======================================================
         TWITTER
    ======================================================= -->

    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="<?php echo h($pageTitle); ?>"
    >

    <meta
        name="twitter:description"
        content="<?php echo h($pageDesc); ?>"
    >

    <meta
        name="twitter:image"
        content="<?php echo h($pageImage); ?>"
    >


    <!-- ======================================================
         STRUCTURED DATA
    ======================================================= -->

    <?php if (!empty($article['title'])): ?>

    <script type="application/ld+json">

    <?php

    echo json_encode(
        [
            "@context" => "https://schema.org",

            "@type" => "NewsArticle",

            "headline" =>
                $article['title'],

            "description" =>
                $pageDesc,

            "image" =>
                $pageImage,

            "datePublished" =>
                $article['timestamp']
                ?? date('c'),

            "author" => [
                "@type" =>
                    "Organization",

                "name" =>
                    "Flexi Educational Consult"
            ],

            "publisher" => [

                "@type" =>
                    "Organization",

                "name" =>
                    "Flexi Educational Consult",

                "logo" => [

                    "@type" =>
                        "ImageObject",

                    "url" =>
                        "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"

                ]

            ],

            "mainEntityOfPage" =>
                $pageUrl

        ],

        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT

    );

    ?>

    </script>

    <?php endif; ?>


    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
    >


    <style>

        :root {

            --blue: #003366;
            --green: #2E8B57;
            --yellow: #FFD700;

            --bg: #f4f7f6;
            --text: #333;
            --muted: #888;

            --radius: 12px;

            --shadow:
                0 4px 18px rgba(0,0,0,0.06);

        }


        * {
            box-sizing: border-box;
        }


        body {

            font-family:
                'Segoe UI',
                system-ui,
                -apple-system,
                sans-serif;

            background:
                var(--bg);

            margin: 0;

            padding: 0;

            color:
                var(--text);

            line-height:
                1.6;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

        }


        /* ====================================================
           HEADER
        ==================================================== */

        header {

            background:
                var(--blue);

            color:
                white;

            height:
                52px;

            display:
                flex;

            align-items:
                center;

            padding:
                0 20px;

            border-bottom:
                3px solid var(--green);

            position:
                sticky;

            top:
                0;

            z-index:
                1000;

        }


        .back-btn {

            color:
                white;

            text-decoration:
                none;

            font-size:
                22px;

            margin-right:
                14px;

            line-height:
                1;

        }


        .header-title {

            font-weight:
                600;

            font-size:
                15px;

        }


        /* ====================================================
           CONTAINER
        ==================================================== */

        .container {

            max-width:
                920px;

            margin:
                24px auto;

            width:
                94%;

            padding-bottom:
                48px;

        }


        /* ====================================================
           ARTICLE CARD
        ==================================================== */

        .content-card {

            background:
                white;

            border-radius:
                var(--radius);

            overflow:
                hidden;

            box-shadow:
                var(--shadow);

            margin-bottom:
                24px;

        }


        .main-img {

            width:
                100%;

            max-height:
                420px;

            object-fit:
                contain;

            background:
                #001f3f;

            display:
                block;

        }


        .article-body {

            padding:
                24px 28px 28px;

        }


        .article-title {

            color:
                var(--blue);

            font-size:
                1.65rem;

            line-height:
                1.3;

            margin:
                0 0 10px 0;

            font-weight:
                700;

        }


        .article-date {

            color:
                var(--muted);

            font-size:
                13px;

            margin-bottom:
                18px;

            border-bottom:
                1px solid #eee;

            padding-bottom:
                12px;

        }


        /* ====================================================
           ARTICLE CONTENT
        ==================================================== */

        .article-text {

            color:
                #333;

            line-height:
                1.85;

            font-size:
                16px;

            word-wrap:
                break-word;

            overflow-wrap:
                anywhere;

        }


        .article-text p {

            margin:
                0 0 18px;

        }


        .article-text strong {

            font-weight:
                700;

            color:
                #222;

        }


        .article-text em {

            font-style:
                italic;

        }


        .article-text h2 {

            color:
                var(--blue);

            font-size:
                1.35rem;

            line-height:
                1.35;

            margin:
                28px 0 12px;

            padding-bottom:
                6px;

            border-bottom:
                2px solid #e8eeee;

        }


        .article-text h3 {

            color:
                var(--green);

            font-size:
                1.18rem;

            line-height:
                1.4;

            margin:
                24px 0 10px;

        }


        .article-text h4 {

            color:
                #444;

            font-size:
                1.05rem;

            line-height:
                1.4;

            margin:
                20px 0 8px;

        }


        .article-text ul,
        .article-text ol {

            margin:
                8px 0 20px;

            padding-left:
                28px;

        }


        .article-text li {

            margin-bottom:
                7px;

        }


        .article-text blockquote {

            margin:
                20px 0;

            padding:
                13px 16px;

            background:
                #f1f7f4;

            border-left:
                5px solid var(--green);

            border-radius:
                0 8px 8px 0;

            color:
                #444;

            font-style:
                normal;

        }


        .article-text hr {

            border:
                0;

            border-top:
                2px solid #e5e5e5;

            margin:
                28px 0;

        }


        .article-text code {

            background:
                #f0f0f0;

            padding:
                2px 5px;

            border-radius:
                4px;

            font-size:
                0.9em;

        }


        /* ====================================================
           TABLES
        ==================================================== */

        .article-table {

            width:
                100%;

            overflow-x:
                auto;

            margin:
                24px 0;

            padding:
                0;

            -webkit-overflow-scrolling:
                touch;

        }


        .article-table table {

            width:
                100%;

            min-width:
                500px;

            border-collapse:
                collapse;

            font-size:
                14px;

            background:
                white;

        }


        .article-table th,
        .article-table td {

            border:
                1px solid #ddd;

            padding:
                10px;

            text-align:
                left;

            vertical-align:
                top;

        }


        .article-table th {

            background:
                var(--blue);

            color:
                white;

            font-weight:
                700;

        }


        .article-table tr:nth-child(even) {

            background:
                #f9f9f9;

        }


        .article-table tr:hover {

            background:
                #f1f5f3;

        }


        .missing-table {

            border:
                1px dashed #ccc;

            background:
                #fafafa;

            color:
                #888;

            padding:
                12px;

            text-align:
                center;

            margin:
                20px 0;

            border-radius:
                6px;

        }


        /* ====================================================
           SHARE
        ==================================================== */

        .share-box {

            margin-top:
                28px;

            padding-top:
                18px;

            border-top:
                1px solid #eee;

            display:
                flex;

            flex-wrap:
                wrap;

            gap:
                10px;

            align-items:
                center;

        }


        .share-btn {

            background:
                var(--green);

            color:
                white;

            border:
                none;

            padding:
                9px 16px;

            border-radius:
                6px;

            cursor:
                pointer;

            font-weight:
                600;

            font-size:
                13px;

            text-decoration:
                none;

            display:
                inline-block;

        }


        .share-btn:hover {

            opacity:
                0.92;

        }


        /* ====================================================
           PDF
        ==================================================== */

        .pdf-section {

            margin-top:
                28px;

            padding-top:
                22px;

            border-top:
                2px dashed #eee;

        }


        .pdf-preview {

            width:
                100%;

            max-height:
                550px;

            border:
                1px solid #ddd;

            border-radius:
                8px;

            margin-bottom:
                12px;

            background:
                #f0f0f0;

            overflow-y:
                auto;

            display:
                flex;

            flex-direction:
                column;

            align-items:
                center;

        }


        .download-btn {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                8px;

            background:
                var(--green);

            color:
                white;

            padding:
                8px 16px;

            text-decoration:
                none;

            border-radius:
                4px;

            font-weight:
                bold;

            font-size:
                14px;

        }


        .download-btn:hover {

            opacity:
                0.9;

        }


        .download-btn svg {

            width:
                18px;

            height:
                18px;

            fill:
                currentColor;

        }


        /* ====================================================
           COMMENTS
        ==================================================== */

        .comment-section {

            background:
                white;

            border-radius:
                var(--radius);

            padding:
                24px 28px;

            box-shadow:
                var(--shadow);

            margin-bottom:
                24px;

        }


        .comment-box {

            border-bottom:
                1px solid #eee;

            padding:
                12px 0;

        }


        .comment-name {

            font-weight:
                bold;

            color:
                var(--blue);

            font-size:
                14px;

        }


        .comment-text {

            font-size:
                14px;

            color:
                #555;

            margin:
                4px 0;

        }


        .comment-date {

            font-size:
                11px;

            color:
                #aaa;

        }


        .add-comment {

            margin-top:
                20px;

        }


        input,
        textarea {

            width:
                100%;

            padding:
                11px 12px;

            margin-bottom:
                10px;

            border:
                1px solid #ddd;

            border-radius:
                6px;

            font-family:
                inherit;

            font-size:
                14px;

            -webkit-user-select:
                auto;

            -moz-user-select:
                auto;

            -ms-user-select:
                auto;

            user-select:
                auto;

        }


        .comment-btn {

            background:
                var(--blue);

            color:
                white;

            border:
                none;

            padding:
                12px;

            width:
                100%;

            border-radius:
                6px;

            font-weight:
                bold;

            cursor:
                pointer;

            font-size:
                14px;

        }


        .comment-btn:hover {

            opacity:
                0.92;

        }


        /* ====================================================
           LOADER / NOT FOUND
        ==================================================== */

        #loader {

            text-align:
                left;

            background:
                white;

            padding:
                32px;

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            color:
                var(--blue);

        }


        #loader h2 {

            color:
                var(--blue);

            margin-top:
                0;

        }


        #loader p {

            color:
                #555;

            line-height:
                1.6;

        }


        /* ====================================================
           OTHER NEWS
        ==================================================== */

        .other-news-section {

            background:
                white;

            border-radius:
                var(--radius);

            padding:
                24px 28px;

            box-shadow:
                var(--shadow);

            margin-bottom:
                24px;

        }


        .other-news-section h3 {

            color:
                var(--blue);

            margin:
                0 0 18px 0;

            font-size:
                1.2rem;

            border-left:
                4px solid var(--green);

            padding-left:
                12px;

        }


        .other-news-grid {

            display:
                grid;

            grid-template-columns:
                1fr;

            gap:
                12px;

        }


        .other-news-item {

            display:
                flex;

            align-items:
                center;

            gap:
                14px;

            padding:
                12px;

            border:
                1px solid #eee;

            border-radius:
                10px;

            text-decoration:
                none;

            color:
                inherit;

            transition:
                background 0.2s,
                box-shadow 0.2s,
                transform 0.15s;

        }


        .other-news-item:hover {

            background:
                #f8faf9;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.06);

            transform:
                translateY(-1px);

        }


        .other-news-item img {

            width:
                88px;

            height:
                66px;

            object-fit:
                cover;

            border-radius:
                8px;

            border:
                1px solid #e5e5e5;

            flex-shrink:
                0;

            background:
                #001f3f;

        }


        .other-news-item .on-title {

            font-size:
                14px;

            font-weight:
                600;

            color:
                var(--blue);

            line-height:
                1.4;

            flex:
                1;

        }


        .other-news-item .on-arrow {

            color:
                #aaa;

            font-size:
                16px;

            flex-shrink:
                0;

        }


        /* ====================================================
           FOOTER
        ==================================================== */

        .footer {

            background:
                linear-gradient(
                    135deg,
                    #011627 0%,
                    #032038 100%
                );

            color:
                #e2e8f0;

            padding:
                60px 20px 30px;

            margin-top:
                40px;

            border-top:
                4px solid var(--green);

            font-size:
                14px;

        }


        .footer-grid {

            display:
                grid;

            grid-template-columns:
                1.8fr 1.2fr 1.3fr 1fr;

            gap:
                35px;

            max-width:
                1200px;

            margin:
                0 auto;

        }


        .footer h4 {

            color:
                var(--yellow);

            font-size:
                0.9rem;

            text-transform:
                uppercase;

            letter-spacing:
                1.2px;

            margin:
                0 0 16px 0;

            position:
                relative;

            padding-bottom:
                6px;

        }


        .footer h4::after {

            content:
                '';

            position:
                absolute;

            left:
                0;

            bottom:
                0;

            width:
                24px;

            height:
                2px;

            background:
                var(--yellow);

            opacity:
                0.7;

            border-radius:
                2px;

        }


        .footer-about {

            line-height:
                1.7;

            color:
                #94a3b8;

            margin:
                0;

        }


        .footer-links-list {

            list-style:
                none;

            padding:
                0;

            margin:
                0;

        }


        .footer-links-list li {

            margin-bottom:
                10px;

        }


        .footer a {

            color:
                #cbd5e0;

            text-decoration:
                none;

            transition:
                all 0.25s ease;

        }


        .footer-links-list a:hover {

            color:
                var(--yellow);

            transform:
                translateX(4px);

            display:
                inline-block;

        }


        .whatsapp-channel-link {

            color:
                #25D366 !important;

            font-weight:
                600;

            display:
                inline-block;

        }


        .whatsapp-channel-link:hover {

            opacity:
                0.85;

            transform:
                translateX(4px);

        }


        .contact-group {

            margin-bottom:
                16px;

        }


        .contact-group h5 {

            color:
                #ffffff;

            font-size:
                0.82rem;

            text-transform:
                uppercase;

            letter-spacing:
                0.8px;

            margin:
                0 0 6px 0;

            opacity:
                0.9;

        }


        .contact-group a {

            display:
                block;

            color:
                #94a3b8;

            font-size:
                0.88rem;

            margin-bottom:
                4px;

        }


        .contact-group a:hover {

            color:
                #ffffff;

        }


        .footer-bottom {

            max-width:
                1200px;

            margin:
                40px auto 0;

            padding-top:
                20px;

            border-top:
                1px solid rgba(
                    255,
                    255,
                    255,
                    0.08
                );

            text-align:
                center;

            font-size:
                13px;

            color:
                #64748b;

        }


        /* ====================================================
           RESPONSIVE
        ==================================================== */

        @media (min-width: 768px) {

            .container {

                max-width:
                    980px;

                margin:
                    32px auto;

            }


            .article-title {

                font-size:
                    1.85rem;

            }


            .article-text {

                font-size:
                    17px;

                line-height:
                    1.9;

            }


            .article-body {

                padding:
                    28px 36px 36px;

            }


            .main-img {

                max-height:
                    480px;

            }


            .other-news-grid {

                grid-template-columns:
                    1fr 1fr;

                gap:
                    14px;

            }


            .comment-section,
            .other-news-section {

                padding:
                    28px 32px;

            }

        }


        @media (min-width: 1100px) {

            .container {

                max-width:
                    1040px;

            }

        }


        @media (max-width: 900px) {

            .footer-grid {

                grid-template-columns:
                    1fr 1fr;

                gap:
                    30px;

            }

        }


        @media (max-width: 550px) {

            .article-body {

                padding:
                    18px 16px 22px;

            }


            .article-title {

                font-size:
                    1.35rem;

            }


            .article-text {

                font-size:
                    15px;

            }


            .comment-section,
            .other-news-section {

                padding:
                    18px 16px;

            }


            .other-news-item img {

                width:
                    72px;

                height:
                    54px;

            }


            .footer-grid {

                grid-template-columns:
                    1fr;

                gap:
                    28px;

            }

        }

    </style>

</head>


<body
    oncopy="return false"
    oncut="return false"
    onpaste="return false"
    oncontextmenu="return false"
>


<!-- ========================================================
     HEADER
========================================================= -->

<header>

    <a
        href="/index.php"
        class="back-btn"
        aria-label="Back to home"
    >
        ❮
    </a>

    <span class="header-title">
        Full Update
    </span>

</header>


<!-- ========================================================
     MAIN
========================================================= -->

<div class="container">

<?php if (empty($article['title'])): ?>

    <div id="loader">

        <h2>
            Article Not Found
        </h2>

        <p>

            Please select an update from our

            <a
                href="/index.php"
                style="
                    color:var(--blue);
                    font-weight:bold;
                "
            >
                news archive
            </a>.

        </p>

    </div>


<?php else: ?>


    <div id="article-container">


        <!-- ==================================================
             ARTICLE
        =================================================== -->

        <div class="content-card">


            <?php if (!empty($article['imageUrl'])): ?>

                <img
                    id="news-image"
                    class="main-img"
                    src="<?php echo h($article['imageUrl']); ?>"
                    alt="<?php echo h($article['title']); ?>"
                >

            <?php endif; ?>


            <div class="article-body">


                <!-- ==========================================
                     TITLE
                =========================================== -->

                <h1
                    id="news-title"
                    class="article-title"
                >
                    <?php
                    echo h(
                        $article['title']
                    );
                    ?>
                </h1>


                <!-- ==========================================
                     DATE
                =========================================== -->

                <?php if (!empty($article['timestamp'])): ?>

                    <div
                        id="news-date"
                        class="article-date"
                    >

                        Posted:

                        <?php

                        echo date(
                            'D M j Y',
                            strtotime(
                                $article['timestamp']
                            )
                        );

                        ?>

                    </div>

                <?php endif; ?>


                <!-- ==========================================
                     FORMATTED ARTICLE CONTENT
                     
                     THIS IS THE MAJOR CHANGE.
                     
                     The content is now passed through
                     flexi_render_article_content().
                =========================================== -->

                <div
                    id="news-content"
                    class="article-text"
                >

                    <?php
                    echo $renderedArticleContent;
                    ?>

                </div>


                <!-- ==========================================
                     SHARE
                =========================================== -->

                <div class="share-box">

                    <span
                        style="
                            font-size:13px;
                            font-weight:bold;
                            color:#555;
                        "
                    >
                        Share update:
                    </span>


                    <button
                        class="share-btn"
                        onclick="shareArticle()"
                    >
                        Copy Link
                    </button>


                    <a
                        id="whatsapp-share"
                        href="<?php

                        echo
                            'https://api.whatsapp.com/send?text=' .
                            rawurlencode(
                                $article['title']
                                . ' - '
                                . $pageUrl
                            );

                        ?>"
                        target="_blank"
                        rel="noopener"
                        class="share-btn"
                        style="
                            background:#25D366;
                        "
                    >
                        WhatsApp
                    </a>

                </div>


                <!-- ==========================================
                     PDF
                =========================================== -->

                <?php if (!empty($article['pdfUrl'])): ?>

                    <div
                        id="pdf-container"
                        class="pdf-section"
                    >

                        <div
                            style="
                                display:flex;
                                justify-content:space-between;
                                align-items:center;
                                margin-bottom:10px;
                                flex-wrap:wrap;
                                gap:10px;
                            "
                        >

                            <h4
                                style="
                                    color:var(--blue);
                                    margin:0;
                                "
                            >
                                📎 Attached Document
                            </h4>


                            <a
                                id="pdf-download-link"
                                href="<?php echo h($article['pdfUrl']); ?>"
                                class="download-btn"
                                download
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z"
                                    />
                                </svg>

                                Download Full PDF

                            </a>

                        </div>


                        <div
                            id="pdf-viewer-container"
                            class="pdf-preview"
                        >

                            <img
                                src="<?php echo h($pdfPreview); ?>"
                                alt="Document Preview"
                                style="
                                    width:100%;
                                    height:auto;
                                    display:block;
                                "
                            >

                        </div>

                    </div>

                <?php endif; ?>


            </div>

        </div>


        <!-- ==================================================
             COMMENTS
        =================================================== -->

        <div class="comment-section">

            <h3
                style="
                    color:var(--blue);
                    margin-top:0;
                "
            >
                Discussion
            </h3>


            <div id="comments-list">

                <p
                    style="
                        color:#999;
                        font-size:14px;
                    "
                >
                    No comments yet.
                    Be the first!
                </p>

            </div>


            <div class="add-comment">

                <h4
                    style="
                        margin-bottom:10px;
                    "
                >
                    Leave a Comment
                </h4>


                <input
                    type="text"
                    id="comm-name"
                    placeholder="Your Name"
                    required
                >


                <textarea
                    id="comm-text"
                    rows="2"
                    placeholder="Write your thoughts..."
                    required
                ></textarea>


                <button
                    class="comment-btn"
                    id="post-comm-btn"
                    onclick="postComment()"
                >
                    Post Comment
                </button>

            </div>

        </div>


        <!-- ==================================================
             OTHER NEWS
        =================================================== -->

        <div
            class="other-news-section"
            id="other-news-section"
            style="display:none;"
        >

            <h3>
                Other News
            </h3>


            <div
                class="other-news-grid"
                id="other-news-list"
            ></div>

        </div>


    </div>


<?php endif; ?>

</div>


<!-- ========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="footer-grid">


        <!-- ABOUT -->

        <div class="footer-col">

            <p class="footer-about">

                We empower Nigerian students with
                admission updates, CBT preparation,
                tutorials, past questions in PDF,
                and premium educational support.

            </p>

        </div>


        <!-- QUICK LINKS -->

        <div class="footer-col">

            <h4>
                Quick Links
            </h4>


            <ul class="footer-links-list">

                <li>
                    <a href="/index.php">
                        Home
                    </a>
                </li>


                <li>
                    <a
                        href="https://elearning.flexieduconsult.com.ng"
                        target="_blank"
                        rel="noopener"
                    >
                        WhatsApp Masterclass (E-Learning)
                    </a>
                </li>


                <li>
                    <a href="/syllabus.html">
                        Access the JAMB/WAEC syllabus
                    </a>
                </li>


                <li>
                    <a href="/brochure.html">
                        Access JAMB Brochure
                    </a>
                </li>


                <li>
                    <a href="/videos.html">
                        Video Lessons
                    </a>
                </li>


                <li>
                    <a href="/pdf.html">
                        Past Questions & PDFs
                    </a>
                </li>


                <li>
                    <a href="/cbt.html">
                        CBT Simulator
                    </a>
                </li>


                <li>
                    <a href="/classroom.html">
                        Classroom
                    </a>
                </li>


                <li>
                    <a href="/location.html">
                        Tutorial Centres
                    </a>
                </li>

            </ul>

        </div>


        <!-- SUPPORT -->

        <div class="footer-col">

            <h4>
                Support & Community
            </h4>


            <div class="contact-group">

                <a
                    href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u"
                    target="_blank"
                    rel="noopener"
                    class="whatsapp-channel-link"
                >
                    Join our WhatsApp Channel
                </a>

            </div>


            <div class="contact-group">

                <h5>
                    Contact Us
                </h5>


                <a href="tel:+2349034159839">
                    (+234) 903 415 9839
                </a>


                <a href="tel:+2347033855206">
                    (+234) 703 385 5206
                </a>

            </div>


            <div class="contact-group">

                <h5>
                    Email Us
                </h5>


                <a href="mailto:support@flexieduconsult.com.ng">
                    support@flexieduconsult.com.ng
                </a>


                <a href="mailto:info@flexieduconsult.com.ng">
                    info@flexieduconsult.com.ng
                </a>

            </div>

        </div>


        <!-- SOCIAL -->

        <div class="footer-col social-links">

            <h4>
                Follow Us
            </h4>


            <ul class="footer-links-list">

                <li>

                    <a
                        href="https://www.facebook.com/profile.php?id=61589793118693"
                        target="_blank"
                        rel="noopener"
                    >
                        Facebook @flexieduconsult
                    </a>

                </li>


                <li>

                    <a
                        href="https://instagram.com/flexieduconsult2000"
                        target="_blank"
                        rel="noopener"
                    >
                        Instagram @flexieduconsult2000
                    </a>

                </li>


                <li>

                    <a
                        href="https://www.tiktok.com/@flexieduconsult"
                        target="_blank"
                        rel="noopener"
                    >
                        TikTok @flexieduconsult
                    </a>

                </li>

            </ul>

        </div>


    </div>


    <div class="footer-bottom">

        &copy;

        <span id="current-year"></span>

        Flexi Educational Consult.
        All Rights Reserved.

    </div>

</footer>


<!-- ========================================================
     TOASTIFY
========================================================= -->

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/toastify-js"
></script>


<!-- ========================================================
     FIREBASE / COMMENTS / OTHER NEWS
========================================================= -->

<script type="module">

import {
    initializeApp
}
from
"https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";


import {
    getFirestore,
    collection,
    addDoc,
    getDocs,
    query,
    orderBy,
    serverTimestamp
}
from
"https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";


// ==========================================================
// FIREBASE
// ==========================================================

const firebaseConfig = {

    apiKey:
        "AIzaSyA0bM6pk1T1peGSS7quVfPEMOMuplnNRNM",

    authDomain:
        "waec2026jamb2027.firebaseapp.com",

    projectId:
        "waec2026jamb2027"

};


const app =
    initializeApp(
        firebaseConfig
    );


const db =
    getFirestore(
        app
    );


// ==========================================================
// CURRENT ARTICLE ID
// ==========================================================

const currentArticleId =
    <?php

    echo json_encode(
        $article['id'] ?? ''
    );

    ?>;


// ==========================================================
// SAFE HTML ESCAPE FOR CLIENT-SIDE DATA
// ==========================================================

function escapeHtml(str) {

    if (!str) return '';

    return String(str)

        .replace(
            /&/g,
            '&amp;'
        )

        .replace(
            /</g,
            '&lt;'
        )

        .replace(
            />/g,
            '&gt;'
        )

        .replace(
            /"/g,
            '&quot;'
        )

        .replace(
            /'/g,
            '&#039;'
        );

}


// ==========================================================
// SHARE ARTICLE
// ==========================================================

window.shareArticle = () => {

    if (
        navigator.share
    ) {

        navigator.share({

            title:
                document
                    .getElementById(
                        'news-title'
                    )
                    ?.innerText
                ||
                'News',

            url:
                window.location.href

        })
        .catch(
            () => {}
        );


    } else {

        if (
            navigator.clipboard
        ) {

            navigator.clipboard
                .writeText(
                    window.location.href
                );

        }


        if (
            typeof Toastify !==
            'undefined'
        ) {

            Toastify({

                text:
                    "Link copied to clipboard!",

                duration:
                    3000,

                gravity:
                    "top",

                position:
                    "right",

                style: {

                    background:
                        "#2E8B57"

                }

            }).showToast();

        }

    }

};


// ==========================================================
// LOAD COMMENTS
// ==========================================================

async function loadComments() {

    if (!currentArticleId)
        return;


    const cList =
        document.getElementById(
            'comments-list'
        );


    if (!cList)
        return;


    try {

        const q =
            query(

                collection(
                    db,
                    "news",
                    currentArticleId,
                    "comments"
                ),

                orderBy(
                    "timestamp",
                    "desc"
                )

            );


        const snap =
            await getDocs(
                q
            );


        if (!snap.empty) {

            cList.innerHTML =
                "";


            snap.forEach(
                d => {

                    const c =
                        d.data();


                    const date =
                        c.timestamp
                            ?
                            c.timestamp
                                .toDate()
                                .toLocaleString()
                            :
                            "Just now";


                    cList.innerHTML += `

                        <div class="comment-box">

                            <div class="comment-name">

                                ${escapeHtml(
                                    c.name
                                )}

                            </div>


                            <div class="comment-text">

                                ${escapeHtml(
                                    c.text
                                )}

                            </div>


                            <div class="comment-date">

                                ${escapeHtml(
                                    date
                                )}

                            </div>

                        </div>

                    `;

                }
            );

        }

    } catch (e) {

        console.error(
            "Error loading comments:",
            e
        );

    }

}


// ==========================================================
// LOAD OTHER NEWS
// ==========================================================

async function loadOtherNews() {

    const section =
        document.getElementById(
            'other-news-section'
        );


    const list =
        document.getElementById(
            'other-news-list'
        );


    if (
        !section ||
        !list
    )
        return;


    try {

        const snap =
            await getDocs(
                collection(
                    db,
                    "news"
                )
            );


        if (
            snap.empty
        )
            return;


        const items = [];


        snap.forEach(
            d => {

                if (
                    d.id ===
                    currentArticleId
                )
                    return;


                const data =
                    d.data();


                items.push({

                    id:
                        d.id,

                    title:
                        data.title ||
                        "News Update",

                    imageUrl:
                        data.imageUrl ||
                        "https://via.placeholder.com/88x66",

                    slug:
                        data.slug ||
                        "",

                    timestamp:
                        data.timestamp
                            ?
                            data.timestamp
                                .toDate()
                                .getTime()
                            :
                            0

                });

            }
        );


        items.sort(
            (a, b) =>
                b.timestamp -
                a.timestamp
        );


        const top =
            items.slice(
                0,
                6
            );


        if (
            top.length === 0
        )
            return;


        list.innerHTML =
            top
                .map(
                    item => {

                        const href =
                            item.slug

                                ?
                                `/news/${encodeURIComponent(
                                    item.slug
                                )}`

                                :
                                `/news/id/${encodeURIComponent(
                                    item.id
                                )}`;


                        return `

                            <a
                                class="other-news-item"
                                href="${escapeHtml(
                                    href
                                )}"
                            >

                                <img
                                    src="${escapeHtml(
                                        item.imageUrl
                                    )}"
                                    alt="${escapeHtml(
                                        item.title
                                    )}"
                                    loading="lazy"
                                >


                                <span class="on-title">

                                    ${escapeHtml(
                                        item.title
                                    )}

                                </span>


                                <span class="on-arrow">
                                    ❯
                                </span>

                            </a>

                        `;

                    }
                )
                .join(
                    ""
                );


        section.style.display =
            "block";


    } catch (e) {

        console.error(
            "Error loading other news:",
            e
        );

    }

}


// ==========================================================
// POST COMMENT
// ==========================================================

window.postComment =
    async () => {

        const name =
            document
                .getElementById(
                    'comm-name'
                )
                .value
                .trim();


        const text =
            document
                .getElementById(
                    'comm-text'
                )
                .value
                .trim();


        const btn =
            document.getElementById(
                'post-comm-btn'
            );


        if (
            !name ||
            !text
        ) {

            alert(
                "Please fill both fields"
            );

            return;

        }


        if (!currentArticleId) {

            alert(
                "This article cannot receive comments."
            );

            return;

        }


        btn.disabled =
            true;


        btn.innerText =
            "Posting...";


        try {

            await addDoc(

                collection(
                    db,
                    "news",
                    currentArticleId,
                    "comments"
                ),

                {

                    name:
                        name,

                    text:
                        text,

                    timestamp:
                        serverTimestamp()

                }

            );


            if (
                typeof Toastify !==
                'undefined'
            ) {

                Toastify({

                    text:
                        "Comment posted!",

                    style: {

                        background:
                            "#2E8B57"

                    }

                }).showToast();

            }


            document
                .getElementById(
                    'comm-text'
                )
                .value =
                "";


            loadComments();


        } catch (e) {

            console.error(
                e
            );


            alert(
                "Error posting comment."
            );


        } finally {

            btn.disabled =
                false;


            btn.innerText =
                "Post Comment";

        }

    };


// ==========================================================
// CURRENT YEAR
// ==========================================================

const yearElement =
    document.getElementById(
        'current-year'
    );


if (yearElement) {

    yearElement.textContent =
        new Date()
            .getFullYear();

}


// ==========================================================
// INTERACTIVE CONTENT
// ==========================================================

if (
    currentArticleId
) {

    loadComments();

    loadOtherNews();

}

</script>


</body>
</html>
