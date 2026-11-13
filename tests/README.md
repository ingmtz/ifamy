# Capture regression checks

Run `php tests/capture-flow.php` and `node tests/capture-flow.js`.

These checks do not bootstrap CodeIgniter, connect to MySQL, or mutate inventory. The PHP harness invokes the actual HU/Batch controller methods using a fake DB/session. The Node harness exercises actual inline view functions against stubbed DOM/AJAX, including confirmation and pending-request guards.

Covered: closed-box quantity preservation; open-box confirmation waiting for quantity; actual quantities including 1 and decimals; invalid/non-finite quantities; malformed state; missing/used HU or batch; invalid location; failed/no-op update; repeated request; valid JSON on all tested branches; scan blocking during request/confirmation. Full rendered-script syntax is also checked.

Tag capture was reviewed but is unchanged: it uses `!openbox_flag` in its quantity assignment and does not exhibit the HU/Batch confirmation-to-quantity copy.

Limitations: fake DB does not prove real database concurrency or driver error handling; no real camera/browser or live inventory capture is exercised. Deployment requires PHP lint, backup, and matching deployed hashes. A live operator capture remains the end-to-end confirmation.
