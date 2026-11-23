# Server-side PHP configuration

The video page and the two PIN-purchase flows now use PHP on the web server to read protected Firestore data and process checkout.

## Required server environment variables

Set these in the hosting provider's server/container environment. Do not put either value in PHP source, JavaScript, GitHub, or a public environment file.

- FIREBASE_SERVICE_ACCOUNT_JSON: the complete JSON contents of a Firebase service-account key for the waec2026jamb2027 Firebase project. The service account needs permission to read the video, store, pin, and study-content collections, and to update inventory and write sales/alerts. A Cloud Datastore / Firestore data role with the necessary permissions is required.
- PAYSTACK_SECRET_KEY: the Paystack secret key for the same live/test environment as the checkout account. Only the server uses it. The browser never receives this key.

The PHP runtime must have OpenSSL enabled and PHP URL stream access (allow_url_fopen) enabled. The supplied Dockerfile uses PHP 8.2 Apache; the standard image includes OpenSSL.

## Routes

- /videos.php (also available through /video.php): renders published video lessons from Firestore on the server. Existing Firebase client-side comment/reaction enhancements remain progressive enhancements.
- /purchase.php: server-initializes Paystack checkout, verifies the reference with Paystack, then allocates an available WAEC/NECO card in a Firestore transaction.
- /prep.php: server-initializes subject PIN checkout, verifies the payment before revealing the subject PIN, and validates PINs on the server before returning study content.
- /purchase.html and /prep.html: permanently redirect to the PHP routes.

## Firestore security

The server uses a service-account credential, which bypasses Firestore Security Rules. Keep that credential private. Client applications must not be able to read or write the server-created collections below:

- scratch_card_sales
- expo_pin_sales
- payment_issues

Add explicit deny rules for those collections to the existing Firestore rules and deploy the rules. Firestore rules are additive: a narrow deny does not cancel a broader matching allow, so adjust any overlapping broad allow rule. Do not replace the existing rules wholesale without reviewing the other website features. The browser should not be allowed to read card PIN inventory, mark documents in pins as sold, or read sale records. Keep administrative access limited to the existing trusted admin authorization model.

## Before taking live payments

1. Configure both environment variables on the actual PHP host/container.
2. Confirm the service-account project is waec2026jamb2027 and its access is limited to the required project.
3. Confirm the Paystack secret key belongs to the intended live account.
4. Test one small controlled transaction in Paystack test mode with a test inventory record.
5. Confirm the PIN is revealed only after the server verifies the Paystack reference, and that the corresponding inventory record changes to sold.
6. Test an invalid reference, a cancelled payment, an already-used reference, and an empty-stock case.

A payment that Paystack confirms but the inventory cannot allocate is recorded in payment_issues for manual support review. The server does not silently pretend that a PIN was delivered.
