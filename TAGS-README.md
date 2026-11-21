# PITS-TAGS — primera revisión
Base weidmann3@24afb9; rama pits/tags independiente. No desplegar a PITS, HUS ni Médica. Reutiliza patrones de infraestructura/transacción del adaptador HUS, no sus reglas de inventario.

## Semántica específica
Un marbete preasignado = una fila `tags`. Captura TAG + locación + parte + cantidad positiva. No se aceptan HU/lotes como alias de parte. Estados Blank/Pending/null disponibles; Used duplicado; otros estados rechazados. Identidad global única, con bloqueo de fila y cabecera transaccional. Locaciones compartidas requieren almacén del TAG inequívoco. Alta de rangos numéricos exclusivamente Administrator; ancho uniforme, ceros preservados, máximo10000 por operación; duplicados revierten rango entero.

Inventario XLSX: formato original SIN columna C. 21 columnas: material C, descripción D, batch E, cantidad F, UOM G, almacén P, locación Q, precio S, área U; descripción área opcional V. Batch es dato de referencia, no modo de captura. No crear TAG a partir de inventario: los marbetes son independientes. Importación reemplaza referencias en transacción, preserva tags/capturas; rechaza omitir artículo capturado. Locaciones5columnas y RawType4columnas conservadas. No almacenar archivos cargados públicamente.

Variation lee exclusivamente `tags.status=Used`, agrega por artículo antes de joins; un constructor compartido pantalla/totales/Excel, ceil solo presentación y negativos hacia infinito positivo. Progress cuenta TAG distinto; Total Capture, corrección con revisión optimista y antes/después/motivo; auditoría Sí/No con snapshot separado. Analyst captura/reportes/auditoría; Data Capture solo captura; Administrator además importación/rangos/correcciones/historial/logs. Todas las rutas legacy fallan cerrado incluso AJAX.

Cámara usa getUserMedia facingMode ideal environment y BarcodeDetector si existe; fallback cámara genérica solo ante incompatibilidad/disponibilidad y entrada manual/lector siempre. Nunca guardar automáticamente por detectar un código. Navegadores sin BarcodeDetector conservan alternativa manual/lector. Prueba física móvil pendiente, no confundir mocks con hardware real.

## Despliegue aislado
URL https://novarum.app/PITS-TAGS/login; PHP7.4/CI3/MySQL8. Solo application/system/assets/tags/index.php/.htaccess en nuevo docroot. Config JSON fuera de docroot `/var/www/.pits-private/tags/config.json` (override CLI PITS_TAGS_CONFIG); namespace de BD obligatorio pits[_dev]_tags*. Sesiones/logs privados dedicados, cookie segura HttpOnly path /PITS-TAGS/, CSRF activo. Secretos nunca en Git. Migración database/tags-001.sql SOLO copia TAGS ya provisionada; no recrear cuentas/BD. Cabeceras de los Used heredados se inicializaron preservando capturas.

## Verificación y límites
Tests de navegador en tests/, integración HTTP y evidencia en expediente de proyecto. QA desechable pits_dev_tags_qa_20261121 exclusivamente, no review. Nunca cargar fixtures/fallos en revisión. Restaurar binding revisión en finally y comprobarlo. Copia revisión pits_dev_tags_20261121 conserva26marbetes y5capturas heredadas; no inventario productivo vacío, no aceptación. Nuevos archivos cliente, cámara física y C13 final pendientes. No BATCH implementado.

Rollback: deshabilitar únicamente nuevo docroot o volver a release TAGS revisada; no redirigir a otra BD. Backup protegido /root/backups/pits-tags-20261121; copia de BD restaurada en QA. No restaurar snapshot sobre capturas válidas sin conciliación. No enviar accesos cliente antes de revisión Arturo; hosting/garantía no iniciados.
