<?php
if(PHP_SAPI!=='cli')exit(1);

// Apply the incremental HUS migration against the configured Wamp development database.
define('BASEPATH',dirname(__DIR__).'/system/');
define('APPPATH',dirname(__DIR__).'/application/');
require APPPATH.'config/database.php';
require APPPATH.'libraries/Hus_import.php';
$target=$db['default'];
if($target['hostname']!=='127.0.0.1' || $target['database']!=='pits_dev_hus_local_20261127') {
    throw new RuntimeException('Expected the local Wamp HUS development database.');
}
$locations=isset($argv[1])?(new Hus_import())->parse($argv[1],'locations'):array();
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$admin=new mysqli('127.0.0.1','root',getenv('PITS_LOCAL_SQL_ADMIN_PASSWORD')?:'',$target['database']);
$admin->set_charset('utf8mb4');
$checkColumn=function()use($admin) {
    return $admin->query("SELECT column_type AS column_type,is_nullable AS is_nullable FROM information_schema.columns
        WHERE table_schema=DATABASE() AND table_name='locations' AND column_name='area_description'")->fetch_assoc();
};
if(!$checkColumn())$admin->query(file_get_contents(__DIR__.'/hus-002.sql'));
$column=$checkColumn();
if($column['column_type']!=='varchar(200)' || $column['is_nullable']!=='YES') {
    throw new RuntimeException('Unexpected HUS descriptions schema.');
}
echo 'hus-002: locations.area_description VARCHAR(200) NULL verified.',PHP_EOL;
if(!$locations)exit(0);
$admin->begin_transaction();
try {
    $admin->query('SELECT id FROM pits_inventory_lock WHERE id=1 FOR UPDATE');
    $update=$admin->prepare("UPDATE locations SET area_description=? WHERE COALESCE(plant,'')=? AND COALESCE(area,'')=?
        AND wh=? AND location=? AND COALESCE(plataforma,'')=?");
    $changed=0;
    foreach($locations as $row) {
        $update->bind_param('ssssss',$row['area_description'],$row['plant'],$row['area'],$row['wh'],$row['location'],$row['plataforma']);
        $update->execute();
        $changed+=$update->affected_rows;
    }
    $admin->commit();
} catch(Throwable $e) {
    $admin->rollback();
    throw $e;
}
$described=$admin->query("SELECT COUNT(*) FROM locations WHERE NULLIF(area_description,'') IS NOT NULL")->fetch_row()[0];
echo "Local locations updated: $changed. Locations with an imported description: $described.",PHP_EOL;
