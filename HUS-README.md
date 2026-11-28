# PITS-HUS — standalone variant
Branch `pits/hus`, based on client `weidmann3` at24afb9ea535f4649946464819df9446684978209. Do not merge the variant's routes/config into the existing PITS deployment. The other applications remain independent.

## Runtime
PHP7.4 / CodeIgniter3.1.10 / MySQL8. HUS adapter reuses `hus`, `masterdata`, `perpetuo`, `locations`, `profiles`, `users` and `rawtype`; there is no TAG-based report source. Existing Spout/PHPExcel readers/writers retained. Legacy endpoints are unreachable through an explicit route allowlist, including direct AJAX requests.

Deploy only `application/`, `system/`, `assets/hus/`, `index.php` and `.htaccess` into a NEW isolated docroot. Never deploy tests, dumps, SQL, archives, `.git` or runtime credentials to web-accessible paths. The paths/base URL below describe the current review installation, not a generic public hosting requirement.

Runtime JSON is outside every application docroot, at `/var/www/.pits-private/hus/config.json` in the container (override CLI with `PITS_HUS_CONFIG`). It contains `host`, `database`, `sql_user`, `sql_password`, `encryption_key`; no real values belong in Git. Database must match the HUS namespace, never `pits` or `pits_medica`. Restrict the SQL principal to ONLY its HUS database. Session files and logs are under the same private directory in separate subdirectories; readable/writable only by the PHP runtime account. Dedicated secure HttpOnly cookie scoped to `/PITS-HUS/` and CSRF enabled.

`database/hus-001.sql` is a one-time migration for a NEW COPY of the existing schema. Review the destination first. It introduces a unique HU capture header separately from the many detail rows; no UNIQUE on detail `hus.tag`. It converts `rawdata` to InnoDB only in the copy. Existing legacy Used captures are initialized separately during preparation; no source writes. Keep captured detail rows during reimport, including all multipartida rows.

Apply `database/hus-002.sql` after `hus-001.sql` in the isolated HUS schema. It adds `locations.area_description`. For the existing WAMP development database, set `PITS_HUS_CONFIG` to the private local HUS runtime JSON and run `php database/migrate-local.php`. Pass the Locations XLSX as the first argument to populate descriptions for existing A–E matches. The runner verifies the target and schema. For a separate QA schema, apply the SQL once through that environment's schema setup before running integration tests.

## Functional surfaces
- Capture: location + HU saves automatically. All detail rows are committed atomically. Duplicate HUs are rejected and an unconfirmed save can be checked by identifier. A location repeated across warehouses is resolved from the imported HU warehouse when unambiguous; ambiguous source data is rejected explicitly, not guessed.
- Progress: one HU globally; grouped warehouse/area summary using the imported description from the Locations catalog plus a complete unique-HU detail table and second XLSX sheet with item descriptions.
- Variation groups Used HU quantities by item and includes all valid capture items. The report shows one Unit Price and omits Type. Display and Excel use the same result builder. The report computes totals from unrounded values and rounds up for display and Excel, including negative values.
- Total Capture: complete captured detail. Administrator-only quantity/item corrections with reason and atomic before/after history; stale edits rejected.
- Audit: storage/location/HU content snapshot, Yes/No and notes, independent from inventory. A changed snapshot must be inspected again.
- Type Raw Variation, operation logs and correction history use HUS data. New capture events contain the original detail rows and the logs workbook exports them on a separate sheet. Raw-type rows are deduplicated per item to prevent multiplying quantities; multiple raw-type labels stay grouped as one combined label.
- Profiles unchanged:1Analyst (capture/reports/audit),2Data Capture (capture only),3Administrator (administrative functions/corrections). No location selector at login; landing Variation for1/3 and capture for2. Server-side enforcement does not trust AJAX headers.

## XLSX import contracts
Inventory keeps original positions: C HU, D item, E description, F batch (if present), G quantity, H UOM, Q warehouse, R location, T unit price. V and W provide optional area and area description. Use the active sheet with a header in row 1. The supported customer export can also include its two extra headings in rows 2 and 3. Keep identifiers as text to preserve leading zeros and avoid spreadsheet numeric truncation. Material description/price/UOM must be consistent for the same item; conflicting files fail validation rather than multiply joined rows.
Rows without HU remain reference inventory (masterdata/perpetuo), matching the existing importer; they do not become capturable HU units. Missing warehouse/area metadata is not fabricated.

Locations uses A–E for Plant, Area, Warehouse, Location and AbsEntry. Optional F supplies the area description shown in Capture Progress. Type Raw uses A–D for Material, Material2, Type Raw and General. Keep those columns in the layout. Individual rows can leave AbsEntry or General blank. The customer export's trailing area summary in C and F is skipped when its entries match areas in the catalog. When a location exists in multiple warehouses, capture resolves the warehouse from the HU when possible. The importer validates the full XLSX before starting the transaction. It accepts files up to 20 MB and rejects imports that omit an already captured HU.

## Meaningful verification

`php tests/hus_import.php` exercises the XLSX parser with synthetic workbooks and does not require a database.
`tests/hus_integration.php` is CLI-only and refuses any database other than the disposable `pits_dev_hus_qa_20261121`. It loads the real CI database driver and production model, using a minimal CI_Model host. Set `PITS_HUS_CONFIG` to a private QA-only JSON. Modes: seed, race (launch two processes), failure (QA-only failure trigger required), basic, import (synthetic XLSX fixtures required), global. Never seed a live or review database.

`tests/hus_web_checks.cjs` uses Playwright with separate browser contexts for each test profile. Provide `PITS_TEST_ACCOUNTS` (protected JSON with HUS.test_accounts[]), optionally `PITS_HUS_URL` and `PITS_TEST_OUTPUT`. It checks real logins/landing, role restrictions, AJAX legacy routes, CSRF, cookie isolation, report rendering, XLSX downloads, desktop/mobile screenshots and logout. Outputs contain copied customer data and must remain private/outside Git. Playwright is the fallback used because the agent-browser CLI was unavailable.

Additional HTTP integration in the operator's private task workspace exercised real multipart import, concurrent capture, audit and corrections against the disposable QA DB. Mid-capture, mid-import and history-write faults were injected ONLY in QA to prove rollback, including persistence of capture rejection logs. Restore the review configuration after any temporary QA binding; current review must use `pits_dev_hus_20261121`, not QA. No QA triggers are installed in review or source.

## Review versus final delivery
Current HUS link is a review environment with a copy of existing data, not an empty productive inventory. Inherited users are disabled only on copies; three dedicated test users exist. Credentials are stored privately, never in this repository. Arturo reviews BEFORE any client delivery. Actual new customer spreadsheets and user acceptance remain pending. TAGS/BATCH are separate follow-on variants.

For final go-live prepare a NEW HUS database empty of inventory, captures and test audit/history/events, retaining agreed users/config/catalogs as appropriate. Do not clear the current source or delete any real operations to reset tests. Hosting/guarantee dates begin only at the agreed operation/delivery milestone, not during review.

Rollback: disable only the new HUS route or restore its reviewed release; preserve its database and any new operations. Never point HUS back at PITS/Médica. Do not restore an old data snapshot over valid new captures without reconciliation. Source code and pre-QA DB backups are protected outside docroots; code snapshot archive and DB restore were verified during preparation.
