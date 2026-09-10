<?php
/**
 * Flexi Educational Consult - Shared Head Component
 *
 * Centralized <head> for public PHP pages.
 *
 * Optional variables:
 *   $pageTitle
 *   $pageDescription
 *   $pageKeywords
 *   $pageImage
 *   $pageCanonical
 *   $includeToastify
 *   $includeAdsense
 *
 * IMPORTANT:
 * Page-specific pages may still provide their own SEO metadata.
 * Do not include this component on a page that already outputs a
 * complete <head> unless that page is first migrated to use it.
 */

// ---------------------------------------------------------
// Safe defaults
// ---------------------------------------------------------

if (!isset($pageTitle) || trim($pageTitle) === '') {
    $pageTitle = 'Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria';
}

if (!isset($pageDescription) || trim($pageDescription) === '') {
    $pageDescription = 'Flexi Educational Consult (Flexi Tutors) provides JAMB, WAEC, NECO and JUPEB preparation, CBT practice, admission updates, past questions and educational support.';
}

if (!isset($pageKeywords) || trim($pageKeywords) === '') {
    $pageKeywords = 'Flexi Educational Consult, Flexi Tutors, JAMB, UTME, WAEC, NECO, JUPEB, CBT, past questions, admission updates, Nigeria';
}

if (!isset($pageImage) || trim($pageImage) === '') {
    $pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';
}

if (!isset($pageCanonical) || trim($pageCanonical) === '') {
    $pageCanonical = '';
}

// Optional external resources.
// Keep these disabled unless the page actually needs them.
if (!isset($includeToastify)) {
    $includeToastify = false;
}

if (!isset($includeAdsense)) {
    $includeAdsense = false;
}

// ---------------------------------------------------------
// Escape helper
// ---------------------------------------------------------

$flexiEscape = static function ($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

$flexiTitle       = $flexiEscape($pageTitle);
$flexiDescription = $flexiEscape($pageDescription);
$flexiKeywords    = $flexiEscape($pageKeywords);
$flexiImage       = $flexiEscape($pageImage);
$flexiCanonical   = $flexiEscape($pageCanonical);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="<?php echo $flexiDescription; ?>"
    >

    <meta
        name="keywords"
        content="<?php echo $flexiKeywords; ?>"
    >

    <!-- Favicon -->
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

    <!-- Open Graph -->
    <meta
        property="og:title"
        content="<?php echo $flexiTitle; ?>"
    >

    <meta
        property="og:description"
        content="<?php echo $flexiDescription; ?>"
    >

    <meta
        property="og:image"
        content="<?php echo $flexiImage; ?>"
    >

    <meta
        property="og:type"
        content="website"
    >

    <?php if ($pageCanonical !== ''): ?>
        <meta
            property="og:url"
            content="<?php echo $flexiCanonical; ?>"
        >

        <link
            rel="canonical"
            href="<?php echo $flexiCanonical; ?>"
        >
    <?php endif; ?>

    <!-- Twitter -->
    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="<?php echo $flexiTitle; ?>"
    >

    <meta
        name="twitter:description"
        content="<?php echo $flexiDescription; ?>"
    >

    <meta
        name="twitter:image"
        content="<?php echo $flexiImage; ?>"
    >

    <title><?php echo $flexiTitle; ?></title>

    <?php if ($includeAdsense): ?>
        <!-- Google AdSense - enabled only on pages that explicitly request it -->
        <script
            async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180"
            crossorigin="anonymous"
        ></script>
    <?php endif; ?>

    <?php if ($includeToastify): ?>
        <!-- Toastify - enabled only on pages that explicitly request it -->
        <link
            rel="stylesheet"
            type="text/css"
            href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
        >
    <?php endif; ?>

    <!-- Central Flexi Brand CSS -->
    <link
        rel="stylesheet"
        type="text/css"
        href="/assets/css/flexi-brand.css"
    >

</head>
<body>
