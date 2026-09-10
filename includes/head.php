<?php
/**
 * Flexi Educational Consult - Shared Head Component
 * 
 * This file provides the common HTML head section for all public website pages.
 * 
 * Variables expected:
 *  - $pageTitle (string, optional): Page title. Defaults to "Flexi Tutors"
 * 
 * Usage:
 *  <?php $pageTitle = "My Page Title"; include __DIR__ . '/includes/head.php'; ?>
 */

// Safe defaults
if (!isset($pageTitle)) {
    $pageTitle = "Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria";
}

if (!isset($pageDescription)) {
    $pageDescription = "Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.";
}

if (!isset($pageImage)) {
    $pageImage = "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($pageImage); ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($pageImage); ?>">
    
    <!-- Title -->
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180" crossorigin="anonymous"></script>
    
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <!-- Flexi Brand CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/flexi-brand.css">
</head>
<body>
