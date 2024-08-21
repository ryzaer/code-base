<?php
include_once 'autobase.php';

$src = isset($_GET['src']) && $_GET['src'] ? preg_replace('/(^[0-9][a-z][A-Z])\s+/','',$_GET['src']): null;

$src || print '<a href="?src=riza">example query : ?src=riza</a>';

if($src){

    $sql = db\mysql::open('root','123');
    // $src = count_chars($src,3);
    $whr = [];
    foreach (explode(' ',$src) as $str) {
        $whr[] = "nama like '%$str%'";
    }
    $whr = 'where ('.implode(' and ', $whr).')';
    $cmd = <<<SQL
    select id_agt as id ,nama from std_polres.list_agt $whr
    SQL;
    print "<h3>PENCARIAN NAMA : </h3><pre>$cmd</pre>";
    $sql->exec($cmd);
    $prm = $sql->fetch();
    var_dump($prm);
    if($prm){
        
        $cmd = <<<SQL
        select
            trim(nopol) as nopol,
            trim(data_info) as data_info,
            REGEXP_REPLACE(JSON_EXTRACT(lampiran6,'\$."1"."0"'),'[\\]\\[]|"| ','') as personel
        from std_polres.sprin_data
        where find_in_set('{$prm[0]['id']}',REGEXP_REPLACE(JSON_EXTRACT(lampiran6,'\$."1"."0"'),'[\\]\\[]|"| ',''))
        limit 100
        SQL;
        print "<h3>PENCARIAN SPRIN : </h3><pre>$cmd</pre>";
        $sql->exec($cmd);
        // foreach ($variable as $key => $value) {
        //     # code...
        var_dump($sql->fetch());
    }
}

// ((select nama from std_polres.list_agt where id_agt in (REGEXP_REPLACE(lampiran,'[\\]\\[]|"','')))) fieldset