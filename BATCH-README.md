# PITS-BATCH — primera revisión independiente
Base cliente weidmann3@24afb9. Rama pits/batch. URL https://novarum.app/PITS-BATCH/login, BD revisión pits_dev_batch_20261121. No modificar HUS/TAGS/PITS/Médica ni mezclar ramas.

## Contrato
El flujo locación → BATCH guarda automáticamente al completar la lectura. Si la respuesta se pierde, el operador puede consultar el estado. La bitácora conserva el detalle original de las nuevas capturas y lo exporta en una hoja separada.

Locación+BATCH; identidad global de lote (hus.batch, varchar100), detalle múltiple hus con HU origen en tag. Captura todas las partidas/artículos en transacción, cabecera única separada, bloqueo por lote y rechazo de recapturas incluso cambiando almacén. Estados null/vacío/Pending elegibles; Used o captura parcial previa requieren revisión, nunca recaptura. Filas sin BATCH no son unidades capturables ni captura válida de esta modalidad. Ubicación compartida se resuelve por warehouse importado solo si inequívoco.

Importación XLSX original22columnas: C HU origen, D material, E descripción, F BATCH, G cantidad, H UOM, Q almacén, R locación, T precio, V área. W descripción área opcional. NO usar layout TAGS21columnas. HU vacío no invalida un lote BATCH; lote vacío solo referencia onhand. Identificadores texto, ceros conservados. Todas las partidas repetidas se conservan. Material/precio/descripción/UOM consistentes por artículo. Locaciones5columnas y RawType4columnas originales. El lector admite los encabezados adicionales del inventario y el resumen final de Locaciones del cliente. La carga se valida antes de reemplazar datos. Si un lote capturado se omite, cambia sus partidas originales o carece de referencia para verificarlo, se rechaza toda la reimportación.

Reportes desde hus Used con lote no vacío, agregados por artículo antes de joins; no tags. Progress cuenta lote único y conserva detalle por artículo. Variation pantalla/Excel comparten builder, UnitPrice único y sin Type; TypeRaw separado. Ceil solo presentación, también negativos, cero normalizado; códigos como strings.

Auditoría almacén/locación/lote con snapshot Sí/No/observaciones independiente. Solo Admin corrige parte/cantidad con motivo, usuario/hora/antes/después e historial atómico. Edición/auditoría stale rechazada. Analyst captura/reportes/audit; DataCapture solo captura; Admin además importaciones/logs/historial/correcciones. Login sin ubicación, Admin/Analyst Variation, DataCapture BATCH; allowlist servidor impide rutas legacy incluso AJAX y Openbox.

## Operación y seguridad
PHP7.4/CI3/MySQL8. Migración database/batch-001.sql aplicada UNA VEZ solo copia BATCH; rawdataInnoDB, hus ya InnoDB. No repetir provisión ni resetear cuentas. Config secreta fuera docroot: /var/www/.pits-private/batch/config.json, override CLI PITS_BATCH_CONFIG; namespace BD forzado. Cookie segura HttpOnly path /PITS-BATCH/, CSRF, sesiones/logs privados propios. SQL revisión solo esa BD. Deploy únicamente application/system/assets/batch/index.php/.htaccess en nuevo docroot; nunca tests/fixtures/SQL/dumps/.git.

QA desechable pits_dev_batch_qa_20261121 con usuarioSQL propio; fixtures y fallos SOLO QA. tests/batch_integration.php CLI guarda namespace (seed,race,failure,basic,global); tests/batch_web_checks.cjs navegador3roles; tests/batch_ui_flow_qa.cjs exige PITS_BATCH_QA_CONFIRM y binding QA previamente verificado. Integración HTTP y scripts operativos en expediente privado, no docroot. Binding revisión restaurado en finally y comprobado; no dejar QA atendiendo revisión. Reproducir fallos solo con triggers QA, nunca en revisión. Backups fuera de docroot /root/backups/pits-batch-20261121.

Rollback: deshabilitar solo nuevo enlace o restaurar código de release BATCH, preservar BD y capturas nuevas. No redirigir a origen/hermanas ni restaurar datos viejos sobre operaciones válidas sin conciliación.

Revisión Arturo primero; copia autorizada conservada, no inventario final vacío. Nuevos archivos cliente y aceptación pendientes. No envío cliente, no inicio hosting/garantía. Entrega25nov/inventario03dic2026.
