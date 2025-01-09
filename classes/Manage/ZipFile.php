<?php 
namespace Manage;

// php7.2 >=

class ZipFile {

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
//     if(create($source, $destination))
//         unlink($source);
// }
	private $create = false,
		    $saveTo = null,
		    $addPwd = null,
			$addFiles=[];

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

	private function is_zip($file) {
		$iszip = false;
		if(file_exists($file)){
			$fh = @fopen($file, "r");
			$blob = fgets($fh,5);
			fclose($fh);
			// if (strpos($blob, 'Rar') !== false) {
			// print "Looks like a Rar.\n";
			// } else
			if(preg_match('/^PK/', $blob))
				$iszip = true;
		}
		return $iszip;
	}

	public function create($source, $destination=null) {
		$status = false;
		$this->create = true;

		if(is_callable($destination)){        
			call_user_func($destination,$this);
		}else{
			if(is_string($destination))
				$this->saveTo = $destination;			
		}

		if(!$this->saveTo)
			return false;
		
		$zip = new \ZipArchive();
		// break if destination is file
		if(is_file($this->saveTo)){
			if(file_exists($this->saveTo))
				unlink($this->saveTo);				
		}

		$res = $zip->open($this->saveTo, \ZipArchive::CREATE);
		if($this->addPwd){
			$zip->setPassword($this->addPwd);
		}
		
		if($res){
			$encrypt = [];
			$status = true;
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
				
			}elseif (is_file($source)) {
				$zip->addFromString($source, file_get_contents($source));
				$encrypt[] = $source;				
			} 	

			if($this->addPwd)
				foreach ($encrypt as $path) 
					$zip->setEncryptionName($path, \ZipArchive::EM_AES_256, $this->addPwd);
			
			if($this->addFiles){
				foreach ($this->addFiles as $file) {
					$name = basename($file);
					// var_dump($name);
					$zip->addFromString($name, file_get_contents($file));
					if($this->addPwd)
						$zip->setEncryptionName($name, \ZipArchive::EM_AES_256, $this->addPwd);				
				}
			}
		}
		$this->create = false;
		$this->saveTo = null;
		$this->addPwd = null;
		$zip->close();
		return $status ;
	}

	public function password($addPwd){
		if($this->create)
			$this->addPwd = $addPwd;
	}

	public function files($addFiles){
		if($this->create){
			if(is_array($addFiles)){
				foreach ($addFiles as $file) {
					if(file_exists($file))
						$this->addFiles[] = $file;
				}
			}
			if(is_string($addFiles))
				if(file_exists($addFiles))
					$this->addFiles[] = $addFiles;
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

	function protect($source,$newPwd=null,$lstPass=null){
		
		$status = false;
		
		if(!$this->is_zip($source)){
			echo "File not is not zip!";
			return false;
		}

		if(!$lstPass && $this->is_encrypted($source)){
			echo "Set the Last Password!";
			return false;
		}		

		if($newPwd){
			$tmp_dir = __DIR__ .'/'. md5(microtime(true));
			if(!$lstPass && $this->is_encrypted($source)){
				echo "Current Password Not Set!";
				return false;
			}
			if($this->extractZip($source,$tmp_dir,$lstPass)){
				$status = true;
				unlink($source);
				$this->addPwd = $newPwd;
				$this->create($tmp_dir,$source);
				\__fn::rm($tmp_dir);
			}
		}

		return $status;
	}

	function open($file,$pass=null,$match=false){
		// still developing 
		$check = false;
		$cinfo = null;
		if(!$this->is_zip($file)){
			print "Zip file not exixst!";
			return false;
		}
		if(!$pass && $this->is_encrypted($file)){
			print "Zip Encrypted!";
			return false;
		}

		$zip = new \ZipArchive(); 
		$ext = new \finfo(FILEINFO_MIME_TYPE);
		$zip->open($file);
		if($pass){
			$zip->setPassword($pass);
		}

		$rsl=[];
		for( $i = 0; $i < $zip->numFiles; $i++ ){ 
			$stat = $zip->statIndex( $i );
			$show = true;
			if($show){
				$name = preg_replace("/\\\+/","/",$stat['name']);
				if(!$match){
					$file = $zip->getFromName($name);
					$mime = $ext->buffer($file);
					if(!preg_match('/empty/i', $mime))
					$rsl[] = [
						"name" => $name,
						"mime" => $mime,
						// "base64" => base64_encode($file),
						"size" => strlen($file)
					];
				}else{
					if($match == $name){					
						$cinfo = $zip->getFromName($name);
						// Open the file as a stream not work on 7.4 (why?)
						// $stream = $zip->getStream($name);
						// var_dump($stream);
						// if ($stream) {
						// 	// Read the file content in chunks and stream it
						// 	while (!feof($stream)) {
						// 		print fread($stream, 1024); // Read 1KB at a time
						// 	}
						// 	fclose($stream); // Close the stream
						// }	
					}				
					$show = false;
				}
			}
		}
		
		$zip->close();

		return $rsl? json_encode($rsl) : $cinfo;
		
	}	

	public function extractZip($file,$to="",$pass=null){
		$success = false;
		if($this->is_zip($file)){
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