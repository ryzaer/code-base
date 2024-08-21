<?php
namespace Manage\Data;

class skck {

	private static $dba,$dbs;

	public static function db(...$args){
		if(!self::$dba){
			self::$dbs = $args;
			self::$dba = \db\mysql::open(...$args);
		}
	}

	public static function move_blob_to($folder,$newlogin){
		if(self::$dba){
			$var_skck_blob = [ 
				"_file_foto",
				"_file_ktp",
				"_file_kk",
				"_file_akta",
				"_file_fpc",
				"_file_skk",
			];
			
			$posts=50;
			// $posts=25;
			// if(\__fn::between_time('05:00','07:30')){
			// 	$posts=10;
			// }elseif(\__fn::between_time('07:30','14:30')){
			// 	$posts=5;
			// }elseif(\__fn::between_time('14:30','15:30')){
			// 	$posts=10;
			// }elseif(\__fn::between_time('15:30','20:30')){
			// 	$posts=15;
			// }elseif(\__fn::between_time('20:30','00:00')){
			// 	$posts=20;
			// }

			$rslt=[];	
			$reload = false;		
			foreach (self::$dba->table('base_skck_lobs')->select(null,null,$posts) as $key => $var) {
				
				$get_dir = "$folder/{$var['nik']}";
				if(!is_dir($get_dir)){
					mkdir($get_dir,755);
				}
				self::$dba = \db\mysql::open(...$newlogin);
				self::$dba->table('tb_blob')->insert([
					'uid' => $var['biodata'],
					'pin' => $var['nik'],
					'date_time' => $var['tgl_update']
				]);

				foreach ($var_skck_blob as $key_file) {
					if(isset($var[$key_file]) && $var[$key_file]){
						$img = base64_encode($var[$key_file]);
						$fnm = substr($key_file,6);
						file_put_contents("$get_dir/$fnm.jpg",$var[$key_file]);
						if($fnm == 'foto')
							echo "<img width=\"100\" src=\"data:image/jpeg;base64,$img\"/>";
					}
				}
				self::$dba = \db\mysql::open(...self::$dbs);
				self::$dba->table('base_skck_lobs')->delete(['biodata' => $var['biodata']]);
				if($key+1 == $posts)
					$reload = true;				
			}
			if($reload ){
				echo "<h3><i style=\"color:red\">RELOAD</i></h3><meta http-equiv=\"refresh\" content=\"2\">";
			}else{
				echo "<h3><i style=\"color:green\">DONE</i></h3>";
			}

			// var_dump(self::$dba);
			// self::$dba = \db\mysql::open('root','123');
			// var_dump(self::$dba);
			// self::$dba = \db\mysql::open('root','skck@2oi9',null,'192.168.1.107');
			// var_dump(self::$dba);

		}
	}
}