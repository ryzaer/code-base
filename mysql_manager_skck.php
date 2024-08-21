<?php
$time = microtime(true);
include_once 'autobase.php';
use \Manage\Data\skck as sync_skck;

// check if site is fine 
if(\__fn::get_site(200, 'http://api.resptk.org/response.json')){
    // set database values
    date_default_timezone_set('asia/pontianak');
    $dbident = 'dbident';
    $dbskck  = 'dbserver_skck';
    $dbkrim  = 'dbident';
    $dbfile  = 'D:/.ssh/attachment_skck/'.date('Ym'); 
    is_dir($dbfile) || mkdir($dbfile,0775,true);
    // $db = \Manage\Data\mysql::open('ident','in4fi5',$dbskck,'128.199.111.250');
    //$db = \Manage\Data\mysql::open('root','123',$dbskck);

    // $wd = new \Indo\wilayah();
    // $wil = $wd->get(); // sampai disini
    
    sync_skck::db('ident','in4fi5',$dbskck,'66.42.62.101');    
    //sync_skck::db('root','123',$dbskck);
    sync_skck::move_blob_to($dbfile,['root','123','db_skck_data']);
   

    $var_skck_data = [ 
        "biodata",     
        "nik", 
        "nama",         
        "alias",        
        "gelar",
        "tpt_lahir",
        "tgl_lahir",
        "gender",
        "agama",
        "alamat",
        "kerjaan",
        "pendidikan",
        "telp",
        "imigrasi",
        "sinyal",
        "data_ortu",
        "data_sdr",
        "data_kel",
        "tgl_update",
        "catatan",
        "nama_ayah",
        "sinyal",
    ];
    
    $var_krim_a = [ 
    ];
    $var_skck_a = [ 
    ];
    $var_krim_b = [ 
    ];

}else{
    echo '<h3 style="color:red">connection lost</h3><meta http-equiv=\"refresh\" content=\"1800\">';
}
$time = number_format((microtime(true) - $time),5);
print "<br><i>execution time : $time</i>";