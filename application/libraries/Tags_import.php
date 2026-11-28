<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
/** Existing Weidmann positional XLSX contracts, validated completely BEFORE any writes. */
class Tags_import {
    private function text($row,$i,$limit=200) {
        $value=$row[$i]??'';
        if(!is_scalar($value))throw new DomainException('Invalid value at column '.($i+1));
        $v=trim((string)$value);
        if(mb_strlen($v)>$limit || preg_match('/[\x00-\x1f]/',$v))throw new DomainException('Invalid or oversized value at column '.($i+1));
        return $v;
    }
    private function numeric($row,$i) {
        $v=$this->text($row,$i,50);
        if(!is_numeric($v)||!is_finite((float)$v)||abs((float)$v)>1e14)throw new DomainException('Invalid quantity/price at column '.($i+1));return $v;
    }
    private function validateInventoryHeader($row) {
        $labels=array_map(function($value){
            $value=strtr(mb_strtolower(trim((string)$value)),array('á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u'));
            return trim(preg_replace('/[^a-z0-9]+/',' ',$value));
        },$row);
        $expected=array(
            2=>array('material','mat number','item code','part number','numero de parte','material number'),
            3=>array('description','material description','descripcion'),
            5=>array('qty','quantity','packed quantity','cantidad'),
            6=>array('um','u m','uom','u o m','uni medida','unidad de medida'),
            15=>array('warehouse','storage location','almacen'),
            16=>array('location','location sotorage bin','location storage bin','locacion','storage bin'),
            18=>array('cost price','unit price','unit cost','unit price usd','costo','precio unitario usd')
        );
        foreach($labels as $label) {
            if(in_array($label,array('hu','hu number','handling unit','handling unit number','exidv'),true))
                throw new DomainException('El archivo no corresponde a la plantilla de inventario de TAGS.');
        }
        foreach($expected as $column=>$allowed) {
            if(!in_array($labels[$column]??'', $allowed,true))
                throw new DomainException('Encabezado de inventario incorrecto en la columna '.chr(65+$column).'.');
        }
    }
    public function parse($file,$kind) {
        $zip=new ZipArchive();if($zip->open($file)!==true)throw new DomainException('Invalid XLSX archive.');
        $size=0;for($i=0;$i<$zip->numFiles;$i++)$size+=$zip->statIndex($i)['size'];$zip->close();
        if($size>150*1024*1024)throw new DomainException('Expanded file is too large.');
        $reader=ReaderFactory::create(Type::XLSX)->setShouldPreserveEmptyRows(true);$rows=array();$master=array();
        try {
            $reader->open($file);
            foreach($reader->getSheetIterator() as $sheet) if($sheet->isActive()) foreach($sheet->getRowIterator() as $n=>$row) {
                if($n===1) {
                    if($kind==='inventory')$this->validateInventoryHeader($row);
                    continue;
                }
                if(!array_filter($row,function($v){return $v!==null && (!is_string($v) || trim($v)!=='');}))continue;
                if(count($rows)>=100000)throw new DomainException('Maximum 100000 rows per import.');
                if($kind==='inventory') {
                    if(count($row)<19)throw new DomainException('Inventory requires Cost Price in column S. Row '.$n);
                    $r=array('material'=>$this->text($row,2,50),'material_description'=>$this->text($row,3),'batch'=>$this->text($row,4,100),'qty'=>$this->numeric($row,5),'um'=>$this->text($row,6,5),'location'=>$this->text($row,16,50),'wh'=>$this->text($row,15,50),'cost_price'=>$this->numeric($row,18),'area'=>$this->text($row,20),'area_description'=>isset($row[21])?$this->text($row,21):'');
                    if($r['material']==='')throw new DomainException('Material is required. Row '.$n);
                    $signature=json_encode(array($r['material_description'],(float)$r['cost_price'],$r['um']));
                    if(isset($master[$r['material']]) && $master[$r['material']]!==$signature)throw new DomainException('Conflicting description/price/UOM for item '.$r['material']);
                    $master[$r['material']]=$signature;
                } elseif($kind==='locations') {
                    if(count($row)<5)throw new DomainException('Locations require 5 columns.');
                    $r=array('plant'=>$this->text($row,0),'area'=>$this->text($row,1),'wh'=>$this->text($row,2),'location'=>$this->text($row,3),'plataforma'=>$this->text($row,4,20));
                    if($r['wh']==='' || $r['location']==='')throw new DomainException('Warehouse and location are required.');
                } elseif($kind==='rawtype') {
                    if(count($row)<4)throw new DomainException('Raw Type requires 4 columns.');
                    $r=array('material'=>$this->text($row,0),'material2'=>$this->text($row,1),'type_raw'=>$this->text($row,2),'general'=>$this->text($row,3));
                    if($r['material']==='')throw new DomainException('Material is required.');
                } else throw new DomainException('Unknown import type.');
                $rows[]=$r;
            }
        } catch(DomainException $e) { throw $e; }
        catch(Throwable $e) { throw new DomainException('Cannot read XLSX; verify the file and its layout.'); }
        finally { $reader->close(); }
        if(!$rows)throw new DomainException('Import is empty.');
        return $rows;
    }
}
