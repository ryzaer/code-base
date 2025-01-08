<?php 
namespace Manage;

// php7.2 >=

class Archives {

// $z = new ZipArchive();
// if ($z->open('files/files.zip', ZipArchive::CREATE)){
//     $string = $z->getFromName("test1.txt");
//     echo $string;
// }
// ob_flush();
// ob_get_clean();
// $zip = new ZipArchive();
// if ($zip->open('files/test1.zip', ZipArchive::CREATE)) {
//     # Setting password here
//     $zip->setPassword('12345');
//     # Adding some files to zip
//     $zip->addFile('test1.txt');
//     $zip->setEncryptionName('test1.txt', ZipArchive::EM_AES_256);
//     # Closing instance of zip object
//     //$zip->close();
//     exit("Done! Your zip is ready!");
// } else {
//     exit("Whoops:( Failed to create zip.");
// }
// 
// 
// $zip->open('files/test.zip', ZipArchive::CREATE);
// $zip->addFromString('test.txt', 'file content goes here');
// $zip->setEncryptionName('test.txt', ZipArchive::EM_AES_256, 'passw0rd');
// $zip->close();
// 
// $zip = ZipArchive::CLOSE;
// $zip = null;

// function moveToZip($source, $destination){
//     chmod($source, 0755);
//     if(createZip($source, $destination))
//         unlink($source);
// }
	private $create = false,
		    $saveTo = null,
		    $addPwd = null,
		    $newPwd = null;

	private function checkZip($file=null){
		$status = false;
		if($file && is_file($file)){
			$status = true;
		}
		if(!$status){
			echo "Nothing found file of $file";
		}
		return $status;
	}

	private function is_encrypted($zip_file = null) {
		$status = true;
		// open the file
		$zip = zip_open($zip_file);
		if (is_resource($zip)) {// file opened OK
			// try read of zip file
			while ( $zip_entry = zip_read($zip)){
				if (zip_entry_open($zip, $zip_entry))
					//couldn't read inside, so passworded
					if(zip_entry_read($zip_entry))
						$status = false;
				zip_entry_close($zip_entry);
			}	
		} 
		zip_close($zip);
		return $status;
	}

	public function createZip($source, $destination=null) {
		$status = false;
		$this->create = true;

		if(is_callable($destination)){        
			call_user_func($destination,$this);
		}else{
			$this->saveTo = $destination;
		}

		$encrypted = false;
		if(is_file($source)){
			$encrypted = $this->is_encrypted($source);
		}

		$zip = new \ZipArchive();
		$res = $zip->open($this->saveTo, \ZipArchive::CREATE);

		if($encrypted && $this->addPwd){
			$zip->setPassword($this->addPwd);
		}
		
		if($res){
			$encrypt = [];
	
			if(is_string($this->create)){
				$encrypt[] = "info.json";
				$zip->addFromString("info.json", $this->create);
			}
	
			if (is_dir($source)) {
				$iterator = new \RecursiveDirectoryIterator($source);
				// skip dot files while iterating 
				$iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
				foreach ($files as $file) {
					$paths = substr($file->getRealPath(), strlen(realpath($source)) + 1);
					//  still working bcoz only necessary for directories that will REMAIN empty 
					if ($file->isDir()) 
						$zip->addEmptyDir($paths);
					
					if($file->isFile()) 
						$zip->addFromString($paths, file_get_contents($file));
					
					$encrypt[] = $paths;
				}
				
			} 

			else if (is_file($source)) {
				$zip->addFromString($source, file_get_contents($source));
				$encrypt[] = $source;				
			} 			
			
			if(!$encrypted && $this->addPwd)
				foreach ($encrypt as $path) {
					$zip->setEncryptionName($path, \ZipArchive::EM_AES_256, $this->addPwd);
				}
		}
		$this->create = false;
		$this->saveTo = null;
		$this->addPwd = null;
		$zip->close();
		return $status ;
	}

	public function password($addPwd){
		if(is_bool($this->create) && $this->create){
			$this->addPwd = $addPwd;
		}else{
			$this->newPwd = $addPwd;
			return $this;
		}
	}

	public function export($saveTo){
		if(is_bool($this->create) && $this->create)
			$this->saveTo = $saveTo;
	}

	public function info($format=[]){
		if(is_bool($this->create) && $this->create){
			$arr_text=[];
			foreach ([
				"id" => "",
				"title" => "",
				"author" => "",
				"production" => "",
				"description" => "",
				"category" => "",
				"cast" => "",
				"genre" => "",
				"tags" => "",
			] as $key => $value) {			
				if(isset($format[$key]) && $format[$key]){
					$arr_text[$key] = $format[$key];
				}else{
					$arr_text[$key] = $value;
				}
			}
			$this->create = json_encode($arr_text, JSON_PRETTY_PRINT);
		}
	}

	function encryptZip($source=null,$pass=null){
		
		$status = false;
		$newPwd = false;
		if (is_string($this->newPwd) && $this->newPwd){
			$newPwd = true;
		}
		
		if ($newPwd && is_file($source)) {
			$tmp_dir = __DIR__ .'/'. md5(microtime(true));
			$cur_pwd = true;
			$status  = true;
			if($this->is_encrypted($source)){
				if(!$pass && !is_string($pass)){
					echo "Current Password Not Set!";
					$cur_pwd = false;
				}
			}else{
				$pass = null;
			}

			if($cur_pwd && $newPwd){				
				if($this->extractZip($source,$tmp_dir,$pass))
					$this->addPwd = $this->newPwd; 
					$this->createZip($tmp_dir,$source);
					\__fn::rm($tmp_dir);
			}			
		}

		if(!$newPwd){
			echo "New Password not set!";
		}

		$this->newPwd = null;
		return $status;
	}

	function openZip($file,$pass=null){
		// still developing 
		$check = false;
		$cinfo = "Zip file not exixst!";
		if($this->checkZip($file)){
			$check = true;
		}
		$cpass = true;
		if($check && $this->is_encrypted($file)){
			if(!$pass)
				$cpass = false;
				$cinfo = "Zip Encrypted!";
		}

		if($check && $cpass){
			$zip = new \ZipArchive(); 
			$ext = new \finfo(FILEINFO_MIME_TYPE);
			$zip->open($file);
			if($pass){
				$zip->setPassword($pass);
			}
			for( $i = 0; $i < $zip->numFiles; $i++ ){ 
				$stat = $zip->statIndex( $i ); 
				var_dump($stat);
				//sampai disini utk membuka file
				$file = $zip->getFromName($stat['name']);
				if(preg_match('/\.(jp(e?)g|png)/',$stat['name'])){
					echo "<div><img style=\"width:300px\" src=\"data:".$ext->buffer($file).";base64,".base64_encode($file)."\"></div>";
					
				}

				// else{
				// 	if($file)
				// 		echo "<p>". $file."</p>";
					            
				// }
				echo "mime_type=".$ext->buffer($file).'; filename='.$stat['name'] ."<br>";
					
				// print_r( basename( $stat['name'] ) . PHP_EOL ."<br>"); 
			}
			
			$zip->close();
			$cinfo = null;
		}

		echo $cinfo;
		
	}	

	public function extractZip($file,$to="",$pass=null){
		$success = false;
		if(file_exists($file)){
			$za = new \ZipArchive(); 		
			$za->open($file); 
			if($pass){
				$za->setPassword($pass);
			}
			$za->extractTo($to);
			$za->close();
			$success = true;
		}
		return $success;		
	}
}