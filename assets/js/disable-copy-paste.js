/* Flexi site-wide clipboard restriction.
 * This is a browser-level deterrent, not a security boundary.
 * Exam and corrections screens intentionally do not load this script.
 */
(function () {
  'use strict';

  function blockClipboard(event) {
    event.preventDefault();
    if (event.stopImmediatePropagation) event.stopImmediatePropagation();
  }

  ['copy', 'cut', 'paste'].forEach(function (eventName) {
    document.addEventListener(eventName, blockClipboard, true);
  });
  document.addEventListener('keydown', function (event) {
    var key = String(event.key || '').toLowerCase();
    var modifier = event.ctrlKey || event.metaKey;

    var clipboardShortcut = modifier && ['c', 'x', 'v'].includes(key);
    var legacyCopyShortcut = event.ctrlKey && (
      (key === 'insert') ||
      (event.shiftKey && key === 'insert')
    );
    var legacyPasteShortcut = event.shiftKey && key === 'insert';

    if (clipboardShortcut || legacyCopyShortcut || legacyPasteShortcut) {
      blockClipboard(event);
    }
  }, true);
})();
