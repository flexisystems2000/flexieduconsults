<?php
// SERVER-SIDE PHP: Generates dynamic Open Graph meta tags for WhatsApp & Social crawlers
$firebaseProjectId = "waec2026jamb2027";

$newsId = isset($_GET['id']) ? trim($_GET['id']) : '';
$newsSlug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$pageTitle = "Flexi Tutors | News Update";
$pageDesc = "Read the latest educational news, JAMB updates, WAEC notices, and admission guides on Flexi Educational Consult.";
$pageImage = "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg";
$pageUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if ($newsId || $newsSlug) {
    $apiUrl = "https://firestore.googleapis.com/v1/projects/{$firebaseProjectId}/databases/(default)/documents/news";
    $response = @file_get_contents($apiUrl);
    
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['documents'])) {
            foreach ($data['documents'] as $doc) {
                $docNameParts = explode('/', $doc['name']);
                $docId = end($docNameParts);
                $fields = isset($doc['fields']) ? $doc['fields'] : [];
                $slug = isset($fields['slug']['stringValue']) ? $fields['slug']['stringValue'] : '';
                
                if (($newsId && $docId === $newsId) || ($newsSlug && $slug === $newsSlug)) {
                    if (isset($fields['seoTitle']['stringValue'])) {
                        $pageTitle = $fields['seoTitle']['stringValue'] . " | Flexi Educational Consult";
                    } elseif (isset($fields['title']['stringValue'])) {
                        $pageTitle = $fields['title']['stringValue'] . " | Flexi Educational Consult";
                    }
                    
                    if (isset($fields['metaDescription']['stringValue'])) {
                        $pageDesc = $fields['metaDescription']['stringValue'];
                    } elseif (isset($fields['content']['stringValue'])) {
                        $pageDesc = mb_substr(strip_tags($fields['content']['stringValue']), 0, 150) . "...";
                    }
                    
                    if (isset($fields['imageUrl']['stringValue'])) {
                        $pageImage = $fields['imageUrl']['stringValue'];
                    }
                    break;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Google AdSense Auto Ads -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180"
         crossorigin="anonymous"></script>

    <link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic SEO Meta Tags Generated via PHP for Instant Social Previews -->
    <title id="page-title"><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDesc); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($pageUrl); ?>">

    <!-- Open Graph Meta Tags for WhatsApp & Social Media Previews -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDesc); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($pageImage); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($pageUrl); ?>">
    <meta property="og:type" content="article">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDesc); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($pageImage); ?>">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root { --blue: #003366; --green: #2E8B57; --yellow: #FFD700; --bg: #f4f7f6; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; padding: 0; }

        header { 
            background: var(--blue); color: white; height: 1cm; 
            display: flex; align-items: center; padding: 0 15px;
            border-bottom: 3px solid var(--green); position: sticky; top: 0; z-index: 1000;
        }
        .back-btn { color: white; text-decoration: none; font-size: 20px; margin-right: 15px; cursor: pointer; }

        .container { max-width: 700px; margin: 15px auto; width: 95%; padding-bottom: 40px; }

        .content-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .main-img { width: 100%; max-height: 400px; object-fit: contain; background: #001f3f; display: block; }
        .article-body { padding: 20px; }
        .article-title { color: var(--blue); font-size: 22px; margin: 0 0 8px 0; }
        .article-date { color: #888; font-size: 13px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .article-text { color: #333; line-height: 1.8; font-size: 15px; white-space: pre-wrap; }

        /* Share Box Styling */
        .share-box{
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .share-btn{
            background: var(--green);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
        }

        /* Table Styling */
        .data-table-container { margin-top: 20px; overflow-x: auto; border-top: 1px solid #eee; padding-top: 15px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: var(--blue); color: white; }
        tr:nth-child(even) { background: #f9f9f9; }

        /* PDF Section Styling */
        .pdf-section { margin-top: 25px; padding-top: 20px; border-top: 2px dashed #eee; }
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
        
        .download-btn { 
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--green); color: white; padding: 8px 16px; 
            text-decoration: none; border-radius: 4px; font-weight: bold; 
            font-size: 14px; transition: opacity 0.2s;
        }
        .download-btn:hover { opacity: 0.9; }
        .download-btn svg { width: 18px; height: 18px; fill: currentColor; }

        /* Comments Section */
        .comment-section { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .comment-box { border-bottom: 1px solid #eee; padding: 10px 0; }
        .comment-name { font-weight: bold; color: var(--blue); font-size: 14px; }
        .comment-text { font-size: 14px; color: #555; margin: 4px 0; }
        .comment-date { font-size: 11px; color: #aaa; }

        .add-comment { margin-top: 20px; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        .comment-btn { background: var(--blue); color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; }

        /* Crawler-Friendly Loader Container */
        #loader { 
            text-align: left; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: var(--blue); 
        }
        #loader h2 { color: var(--blue); margin-top: 0; }
        #loader p { color: #555; line-height: 1.6; }
    
        /* MODERN FOOTER STYLES */
        .footer {
            background: linear-gradient(135deg, #011627 0%, #032038 100%);
            color: #e2e8f0;
            padding: 60px 20px 30px 20px;
            margin-top: 60px;
            border-top: 4px solid var(--green, #22c55e);
            font-size: 14px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr 1.3fr 1fr;
            gap: 35px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer h4 {
            color: var(--yellow, #fbbf24);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 0 0 16px 0;
            position: relative;
            padding-bottom: 6px;
        }

        .footer h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 24px;
            height: 2px;
            background: var(--yellow, #fbbf24);
            opacity: 0.7;
            border-radius: 2px;
        }

        .footer-about {
            line-height: 1.7;
            color: #94a3b8;
            margin: 0;
        }

        .footer-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links-list li {
            margin-bottom: 10px;
        }

        .footer a {
            color: #cbd5e0;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .footer-links-list a:hover {
            color: var(--yellow, #fbbf24);
            transform: translateX(4px);
            display: inline-block;
        }

        .whatsapp-channel-link {
            color: #25D366 !important;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        .whatsapp-channel-link:hover {
            opacity: 0.85;
            transform: translateX(4px);
        }

        .contact-group {
            margin-bottom: 16px;
        }

        .contact-group h5 {
            color: #ffffff;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 6px 0;
            opacity: 0.9;
        }

        .contact-group a {
            display: block;
            color: #94a3b8;
            font-size: 0.88rem;
            margin-bottom: 4px;
        }

        .contact-group a:hover {
            color: #ffffff;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0 auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 550px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="index.html" class="back-btn">❮</a>
    <span style="font-weight: bold; font-size: 14px;">Full Update</span>
</header>

<div class="container">
    <!-- Crawlable AdSense-Friendly Loader Fallback -->
    <div id="loader">
        <h2>Flexi Educational Consult News Portal</h2>
        <p>Loading official admission news updates, JAMB/WAEC notices, and study announcements. If the article content does not load instantly, please view our verified announcements index directly on the <a href="index.html" style="color:var(--blue); font-weight:bold;">homepage</a>.</p>
    </div>

    <div id="article-container" style="display: none;">
        <div class="content-card">
            <img id="news-image" class="main-img" src="" alt="" style="display: none;">
            <div class="article-body">
                <h1 id="news-title" class="article-title"></h1>
                <div id="news-date" class="article-date"></div>
                <div id="news-content" class="article-text"></div>

                <!-- SHARE BUTTONS -->
                <div class="share-box">
                    <span style="font-size:13px; font-weight:bold; color:#555;">Share update:</span>
                    <button class="share-btn" onclick="shareArticle()">Copy Link</button>
                    <a id="whatsapp-share" href="#" target="_blank" class="share-btn" style="background:#25D366;">WhatsApp</a>
                </div>

                <div id="table-container" class="data-table-container" style="display: none;"></div>

                <div id="pdf-container" class="pdf-section" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="color: var(--blue); margin: 0;">📎 Attached Document</h4>
                        <a id="pdf-download-link" href="#" class="download-btn" download>
                            <svg viewBox="0 0 24 24"><path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z"/></svg>
                            Download Full PDF
                        </a>
                    </div>
                    <div id="pdf-viewer-container" class="pdf-preview"></div>
                </div>
            </div>
        </div>

        <div class="comment-section">
            <h3 style="color: var(--blue); margin-top: 0;">Discussion</h3>
            <div id="comments-list">
                <p style="color: #999; font-size: 14px;">No comments yet. Be the first!</p>
            </div>

            <div class="add-comment">
                <h4 style="margin-bottom: 10px;">Leave a Comment</h4>
                <input type="text" id="comm-name" placeholder="Your Name" required>
                <textarea id="comm-text" rows="2" placeholder="Write your thoughts..." required></textarea>
                <button class="comment-btn" id="post-comm-btn" onclick="postComment()">Post Comment</button>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <p class="footer-about">
               We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.
            </p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links-list">
                <li><a href="index.html">Home</a></li>
                <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
                <li><a href="syllabus.html">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="brochure.html">Access JAMB Brochure</a></li>
                <li><a href="videos.html">Video Lessons</a></li>
                <li><a href="pdf.html">Past Questions & PDFs</a></li>
                <li><a href="cbt.html">CBT Simulator</a></li>
                <li><a href="classroom.html">Classroom</a></li>
                <li><a href="location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Support & Community</h4>
            
            <div class="contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" 
                   target="_blank" 
                   class="whatsapp-channel-link">
                   Join our WhatsApp Channel
                </a>
            </div>

            <div class="contact-group">
                <h5>Contact Us</h5>
                <a href="tel:+2349034159839">(+234) 903 415 9839</a>
                <a href="tel:+2347033855206">(+234) 703 385 5206</a>
            </div>

            <div class="contact-group">
                <h5>Email Us</h5>
                <a href="mailto:support@flexieduconsult.com.ng">support@flexieduconsult.com.ng</a>
                <a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a>
            </div>
        </div>

        <div class="footer-col social-links">
            <h4>Follow Us</h4>
            <ul class="footer-links-list">
                <li><a href="https://www.facebook.com/profile.php?id=61589793118693" target="_blank">Facebook @flexieduconsult</a></li>
                <li><a href="https://instagram.com/flexieduconsult2000" target="_blank">Instagram @flexieduconsult2000</a></li>
                <li><a href="https://www.tiktok.com/@flexieduconsult" target="_blank">TikTok @flexieduconsult</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; <span id="current-year"></span> Flexi Educational Consult. All Rights Reserved.
    </div>
</footer>
    
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getFirestore, doc, getDoc, collection, addDoc, getDocs, query, where, limit, orderBy, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

    const firebaseConfig = {
        apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",
        authDomain: "waec2026jamb2027.firebaseapp.com",
        projectId: "waec2026jamb2027",
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get("slug");
    const articleId = urlParams.get("id");

    let currentArticleId = articleId;

    async function loadPageData() {
        if (!slug && !articleId) {
            document.getElementById('loader').innerHTML = `
                <h2>No Article Specified</h2>
                <p>Please select an update from our <a href="index.html" style="color:var(--blue);">news archive</a>.</p>
            `;
            return;
        }

        try {
            let data;
            if (slug) {
                const q = query(
                    collection(db, "news"),
                    where("slug", "==", slug),
                    limit(1)
                );
                const snap = await getDocs(q);
                if (snap.empty) {
                    document.getElementById("loader").innerHTML = `<h2>Article Not Found</h2><p>Return to <a href="index.html" style="color:var(--blue);">Home</a>.</p>`;
                    return;
                }
                const article = snap.docs[0];
                data = article.data();
                currentArticleId = article.id;
            } else {
                const docSnap = await getDoc(doc(db, "news", articleId));
                if (!docSnap.exists()) {
                    document.getElementById("loader").innerHTML = `<h2>Article Not Found</h2><p>Return to <a href="index.html" style="color:var(--blue);">Home</a>.</p>`;
                    return;
                }
                data = docSnap.data();
                currentArticleId = docSnap.id;
            }

            // Populate Article Elements
            document.getElementById('news-title').innerText = data.title || "";
            document.getElementById('news-content').innerText = data.content || data.body || "";
            
            if(data.imageUrl) {
                const imgEl = document.getElementById('news-image');
                imgEl.src = data.imageUrl;
                imgEl.style.display = "block";
            } else {
                document.getElementById('news-image').style.display = "none";
            }

            if(data.timestamp) {
                document.getElementById('news-date').innerText = "Posted: " + data.timestamp.toDate().toDateString();
            }

            // Setup Share Links
            const currentUrl = window.location.href;
            document.getElementById('whatsapp-share').href = `https://api.whatsapp.com/send?text=${encodeURIComponent((data.title || 'News') + " - " + currentUrl)}`;

            // Table Logic
            const tableCont = document.getElementById('table-container');
            if (data.tableData && data.tableData.trim() !== "") {
                tableCont.innerHTML = data.tableData;
                tableCont.style.display = "block";
            } else {
                tableCont.style.display = "none";
            }

            // PDF Logic
            const pdfCont = document.getElementById('pdf-container');
            if (data.pdfUrl && data.pdfUrl.trim() !== "") {
                const pdfViewerCont = document.getElementById('pdf-viewer-container');
                const pdfLink = document.getElementById('pdf-download-link');
                
                const originalUrl = data.pdfUrl;
                let downloadUrl = originalUrl;
                let previewImageUrl = originalUrl;

                if (originalUrl.includes("res.cloudinary.com")) {
                    downloadUrl = originalUrl.replace('/upload/', '/upload/fl_attachment/');
                    previewImageUrl = originalUrl.replace('/upload/', '/upload/w_800,c_limit,q_auto,f_jpg/pg_1/').replace('.pdf', '.jpg');
                }

                pdfLink.href = downloadUrl;
                pdfViewerCont.innerHTML = `<img src="${previewImageUrl}" alt="Document Preview" style="width:100%; height:auto; display:block;">`;
                pdfCont.style.display = "block";
            } else {
                pdfCont.style.display = "none";
            }

            // Hide loader and show article content container
            document.getElementById('loader').style.display = "none";
            document.getElementById('article-container').style.display = "block";

            loadComments();

        } catch (e) {
            console.error(e);
            document.getElementById('loader').innerHTML = `<h2>Connection Error</h2><p>Could not load the article text. Please check your connection.</p>`;
        }
    }

    // Share action implementation
    window.shareArticle = () => {
        if (navigator.share) {
            navigator.share({
                title: document.getElementById('news-title').innerText,
                url: window.location.href
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            Toastify({
                text: "Link copied to clipboard!",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: { background: "#2E8B57" }
            }).showToast();
        }
    };

    async function loadComments() {
        if (!currentArticleId) return;
        const cList = document.getElementById('comments-list');
        const q = query(collection(db, "news", currentArticleId, "comments"), orderBy("timestamp", "desc"));
        const snap = await getDocs(q);
        
        if (!snap.empty) {
            cList.innerHTML = "";
            snap.forEach(d => {
                const c = d.data();
                const date = c.timestamp ? c.timestamp.toDate().toLocaleString() : "Just now";
                cList.innerHTML += `
                    <div class="comment-box">
                        <div class="comment-name">${c.name}</div>
                        <div class="comment-text">${c.text}</div>
                        <div class="comment-date">${date}</div>
                    </div>
                `;
            });
        }
    }

    window.postComment = async () => {
        const name = document.getElementById('comm-name').value;
        const text = document.getElementById('comm-text').value;
        const btn = document.getElementById('post-comm-btn');

        if(!name || !text) return alert("Please fill both fields");

        btn.disabled = true;
        btn.innerText = "Posting...";

        try {
            await addDoc(collection(db, "news", currentArticleId, "comments"), {
                name: name,
                text: text,
                timestamp: serverTimestamp()
            });
            Toastify({ text: "Comment posted!", style: { background: "#2E8B57" } }).showToast();
            document.getElementById('comm-text').value = "";
            loadComments();
        } catch (e) {
            alert("Error posting comment.");
        } finally {
            btn.disabled = false;
            btn.innerText = "Post Comment";
        }
    };

    document.getElementById('current-year').textContent = new Date().getFullYear();
    loadPageData();
</script>
</body>
</html>
