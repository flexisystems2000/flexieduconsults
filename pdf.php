<?php
$pageTitle = 'Resource Library | Flexi Educational Consult';
$pageDescription = 'Access JAMB, WAEC, NECO past questions, textbooks, lecture notes, formula sheets and other educational resources from Flexi Educational Consult.';
$pageKeywords = 'JAMB past questions, WAEC past questions, NECO, textbooks, lecture notes, Flexi Educational Consult, educational resources';
$pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';
$pageCanonical = 'https://www.flexieduconsult.com.ng/pdf.php';

$includeToastify = false;
$includeAdsense = false;

$currentPage = 'pdf.php';

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    /* =========================================================
       FLEXI RESOURCE LIBRARY
       Page-specific styles only
       ========================================================= */

    .flexi-resource-page {
        background: #ffffff;
        color: #333333;
        min-height: calc(100vh - 80px);
    }

    .flexi-resource-container {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        padding: 30px 20px 50px;
    }

    /* Filter Ribbon */
    .flexi-resource-filters {
        background: #ffffff;
        border: 1px solid #eef2f5;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);

        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .flexi-resource-search {
        flex: 2;
        min-width: 250px;
    }

    .flexi-resource-search input {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #dcdfe6;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        background: #ffffff;
        color: #333333;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .flexi-resource-search input:focus {
        border-color: var(--flexi-primary, #003366);
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.08);
    }

    .flexi-resource-filter-select {
        flex: 1;
        min-width: 140px;
        padding: 10px;
        border: 1px solid #dcdfe6;
        border-radius: 6px;
        background-color: #ffffff;
        color: #333333;
        font-size: 14px;
        outline: none;
        cursor: pointer;
    }

    .flexi-resource-filter-select:focus {
        border-color: var(--flexi-secondary, #2E8B57);
    }

    /* Resource Grid */
    .flexi-resource-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }

    /* Resource Card */
    .flexi-resource-card {
        background: #ffffff;
        border: 1px solid #eef2f5;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);

        display: flex;
        flex-direction: column;
        height: 100%;

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }

    .flexi-resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    /* Thumbnail */
    .flexi-resource-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 75%;
        background-color: #f7f9fa;
        border-bottom: 1px solid #eef2f5;
    }

    .flexi-resource-thumbnail img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top;
    }

    .flexi-resource-thumbnail-placeholder {
        position: absolute;
        inset: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        font-weight: 800;
        font-size: 28px;
        color: #dcdfe6;
        background: #fcfdfe;
    }

    /* Badges */
    .flexi-resource-badges {
        position: absolute;
        top: 12px;
        left: 12px;

        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .flexi-resource-badge {
        color: #ffffff;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .flexi-resource-badge-exam {
        background-color: #0B1B3D;
    }

    .flexi-resource-badge-year {
        background-color: #00A859;
    }

    /* Card Body */
    .flexi-resource-card-body {
        padding: 18px;

        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .flexi-resource-subject {
        color: #00A859;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .flexi-resource-title {
        color: #0B1B3D;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 15px;

        flex-grow: 1;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Card Action Area */
    .flexi-resource-card-actions {
        border-top: 1px solid #f7f9fa;
        padding-top: 12px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .flexi-resource-price {
        font-size: 16px;
        font-weight: 700;
        color: #0B1B3D;
    }

    .flexi-resource-free-status {
        font-size: 12px;
        font-weight: 600;
        color: #00A859;
        background-color: #e6f6ef;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }

    /* Buttons */
    .flexi-resource-action {
        border: none;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 5px;
        cursor: pointer;

        transition:
            background 0.2s,
            transform 0.15s;

        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .flexi-resource-action:hover {
        transform: translateY(-1px);
    }

    .flexi-resource-action:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .flexi-resource-download {
        background-color: #0B1B3D;
        color: #ffffff;
    }

    .flexi-resource-download:hover {
        background-color: #122b5e;
    }

    .flexi-resource-unlock {
        background-color: #00A859;
        color: #ffffff;
    }

    .flexi-resource-unlock:hover {
        background-color: #008f4c;
    }

    /* Empty State */
    .flexi-resource-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        color: #909399;
    }

    .flexi-resource-empty strong {
        display: block;
        color: #0B1B3D;
        font-size: 18px;
        margin-bottom: 6px;
    }

    /* Mobile */
    @media (max-width: 700px) {
        .flexi-resource-container {
            padding: 20px 14px 40px;
        }

        .flexi-resource-filters {
            padding: 15px;
            gap: 10px;
        }

        .flexi-resource-search,
        .flexi-resource-filter-select {
            flex: 1 1 100%;
            min-width: 100%;
        }

        .flexi-resource-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
    }
</style>

<main class="flexi-resource-page">
    <div class="flexi-resource-container">

        <div class="flexi-resource-filters">

            <div class="flexi-resource-search">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search by title, subject or description..."
                    autocomplete="off"
                >
            </div>

            <select id="categoryFilter" class="flexi-resource-filter-select">
                <option value="">All Categories</option>
                <option value="Textbooks">Textbooks</option>
                <option value="Past Questions">Past Questions</option>
                <option value="Lecture Notes">Lecture Notes</option>
                <option value="Formula Sheets">Formula Sheets</option>
            </select>

            <select id="examFilter" class="flexi-resource-filter-select">
                <option value="">All Exams</option>
                <option value="JAMB">JAMB</option>
                <option value="WAEC">WAEC</option>
                <option value="NECO">NECO</option>
            </select>

            <select id="typeFilter" class="flexi-resource-filter-select">
                <option value="">All Prices</option>
                <option value="free">Free</option>
                <option value="paid">Premium</option>
            </select>

        </div>

        <div
            class="flexi-resource-grid"
            id="resourceGrid"
            aria-live="polite"
        ></div>

    </div>
</main>

<!-- Paystack V2 -->
<script src="https://js.paystack.co/v2/inline.js"></script>

<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";

    import {
        getFirestore,
        collection,
        doc,
        getDoc,
        getDocs,
        setDoc
    } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";

    import {
        getAuth,
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";


    /* =========================================================
       FIREBASE CONFIGURATION
       ========================================================= */

    const firebaseConfig = {
        apiKey: "AIzaSyA0bM6pk1T1peGSS7qufVPEMOMuplnNRNM",
        authDomain: "waec2026jamb2027.firebaseapp.com",
        projectId: "waec2026jamb2027"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);
    const auth = getAuth(app);


    /* =========================================================
       APPLICATION STATE
       ========================================================= */

    let masterResources = [];
    let clearUserPurchases = new Set();
    let activeUser = null;


    /* =========================================================
       AUTHENTICATION STATE
       ========================================================= */

    onAuthStateChanged(auth, async (user) => {

        activeUser = user;

        clearUserPurchases.clear();

        if (user) {
            await syncUserPurchases(user.uid);
        }

        await fetchResourceMetadataCollection();
    });


    /* =========================================================
       LOAD USER PURCHASES
       ========================================================= */

    async function syncUserPurchases(uid) {

        try {

            const querySnapshot =
                await getDocs(collection(db, "purchases"));

            querySnapshot.forEach((purchaseDoc) => {

                if (purchaseDoc.id.startsWith(uid + "_")) {

                    const targetResourceId =
                        purchaseDoc.id.replace(uid + "_", "");

                    clearUserPurchases.add(targetResourceId);
                }

            });

        } catch (error) {

            console.error(
                "Error mapping ownership details:",
                error
            );
        }
    }


    /* =========================================================
       LOAD RESOURCE CATALOGUE
       ========================================================= */

    async function fetchResourceMetadataCollection() {

        try {

            const querySnapshot =
                await getDocs(collection(db, "resources"));

            masterResources = [];

            querySnapshot.forEach((resourceDoc) => {

                masterResources.push({
                    id: resourceDoc.id,
                    ...resourceDoc.data()
                });

            });

            applyActiveFiltersFilterPipeline();

        } catch (error) {

            console.error(
                "Error collecting system catalog structures:",
                error
            );

            document.getElementById("resourceGrid").innerHTML = `
                <div class="flexi-resource-empty">
                    <strong>Unable to load resources</strong>
                    Please check your internet connection and try again.
                </div>
            `;
        }
    }


    /* =========================================================
       FILTER PIPELINE
       ========================================================= */

    function applyActiveFiltersFilterPipeline() {

        const searchInput =
            document.getElementById("searchInput");

        const categoryFilter =
            document.getElementById("categoryFilter");

        const examFilter =
            document.getElementById("examFilter");

        const typeFilter =
            document.getElementById("typeFilter");


        const searchVal =
            searchInput.value.trim().toLowerCase();

        const catVal =
            categoryFilter.value;

        const examVal =
            examFilter.value;

        const typeVal =
            typeFilter.value;


        const trackingResults =
            masterResources.filter((res) => {

                const title =
                    String(res.title || "").toLowerCase();

                const subject =
                    String(res.subject || "").toLowerCase();

                const description =
                    String(res.description || "").toLowerCase();


                const matchText =
                    !searchVal ||
                    title.includes(searchVal) ||
                    subject.includes(searchVal) ||
                    description.includes(searchVal);


                const matchCat =
                    !catVal ||
                    res.category === catVal;


                const matchExam =
                    !examVal ||
                    res.examType === examVal;


                const matchType =
                    !typeVal ||
                    (
                        typeVal === "free"
                            ? !res.isPremium
                            : res.isPremium
                    );


                return (
                    matchText &&
                    matchCat &&
                    matchExam &&
                    matchType
                );

            });


        renderInterfaceGrid(trackingResults);
    }


    /* =========================================================
       ESCAPE HTML
       Prevent resource metadata from injecting markup
       ========================================================= */

    function escapeHTML(value) {

        return String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }


    /* =========================================================
       RENDER RESOURCE GRID
       ========================================================= */

    function renderInterfaceGrid(items) {

        const grid =
            document.getElementById("resourceGrid");

        grid.innerHTML = "";


        if (items.length === 0) {

            grid.innerHTML = `
                <div class="flexi-resource-empty">
                    <strong>No educational resources found</strong>
                    Try changing your search or filter options.
                </div>
            `;

            return;
        }


        items.forEach((res) => {

            const isOwned =
                !res.isPremium ||
                clearUserPurchases.has(res.id);


            const card =
                document.createElement("article");

            card.className =
                "flexi-resource-card";


            const safeTitle =
                escapeHTML(res.title || "Educational Resource");

            const safeSubject =
                escapeHTML(
                    res.subject || "General Studies"
                );

            const safeExam =
                escapeHTML(
                    res.examType || "Exam"
                );

            const safeYear =
                escapeHTML(
                    res.year || "General"
                );


            const thumbnailElement =
                res.thumbnailUrl

                    ? `
                        <img
                            src="${escapeHTML(res.thumbnailUrl)}"
                            alt="${safeTitle}"
                            loading="lazy"
                        >
                    `

                    : `
                        <div
                            class="flexi-resource-thumbnail-placeholder"
                            aria-hidden="true"
                        >
                            PDF
                        </div>
                    `;


            let pricingDisplayHTML;

            if (res.isPremium && !isOwned) {

                const numericPrice =
                    parseInt(res.priceNGN, 10) || 0;

                pricingDisplayHTML = `
                    <span class="flexi-resource-price">
                        ₦${numericPrice.toLocaleString()}
                    </span>
                `;

            } else {

                pricingDisplayHTML = `
                    <span class="flexi-resource-free-status">
                        ${res.isPremium ? "Unlocked" : "Free"}
                    </span>
                `;
            }


            const executionBtnHTML =
                isOwned

                    ? `
                        <button
                            type="button"
                            class="flexi-resource-action flexi-resource-download"
                            data-id="${escapeHTML(res.id)}"
                            id="btn-${escapeHTML(res.id)}"
                        >
                            Download
                        </button>
                    `

                    : `
                        <button
                            type="button"
                            class="flexi-resource-action flexi-resource-unlock"
                            data-id="${escapeHTML(res.id)}"
                            id="btn-${escapeHTML(res.id)}"
                        >
                            Unlock
                        </button>
                    `;


            card.innerHTML = `
                <div class="flexi-resource-thumbnail">

                    ${thumbnailElement}

                    <div class="flexi-resource-badges">
                        <span class="flexi-resource-badge flexi-resource-badge-exam">
                            ${safeExam}
                        </span>

                        <span class="flexi-resource-badge flexi-resource-badge-year">
                            ${safeYear}
                        </span>
                    </div>

                </div>

                <div class="flexi-resource-card-body">

                    <span class="flexi-resource-subject">
                        ${safeSubject}
                    </span>

                    <h2 class="flexi-resource-title">
                        ${safeTitle}
                    </h2>

                    <div class="flexi-resource-card-actions">

                        ${pricingDisplayHTML}

                        ${executionBtnHTML}

                    </div>

                </div>
            `;


            grid.appendChild(card);


            const actionBtn =
                card.querySelector(
                    `#btn-${CSS.escape(res.id)}`
                );


            if (actionBtn) {

                actionBtn.addEventListener(
                    "click",
                    () => {

                        if (isOwned) {

                            executeBackgroundDropboxFetchStream(
                                res.id,
                                res.title
                            );

                        } else {

                            triggerPaystackInlineGateway(res);
                        }

                    }
                );
            }

        });
    }


    /* =========================================================
       DROPBOX URL NORMALIZATION
       ========================================================= */

    function normalizeDropboxStreamMapping(url) {

        if (!url) {
            return "";
        }

        if (url.includes("dl=0")) {
            return url.replace("dl=0", "dl=1");
        }

        if (url.includes("dl=1")) {
            return url;
        }

        return url.includes("?")
            ? `${url}&dl=1`
            : `${url}?dl=1`;
    }


    /* =========================================================
       DOWNLOAD OWNED RESOURCE
       ========================================================= */

    async function executeBackgroundDropboxFetchStream(
        id,
        title
    ) {

        const targetBtn =
            document.getElementById(
                `btn-${id}`
            );


        if (!targetBtn) {
            return;
        }


        targetBtn.disabled = true;
        targetBtn.innerText = "Streaming...";


        try {

            const targetRef =
                doc(
                    db,
                    "resource_links",
                    id
                );


            const querySnap =
                await getDoc(targetRef);


            if (querySnap.exists()) {

                const rawUrl =
                    querySnap.data().dropboxSecureUrl;


                const clearUrl =
                    normalizeDropboxStreamMapping(
                        rawUrl
                    );


                if (!clearUrl) {

                    throw new Error(
                        "Resource download URL is unavailable."
                    );
                }


                const hiddenAnchor =
                    document.createElement("a");


                hiddenAnchor.href =
                    clearUrl;


                hiddenAnchor.setAttribute(
                    "download",
                    `${title || "resource"}.pdf`
                );


                hiddenAnchor.target = "_blank";
                hiddenAnchor.rel = "noopener";


                document.body.appendChild(
                    hiddenAnchor
                );


                hiddenAnchor.click();


                document.body.removeChild(
                    hiddenAnchor
                );

            } else {

                alert(
                    "The resource download link could not be found."
                );
            }


        } catch (err) {

            console.error(
                "Link translation failure encountered:",
                err
            );

            alert(
                "Could not initialize the resource download."
            );

        } finally {

            targetBtn.disabled = false;
            targetBtn.innerText = "Download";
        }
    }


    /* =========================================================
       PAYSTACK PAYMENT
       ========================================================= */

    function triggerPaystackInlineGateway(resource) {

        if (!activeUser) {

            alert(
                "Please authenticate inside the Flexi student account dashboard to purchase items."
            );

            return;
        }


        const targetBtn =
            document.getElementById(
                `btn-${resource.id}`
            );


        if (!targetBtn) {
            return;
        }


        targetBtn.disabled = true;
        targetBtn.innerText = "Loading...";


        if (
            typeof window.PaystackPop !== "function"
        ) {

            alert(
                "Paystack payment gateway is still loading. Please check your internet connection and try again."
            );

            targetBtn.disabled = false;
            targetBtn.innerText = "Unlock";

            return;
        }


        const price =
            parseInt(resource.priceNGN, 10);


        if (
            !Number.isFinite(price) ||
            price <= 0
        ) {

            alert(
                "This resource does not have a valid purchase price."
            );

            targetBtn.disabled = false;
            targetBtn.innerText = "Unlock";

            return;
        }


        const popup =
            new window.PaystackPop();


        popup.newTransaction({

            key:
                "pk_live_bb0c7b83bfdbd0c9990e2c7f7fd73a86d4821a00",

            email:
                activeUser.email,

            amount:
                price * 100,

            currency:
                "NGN",

            reference:
                `flexi_${Date.now()}_${Math.floor(Math.random() * 1000)}`,

            onSuccess:
                async (transaction) => {

                    try {

                        const transactionTrackingKey =
                            `${activeUser.uid}_${resource.id}`;


                        await setDoc(
                            doc(
                                db,
                                "purchases",
                                transactionTrackingKey
                            ),
                            {
                                userId:
                                    activeUser.uid,

                                resourceId:
                                    resource.id,

                                referenceCode:
                                    transaction.reference,

                                settledAt:
                                    new Date().toISOString()
                            }
                        );


                        clearUserPurchases.add(
                            resource.id
                        );


                        alert(
                            "Resource unlocked successfully!"
                        );


                        applyActiveFiltersFilterPipeline();


                    } catch (err) {

                        console.error(
                            "Internal processing fault engine record:",
                            err
                        );

                        alert(
                            "Payment was successful, but there was an error recording your resource access. Please contact support."
                        );

                    } finally {

                        targetBtn.disabled = false;
                        targetBtn.innerText = "Download";
                    }
                },


            onCancel:
                () => {

                    targetBtn.disabled = false;
                    targetBtn.innerText = "Unlock";

                    alert(
                        "Checkout process was cancelled."
                    );
                }

        });
    }


    /* =========================================================
       FILTER EVENTS
       ========================================================= */

    document
        .getElementById("searchInput")
        .addEventListener(
            "input",
            applyActiveFiltersFilterPipeline
        );


    document
        .getElementById("categoryFilter")
        .addEventListener(
            "change",
            applyActiveFiltersFilterPipeline
        );


    document
        .getElementById("examFilter")
        .addEventListener(
            "change",
            applyActiveFiltersFilterPipeline
        );


    document
        .getElementById("typeFilter")
        .addEventListener(
            "change",
            applyActiveFiltersFilterPipeline
        );

</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
