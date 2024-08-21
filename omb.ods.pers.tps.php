<?php
/**
 * PREPARE DATABASE
 */
$dbhost ='localhost';
$dbuser ='root';
$dbpass ='123';
$dbdsn  = "mysql:host={$dbhost}";
$dbtb   = 'ods_db.ploting_tps_omb_oke';

try {
    $pdo = new PDO($dbdsn, $dbuser, $dbpass);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}

/** 
 * GENERATE SQL SINTAX & PREPARE DB
 */
$sql  = "select * from $dbtb";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$show = $stmt->fetchAll(PDO::FETCH_ASSOC);
$personel=[];

/** 
 * INSERT TB TPS PERSONEL
 */
$sql  = "insert into db_ops.tps_personel (id,nama,pangkat,nrp,jabatan,hp,tps,akses) values (:id,:nama,:pangkat,:nrp,:jabatan,:hp,:tps,:akses) ";
$insert = $pdo->prepare($sql);

foreach($show as $key => $val){
    if($key > 0){
        $tps=[];
        foreach(explode(',',$val['D']) as $sr){
            $rng = explode('-',$sr);
            if(count($rng) == 2){
                foreach(range(abs(trim($rng[0])),abs(trim($rng[1]))) as $rg ){
                    $tps[] = $rg;
                }
            }else{
                $tps[] = abs(trim($sr));
            }
        }

        // PARSE TPS
        $tps = (string) implode(',',$tps);
        $chk = $pdo->prepare("select id from db_ops.tps_data where area='{$val['B']}' and nomor in ($tps)");
        $chk->execute();
        $tps_id = [];
        foreach ($chk->fetchAll(PDO::FETCH_ASSOC) as $key) {
            $tps_id[] = $key['id'];
        }
        $tps_id = (string) implode(',',$tps_id);
        // var_dump($tps_id);die();

        $pers = explode(';',preg_replace('/;+/',';',$val['C']));


        // PARSE PANGKAT
        $pgkt_str = preg_replace('/[^A-Z]/','',$pers[0]);
        $chk = $pdo->prepare("select gid from db_groups.polri_options where groups='opt_pangkat' and item='$pgkt_str' limit 1");
        $chk->execute();
        $pgkt_id = $chk->fetchAll(PDO::FETCH_ASSOC);

        $scheme = [
            ':id' => abs($val['A']),
            ':nama' => trim($pers[1]),
            ':pangkat' => isset($pgkt_id[0]['gid']) ? $pgkt_id[0]['gid'] : '', 
            ':nrp' => preg_replace('/[^0-9]/','',$pers[0]), 
            ':jabatan' => trim($pers[2]),
            ':hp' => preg_replace('/[^0-9]/','',$pers[3]),
            ':tps' => $tps_id,
            ':akses' => 2
        ];
        
        $insert->execute($scheme);

        $personel[] = $scheme;
        
        
    }
}
header("Content-Type:text/json");
print json_encode($personel,JSON_PRETTY_PRINT);

// echo $sql;
// unset($data[0]);
// $trans=[];

// foreach ($data as $k=>$v) { 
//     $fetch = [];
//     foreach ($v as $l => $k) {
//         $fetch[$keys[$l]] = is_numeric($k) ? abs($k) : $k;
//     }
//     /**
//      * EXPORT TO DATABASE
//      */
//     // $stmt->execute($fetch);
//     $trans[]= $fetch;
// } 

// $data = json_encode($trans,JSON_PRETTY_PRINT);
// // var_dump($data);
// header("Content-Type:text/json");
// print $data;