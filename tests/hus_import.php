<?php
if (PHP_SAPI !== 'cli') exit(1);

define('BASEPATH', dirname(__DIR__).'/system/');
define('APPPATH', dirname(__DIR__).'/application/');
require APPPATH.'libraries/Hus_import.php';
require APPPATH.'third_party/PHPExcel.php';

function workbook($path, array $rows, $sparse = false) {
    $book = new PHPExcel();
    $sheet = $book->getActiveSheet();
    foreach ($rows as $rowIndex => $row) {
        foreach ($row as $columnIndex => $value) {
            $cell = $sheet->getCellByColumnAndRow($columnIndex, $rowIndex+1);
            if ($value instanceof DateTimeInterface) {
                $cell->setValueExplicit(PHPExcel_Shared_Date::PHPToExcel($value), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $sheet->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode('yyyy-mm-dd');
            } else {
                $cell->setValueExplicit($value, is_string($value) ? PHPExcel_Cell_DataType::TYPE_STRING : PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
        }
    }
    $writer = new PHPExcel_Writer_Excel2007($book);
    $writer->save($path);
    $book->disconnectWorksheets();
    if ($sparse) {
        // Some exporters omit both the worksheet dimension and row spans.
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) throw new RuntimeException('Could not open fixture');
        $entry = 'xl/worksheets/sheet1.xml';
        $xml = preg_replace('/<dimension[^>]*\/>| spans="[^"]*"/', '', $zip->getFromName($entry));
        $zip->addFromString($entry, $xml);
        $zip->close();
    }
}

function check($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
    echo 'PASS: '.$message.PHP_EOL;
}

function rejects($parser, $path, $kind, $message) {
    try {
        $parser->parse($path, $kind);
    } catch (DomainException $e) {
        check(strpos($e->getMessage(), $message) !== false, $message);
        return;
    }
    throw new RuntimeException('Expected rejection: '.$message);
}

$path = tempnam(sys_get_temp_dir(), 'hus');
$parser = new Hus_import();
try {
    $header = array_replace(array_fill(0, 20, ''), [2=>'HU', 3=>'Mat number', 6=>'Qty', 19=>'Unit cost']);
    $export = array_replace($header, [2=>'Handling Unit', 3=>'Material', 6=>'Packed quantity', 19=>'Costo']);
    $data = array_replace(array_fill(0, 20, ''), [
        2=>'000001', 3=>'000042', 4=>'Fixture', 6=>0, 7=>'EA', 9=>new DateTime('2026-11-27'),
        16=>'WH', 17=>'0003', 19=>2.5
    ]);
    workbook($path, [['Inventory export'], $header, $export, $data]);
    $rows = $parser->parse($path, 'inventory');
    check(count($rows) === 1 && $rows[0]['hu'] === '000001' && $rows[0]['material'] === '000042'
        && $rows[0]['location'] === '0003' && $rows[0]['qty'] === '0'
        && $rows[0]['area'] === '' && $rows[0]['area_description'] === '',
        'Recognized inventory headings, date in ignored J, zero quantity and identifiers with leading zeros');

    workbook($path, [$header, ['', '', ''], array_replace($data, [6=>'invalid'])]);
    rejects($parser, $path, 'inventory', 'Invalid quantity/price at column 7');

    $locations = ['PLANT', 'AREA', 'WAREHOUSE', 'LOCATION', 'AbsEntry', 'AREA'];
    $location = ['PLANT-1', 'AREA-1', 'WH-1', '0003', '0004'];
    $summary = ['', '', 'Summary label', '', '', 'AREA-1'];
    workbook($path, [$locations, array_merge($location, ['Fixture area description']), $summary]);
    $rows = $parser->parse($path, 'locations');
    check(count($rows) === 1 && $rows[0]['location'] === '0003' && $rows[0]['plataforma'] === '0004'
        && $rows[0]['area_description'] === 'Fixture area description',
        'Locations import description from F and preserve codes while skipping the trailing summary');

    workbook($path, [$locations, $location, ['', '', 'WH-1', '', '', 'AREA-1']]);
    rejects($parser, $path, 'locations', 'Warehouse and location are required. Row 3');

    workbook($path, [$locations, $location, $summary, $location]);
    rejects($parser, $path, 'locations', 'Unexpected row after location summary. Row 4');

    $locationHeader = array_slice($locations, 0, 5);
    $locationWithoutPlatform = array_slice($location, 0, 4);
    workbook($path, [$locationHeader, $locationWithoutPlatform, $location], true);
    $rows = $parser->parse($path, 'locations');
    check(count($rows) === 2 && $rows[0]['plataforma'] === '' && $rows[1]['plataforma'] === '0004'
        && $rows[0]['area_description'] === '' && $rows[1]['area_description'] === '',
        'Locations with the original header accept a missing AbsEntry cell');

    $categoryHeader = ['Material', 'Material2', 'TYPE RAW', 'GENERAL'];
    workbook($path, [$categoryHeader, ['000042', 'M2', 'CLASE'], ['000043', 'M3', 'OTRA', 'GENERAL']], true);
    $rows = $parser->parse($path, 'rawtype');
    check(count($rows) === 2 && $rows[0]['material'] === '000042' && $rows[0]['general'] === ''
        && $rows[1]['general'] === 'GENERAL', 'Category with the original header accepts a missing General cell');

    workbook($path, [array_slice($locationHeader, 0, 4), $locationWithoutPlatform], true);
    rejects($parser, $path, 'locations', 'Locations require 5 columns.');

    workbook($path, [array_slice($categoryHeader, 0, 3), ['000042', 'M2', 'CLASE']], true);
    rejects($parser, $path, 'rawtype', 'Raw Type requires 4 columns.');
} finally {
    if (is_file($path)) unlink($path);
}
