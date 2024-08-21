<?php
namespace parse;
/*--Example segment times--
 *$ ffmpeg -i input -f segment -segment_time 10 -force_key_frames "expr:gte(t,n_forced*4)" -reset_timestamps 1 output_%04d.mp4
 *--Converting an HLS (m3u8) to MP4--
 *$ ffmpeg -i "file:///F:/WEB/@PROJECT_WEBS/@class.collections/assets/videos/hls.example/play1.m3u8" -acodec copy -bsf:a aac_adtstoasc -vcodec copy out.mp4
 *$ ffmpeg -i "http://host/folder/file.m3u8" -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 18 file.mp4
 *--Convert to animated gif
 *ffmpeg -ss 30 -t 3 -i input.mp4 -vf "fps=10,scale=320:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" -loop 0 output.gif
 *--Convert image to webp
 *ffmpeg -i image.jpg{png.. non animated} -c:v libwebp -quality 50 output.webp // quality default 75
 *ffmpeg -i image.gif{animated} -c:v libwebp -lossless 1 -loop 0 output.webp
 *--Draw text with background color (5 scnd)
 *ffmpeg -f lavfi -i "color=color=red:1280x720, drawtext=enable='gte(t,0)': text=Text7: fontfile=C\\:/@rial.ttf: y=(h/2)-(th/2): x=(w/2)-(tw/2): fontcolor=white: fontsize=50" -t 5 Output.mp4
 */
class ffmpeg {

