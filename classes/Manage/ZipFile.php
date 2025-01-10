<?php 
namespace Manage;

// php7.2 >=

class ZipFile {

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
		// only work for image
		// video and audio still have issues
		// getStream is not supported in php 7 windows
		// in php 8 getStream is supported but not working properly
		
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
					// $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
					// $mime = \__fn::ext2mime($fileExtension);
					if(!preg_match('/empty/i', $mime))
						$rsl[] = [
							"name" => $name,
							"mime" => $mime,
							// "base64" => base64_encode($file),
							"size" => $stat['size']
						];
				}else{
					if($match==$name){					
						$cinfo = $zip->getFromName($name);
						// Open the file as a stream not work on 7.4 (why?)
						// $stream = $zip->getStream($name);
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
		
		unset($ext);
		$zip->close();
		return $rsl? json_encode($rsl) : $cinfo ;
		
	}	

	public function stream($source,$FileInZip=null,$passwd=null){
		// still not work for big file :(
		$zip = new \ZipArchive();
		if ($FileInZip && $zip->open($source)) {
			if($passwd)
				$zip->setPassword($passwd);
			
			// Locate the file inside the ZIP
			$index = $zip->locateName($FileInZip);
			// var_dump($index);
			if ($index) {

				$chunkSize = 1024 * 1024; // 1MB chunks for better performance

				// // $filePath = $zip->getFromName($FileInZip);
				// $stat = $zip->statIndex($index);
				// $fileSize = $stat['size'];
				// // Detect MIME type
				// $fileExtension = pathinfo($stat['name'], PATHINFO_EXTENSION);
				// $mimeType = \__fn::ext2mime($fileExtension);
				// $mimeType = $mimeType ? $mimeType : 'application/octet-stream';
				
				// // Start streaming headers
				// header("Content-Type: $mimeType");
				// header("Content-Disposition: inline; filename=\"{$stat['name']}\"");
				// header("Content-Length: $fileSize");

				// // Optional: Handle partial content requests (i.e., range requests)
				// $range = null;
				// if (isset($_SERVER['HTTP_RANGE'])) {
				// 	$range = str_replace('bytes=', '', $_SERVER['HTTP_RANGE']);
				// 	$range = explode('-', $range);
				// }

				// // If a range was requested, handle the partial content logic
				// if ($range) {
				// 	$start = intval($range[0]);
				// 	$end = ($range[1]) ? intval($range[1]) : $fileSize - 1;
				// 	$length = $end - $start + 1;

				// 	header("HTTP/1.1 206 Partial Content");
				// 	header("Content-Range: bytes $start-$end/$fileSize");
				// 	header("Content-Length: $length");

				// 	// $file = fopen($filePath, 'rb');
				// 	// Open the file as a stream
				// 	$stream = $zip->getStream($FileInZip);
				// 	fseek($stream, $start);
				// } else {
				// 	// $file = fopen($filePath, 'rb');
				// 	$stream = $zip->getStream($FileInZip);
				// 	$start = 0;
				// }				

				// if($stream){
				// 	// Read the file content in chunks and stream it
				// 	while (!feof($stream) && ($position = ftell($stream)) <= $fileSize) {
				// 		if ($range && $position >= $end) {
				// 			break;
				// 		}
				// 		// Read 1MB at a time
				// 		print fread($stream, $chunkSize); 
				// 		// flush();
				// 	}

				// 	fclose($stream);
				// }


				// Open the file as a stream not work on 7.4 (why?)
				$stream = $zip->getStream($FileInZip);
				$dataTm = null;
				if ($stream) {
					// Read the file content in chunks and stream it
					while (!feof($stream)) {
						// Read 1MB at a time
						print fread($stream, $chunkSize); 
						// $dataTm .= fread($stream, $chunkSize); 
						flush();
					}
					// Close the stream
					fclose($stream);
				}

				// so i will put the file to another place for streaming (its terrible)
				// $mainDir = "{$_SERVER['TEMP']}/".md5($_SERVER['HTTP_USER_AGENT']);
				// !is_dir($mainDir) || \__fn::rm($mainDir);
				// mkdir($mainDir,0777);
				// file_put_contents("$mainDir/$FileInZip",$zip->getFromName($FileInZip));
				// \__fn::http_file_stream("$mainDir/$FileInZip");
				// unlink("$mainDir/$FileInZip");
			} 

			$zip->close();
		} 
	}

	public function extract($file,$to="",$pass=null){
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