<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
/** Existing Weidmann positional XLSX contracts, validated completely BEFORE any writes. */
class Batch_import {
    private function text($row,$i,$limit=200) {
        $v=isset($row[$i])?trim((string)$row[$i]):'';
        if(mb_strlen($v)>$limit || preg_match('/[\x00-\x1f]/',$v))throw new DomainException('Invalid or oversized value at column '.($i+1));
        return $v;
    }
    private function numeric($row,$i) {
        $v=$this->text($row,$i,50);
        if(!is_numeric($v)||!is_finite((float)$v)||abs((float)$v)>1e14)throw new DomainException('Invalid quantity/price at column '.($i+1));return $v;
    }
    public function parse($file,$kind) {
        $zip=new ZipArchive();if($zip->open($file)!==true)throw new DomainException('Invalid XLSX archive.');
        $size=0;for($i=0;$i<$zip->numFiles;$i++)$size+=$zip->statIndex($i)['size'];$zip->close();
        if($size>150*1024*1024)throw new DomainException('Expanded file is too large.');
        $reader=ReaderFactory::create(Type::XLSX);$rows=array();$master=array();
        try {
            $reader->open($file);
            foreach($reader->getSheetIterator() as $sheet) if($sheet->isActive()) foreach($sheet->getRowIterator() as $n=>$row) {
                if($n<2)continue;
                if(!array_filter($row,function($v){return trim((string)$v)!=='';}))continue;
                if(count($rows)>=100000)throw new DomainException('Maximum 100000 rows per import.');
                if($kind==='inventory') {
                    if(count($row)<22)throw new DomainException('Inventory requires the existing 22-column layout. Row '.$n);
                    $r=array('hu'=>$this->text($row,2,20),'material'=>$this->text($row,3,50),'material_description'=>$this->text($row,4),'batch'=>$this->text($row,5,100),'qty'=>$this->numeric($row,6),'um'=>$this->text($row,7,5),'location'=>$this->text($row,17,50),'wh'=>$this->text($row,16,50),'cost_price'=>$this->numeric($row,19),'area'=>$this->text($row,21),'area_description'=>isset($row[22])?$this->text($row,22):'');
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
