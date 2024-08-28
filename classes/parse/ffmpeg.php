<?php
namespace parse;
/**
 * Example Use
 * 
 * parse\ffmpeg::convert(function($x){
 *		$folder = "D:/riza-ttnt/Videos";
 *		$fname  = "videName";
 *		$sname  = "$fname";
 *		//$x->mode('fast');  
 *		//$x->fps(25);  
 *		//$x->scale(720);  
 *		//$x->fixtimecut("-5","+5"); 
 *		//$x->moveto("$folder/$fname-proc.mp4")->save();   
 *		$x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
 *			// dont remove this example 
 *			//["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"],  
 *			["35:53.525","37:55.024",true,"$fname-scane-%s.mp4"],
 *			["01:07:29.385","01:08:19.377"],
 *			["01:10:33.000","01:11:43.314"],
 *			["01:13:23.703","01:14:35.497",true,"$fname-scane-%s.mp4"],
 *			["01:22:03.343","01:23:05.981"],
 *			["01:23:19.361","01:24:26.122",true,"$fname-scane-%s.mp4"],
 *			["01:54:19.091","01:57:24.896",true,"$fname-scane-%s.mp4"],
 *			["01:59:35.291","01:59:53.683"],
 *			["02:01:28.144","02:05:46.278",true,"$fname-scane-%s.mp4"],
 *			["02:10:33.149","02:11:51.425",true,"$fname-scane-%s.mp4"],			
 *		])->print();
 *	});
 * 
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
			$rmv,
			$animated,
			$fixed_time=["+",0,"+",0],
			$split_as = "avi",
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
			self::$ins->split = [];
			self::$ins->input 	= null;
			self::$ins->output 	= null;
			self::$ins->gif 	= null;			
			self::$ins->rmv 	= preg_match('/WIN/',PHP_OS) ? "del" : "rm -rf" ;			
			self::$ins->move 	= null;		
			self::$ins->webp 	= null;		
			self::$ins->save 	= false;	
			self::$ins->unlink 	= false;
			self::$ins->codec 	= "-c:v copy -c:a aac -b:a 96k";
			
			foreach ($callback as $call) {	
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
	private function os($str){
		$this->rmv = $str == 'win' ? 'del' : 'rm -rf';
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
			if($this->split_as == "avi")
				$fcut1 = $fcut1 + 1.5;			
			$fcut2 = $this->fixed_time[2] == "-" ? $time2 - $this->fixed_time[3] : $time2 + $this->fixed_time[3]; 
			if($this->split_as == "avi")
				$fcut2 = $fcut2 - 1.5;	
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
		$this->codec = $codec ? $codec : $this->codec ;
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
		$avimod = false;
		foreach(["scale","crop","fps","hls"] as $prms){			
			// jika custom scale atau crop atau hls parameter ada maka
			// akan fitur split avi tidak lagi dilanjutkan
			if(!isset($this->$prms))
				$this->$prms = null;
			if($this->$prms)
				$avimod = true;
		}
		if($avimod)
			$this->split_as = "ts";
		// var_dump($this);die;
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
				$seri = ($num+1).".{$this->split_as}";
				$fstr = $sum > 0 ? $seri : $this->output;
				$fmpg = $this->split_as == "avi" ? '-c copy' :  '-c:v libx265 -crf 20 -c:a aac -b:a 96k';
				//$fmpg = '-c:v libx264 -b:v 2200k -c:a aac -b:a 96k';
				//$fmpg = '-c:v libx264 -crf 20 -c:a aac -b:a 96k';
				$fcod = $this->mode == 'fast' ? ($this->split_as == "avi" ? "-q:v 0 ":null)."-c copy" : ($sum > 0 ? "-q:v 0".($this->split_as == "avi" ? " -c copy":null) : $fmpg) ;
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
					$file = $this->str_range(1,$seri,1,".{$this->split_as}");
					$cons = implode("|", $file);
					$rmvs = implode(" ", $file);
					if(preg_match("/\.{$this->split_as}/",$fstr)){
						$scns = sprintf($scns,$this->counts($nux+1));
						$arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy ' : $fmpg).($this->split_as == "avi" ? ' -fflags +genpts ':null)." \"$scns\" && {$this->rmv} $rmvs</div>";	
						// $arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy ' : $fmpg).($this->split_as == "avi" ? ' -use_wallclock_as_timestamps 1 ':null)." \"$scns\" && {$this->rmv} $rmvs</div>";	
						// $arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy ' : $fmpg)." \"$scns\" && {$this->rmv} $rmvs</div>";	
						$nux++;
					}				
					$num = 0;
				}else{
					$num++;
				}
				if(!$end && $key == $sum && $sum > 0){
					$file = $this->str_range(1,$seri,1,".{$this->split_as}");
					$cons = implode("|", $file);
					$rmvs = implode(" ", $file);
					if(preg_match("/\.{$this->split_as}/",$fstr)){
						$this->output = sprintf($this->output,$this->counts($nuy+1));
						$arr[] =  "<div>ffmpeg -i \"concat:$cons\" ".($this->mode == 'fast' ? '-c copy' : $fmpg )." \"{$this->output}\" && {$this->rmv} $rmvs</div>";
						$nuy++;
					}
					$num++;
				}				
			}

			if($this->move){
				$arr[] = "mv \"{$this->output}\" \"{$this->move}\"";
			}
			
			if($this->unlink){
				$arr[] = "{$this->rmv} \"{$this->input}\"";
			}			
		}
		print implode("\n",$arr);
		if($this->save){
			$def = is_string($this->save)? $this->save : 'C:\Action!\Video';
			$dir = preg_replace('~[\\\]~','/',$def);
			$ext = preg_match('/WIN/',PHP_OS) ? "bat" : 'sh';
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