	private static $ins;
	private $hls,$gif,$webp,
			$codec,
			$input,
			$output,
			$split,			
			$scale,
		    $move,
		    $mode,
			$save,
			$unlink,
			$crop,
			$animated,
			$fixed_time=["+",0,"+",0],
			$cycle = 1;
	public $force,$obj=[],$fps=null;
	// public function __construct(){
	// 	$this->force = (object)[];
	// 	$this->force->resolution = 0;
	// }
	public static function convert(...$callback){
		if(!self::$ins){
			self::$ins = new ffmpeg();
		}
		self::$ins->obj  = isset($callback[0]) && is_object($callback[0]) ? $callback[0] : null;	
		if(self::$ins->obj){
			foreach ($callback as $call) {			
				self::$ins->codec 	= "-c:v copy -c:a aac -b:a 96k";
				self::$ins->input 	= null;
				self::$ins->output 	= null;
				self::$ins->split 	= [];		
				self::$ins->scale 	= null;
				self::$ins->crop 	= null;
				self::$ins->move 	= null;		
				self::$ins->hls 	= null;		
				self::$ins->gif 	= null;			
				self::$ins->webp 	= null;		
				self::$ins->save 	= false;	
				self::$ins->unlink 	= false;
				if(is_callable($call)){
					call_user_func($call, self::$ins);				
				}
			}
		}else{
			self::$ins->input = isset($callback[0]) && is_string($callback[0]) ? $callback[0] : null;
			self::$ins->mode  = isset($callback[1]) && is_string($callback[1]) ? $callback[1] : "m3u8_hls";
			return self::$ins;
		}
	}
	private function time_format($str_time, $implode=false){
		$timearr    = explode(":",$str_time);   
		$timerpt    = count($timearr) <= 2 ? str_repeat("00:", 2 - count($timearr) + 1) : null;
		$exptime    = explode(".", str_replace(',','.',$timerpt.$str_time)); 
		$timeformat = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2",  $exptime[0]);
		$milisecond = isset($exptime[1])? ( $implode ? substr($exptime[1],0,3) : substr(abs("0.".$exptime[1]),0,5) ) : 0 ; 
		$format = [
			$timeformat,
			$milisecond
		];
		if($implode){
			$format = $timeformat.".".$milisecond;
		}
		return $format;
	}
	private function time2scnd($str_time = "10:00.45989898"){     
		$timestr  = $this->time_format($str_time);
		sscanf($timestr[0], "%d:%d:%d", $hours, $minutes, $seconds);
		return ($hours * 3600) + ($minutes * 60) + $seconds + $timestr[1];
	}
	private function str_range($from,$to,$int,$char)
	{
		$args = [];
		foreach (range($from,$to,$int) as $rngs) {
			$args[] = $rngs.$char;
		}
		return $args;
	}
	public function param(...$args){
		foreach (explode("|","input|output") as $n => $key) {
			$this->$key = isset($args[$n])? preg_replace('~[\\\]~','/',$args[$n]) : "";
		}
		return $this;
	}	
	public function fixtimecut(...$arr_time){
		$time1 = isset($arr_time[0])? [ substr($arr_time[0],0,1), abs(substr($arr_time[0],1)) ]  : ["+",0];
		$time2 = isset($arr_time[1])? [ substr($arr_time[1],0,1), abs(substr($arr_time[1],1)) ]  : ["+",0];
		$this->fixed_time = array_merge($time1,$time2);
	}
	public function crop($w,$h,$lr=0,$tb=0)
	{
		// being include on "video filter params [-vf]"
		$lr = $lr ? ":$lr" : null;
		$tb = $tb ? ":$tb" : null;
		$this->crop = is_numeric($w) && is_numeric($h)? "crop=$w:$h$lr$tb" :null;
		return $this;
	}
	public function split(...$param){
		foreach ((isset($param[0]) && is_array($param[0])? $param[0] : $param ) as $num => $value) {
			$time1 = $this->time2scnd($value[0]);
			$time2 = $this->time2scnd($value[1]) - $time1 ;
			$fcut1 = $this->fixed_time[0] == "-" ? $time1 - $this->fixed_time[1] : $time1 + $this->fixed_time[1]; 	
			$fcut2 = $this->fixed_time[2] == "-" ? $time2 - $this->fixed_time[3] : $time2 + $this->fixed_time[3]; 
			$args1 = isset($value[2]) ? $value[2] : null;
			$args2 = isset($value[3]) ? $value[3] : null;
			// sampai disini untuk membuat scene automatis
			$this->split[] = [($fcut1 > 0 ? $fcut1 : 0),($fcut2 > 0 ? $fcut2 : 0),$args1,$args2];
		}
		return $this;
	}
	public function moveto($destination){
		$this->move = preg_replace('~[\\\]~','/',$destination);
	}
	public function export($destination){
		if($this->output && $this->mode == "m3u8_hls"){
			preg_match('/windows/',strtolower($_SERVER['SystemRoot']),$root);
			$rand = $root ? "%%" : "%";
			$destination = $this->moveto($destination);
			echo "{$this->output} \"$destination-%3d.ts\" \"$destination.m3u8\"";
		}
	}
	public function unlink(){
		$this->unlink = true;
		return $this;
	}
	public function mode($str,$codec=null){
		// params : fast, m3u8_hls, image_gif, image_webp
		$this->mode = is_string($str) ? $str : null;
		$this->codec = is_string($codec) ? $codec : $this->codec ;
		if($this->mode == "m3u8_hls"){			
			// -start_number 1 -hls_segment_filename filename-%3d.ts meaning 3digits for auto numbering start from 1 devault 0
			//$this->output = "ffmpeg -y -i \"{$this->input}\" $codec -f hls -g 2 -hls_time 10 -hls_list_size 0 -start_number 1 -hls_segment_filename";
			$this->hls = "-f hls -g 2 -hls_time 10 -hls_list_size 0 -start_number 1 -hls_segment_filename";
		}
		if($this->scale && $this->mode == "image_gif"){	
			// being include on "video filter params [-vf]"	
			// place function scale after mode function	
			$codec = is_string($codec) ? $codec : null ;
			// -start_number 1 -hls_segment_filename filename-%3d.ts meaning 3digits for auto numbering start from 1 devault 0
			//$this->output = "ffmpeg -y -i \"{$this->input}\" $codec -f hls -g 2 -hls_time 10 -hls_list_size 0 -start_number 1 -hls_segment_filename";
			$this->gif = "split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse";
		}
		return $this;
	}
	public function fps($num){
		// being include on "video filter params [-vf]"
		$this->fps = "fps=$num" ;
	}
	public function scale($num, $revers=false){
		// being include on "video filter params [-vf]"
		$vals = is_numeric($num) ? $num : false;
		if($this->mode == "image_gif"){
			$vals = $vals ? "$vals:-1" : $vals;
			$this->scale = "scale=$vals:flags=lanczos";
		}else{
			$this->scale = "scale=".($revers ? "$vals:ih*$vals/iw" : "iw*$vals/ih:$vals");
		}
		return $this;
	}
	private function counts($int){
		return $int && $int > 9 ? strval($int) : "0$int" ;
	}
	public function print($save=false){
		// $fileExt =".ts";
		$vfilter =null;
		$vdcodec =null;
		$imgloop =null;
		
		if($this->fps){
			$vfilter[]=$this->fps;
		}
		if($this->scale){
			$vfilter[]=$this->scale;
		} 
		if($this->crop){
			$vfilter[]=$this->crop;
		}
		if($vfilter){
			$vfcount = '"'.implode(",",$vfilter).'"';
			$vfilter = " -vf $vfcount ";
		}

		// if($this->mode == 'image_gif'){
		// 	$fileExt =".gif";
		// }
		// if($this->mode == 'image_webp'){
		// 	$fileExt =".webp";
		// }
		if($this->mode == 'image_gif' || $this->mode == 'image_webp'){
			$imgloop = "-loop 0 ";
		}

		$this->mode = !$vfilter ? $this->mode : null; 
		$this->save = $save;
		$arr = []; 
		if($this->split){
			$num = 0;
			$nux = 0;
			$nuy = 0;
			$sum = count($this->split)-1;
			foreach ($this->split as $key => $val ) {
				$alts = $this->input ; 
				$scns = null;
				$seri = ($num+1).".ts";
				$fstr = $sum > 0 ? $seri : $this->output;
				$fmpg = '-c:v libx265 -crf 20 -c:a aac -b:a 96k';
				//$fmpg = '-c:v libx264 -b:v 2200k -c:a aac -b:a 96k';
				//$fmpg = '-c:v libx264 -crf 20 -c:a aac -b:a 96k';
				$fcod = $this->mode == 'fast' ? '-c copy' : ($sum > 0 ? '-q:v 0' : $fmpg) ;
				if(is_string($val[2]) && $val[2]){
					$alts = preg_replace('~[\\\]~','/',$val[2]);
				}	
				$red_color = null;
				if($val[1] == 0){
					$red_color = " style=\"color:red\"";
				} 
				$arr[] = "<div$red_color>ffmpeg -ss {$val[0]} -i \"{$alts}\" $fcod {$vfilter}{$imgloop}-t {$val[1]} $fstr</div>";
				$end = false;
				if(is_string($val[3]) && $val[3]){	
					if($key == $sum && $sum > 0){
						$end = true;
					}				
					$scns = preg_replace('~[\\\]~','/',$val[3],$num);
					$file = $this->str_range(1,$seri,1,".ts");
					$cons = implode("|", $file);
					$rmvs = implode(" ", $file);
					if(preg_match('/\.ts/',$fstr)){
						$scns = sprintf($scns,$this->counts($nux+1));
						$arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy' : $fmpg )." \"$scns\" && rm -rf $rmvs</div>";	
						$nux++;
					}				
					$num = 0;
				}else{
					$num++;
				}
				if(!$end && $key == $sum && $sum > 0){
					$file = $this->str_range(1,$seri,1,".ts");
					$cons = implode("|", $file);
					$rmvs = implode(" ", $file);
					if(preg_match('/\.ts/',$fstr)){
						$this->output = sprintf($this->output,$this->counts($nuy+1));
						$arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy' : $fmpg )." \"{$this->output}\" && rm -rf $rmvs</div>";
						$nuy++;
					}
					$num++;
				}				
			}

			if($this->move){
				$arr[] = "mv \"{$this->output}\" \"{$this->move}\"";
			}
			
			if($this->unlink){
				$arr[] = "rm -rf \"{$this->input}\"";
			}			
		}
		print implode("\n",$arr);
		if($this->save){
			$def = is_string($this->save)? $this->save : 'C:\Action!\Video';
			$dir = preg_replace('~[\\\]~','/',$def);
			$ext = 'sh';
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$ext = 'bat';
			} 		
			if($this->cycle <= 1){
				$scrpt = implode("\n",$arr);
				$this->cycle = $this->cycle + 1;
			}else{
				$scrpt = file_get_contents("$dir/exec.$ext")."\n".implode("\n",$arr);			
			}
			file_put_contents("$dir/exec.$ext",$scrpt,true);
		}
		
	}
}