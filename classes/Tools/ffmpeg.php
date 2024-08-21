<?php
namespace Tools;



class ffmpeg {

	private static $ins;
	private $fps,$cut,$hls,$gif,
			$webp,$crop,
			$scale,$codec,
			$vfilter=null,$input,$split,
			$output,$scenes,$command=[],$tmp_files = [],
			$mode_split,
			$type_image = 'jp(f|x|m|e?g?2?)|(t|g)if(f)?|ico|(pn|sv)g|bmp',
			$type_audio = 'm(4a|ka|p(2|3))|ogg|aac|wma',
			$type_video = 'flv|rm|ts|m(3u|4v|kv|ov|p(4|e?g?))|webm|avi|3g(p|2)|wmv',
			$fixed_time=["+",0,"+",0],
			$strict = ["mkv","flv","wmv","avi"];

	public function __construct($scene)
	{
		$this->scenes = is_bool($scene) ? $scene : false;
 	}
	private static function thisClass($scene=false){
		if(!self::$ins){
			self::$ins = new ffmpeg($scene);
		}
		return self::$ins;
	}
	private function initFile($var,$convert=false)
	{	
		//file_exists($var) || die("<b color=\"red\"><i>file not exist!</i></b>");
		$ext = "";
		$bin = explode(".",preg_replace('~[\\\]~','/',$var));
		if($bin && count($bin) > 1){
			$ext = $bin[count($bin)-1];
			unset($bin[count($bin)-1]);
			$var = implode(".",$bin);
		}		
		foreach (["m3u8","mkv","ts","wmv","avi"] as $lext) {
			if(is_bool($convert) && $convert && strtolower($ext) == $lext){
				$create = "$var-tmp";
				if(!file_exists($create)){
					$strict = null;
					foreach ($this->strict as $fext) {
						if($fext == $lext)
							$strict = " -strict -2";
					}
					$this->command[] = "ffmpeg -i \"$var.$ext\" -c copy$strict \"$create.mp4\"";
				}
				$ext = "mp4";
				$var = $create;
				$this->tmp_files[] = $create.$ext;
			}
		}
		return [$var,$ext];
	}
	public function remove_tmp(){
		foreach ($this->tmp_files as $name) {
			$this->command[] = "rm -f \"$name\"";
		}
		$this->tmp_files = [];
		foreach ($this->command as $cmd) {
			echo "<div>$cmd</div>";
		}
	}
	public function initCodec($output){
		$fcodec = "-c copy";
		$dcodec = "";
		foreach ($this->strict as $fext) {
			if($fext == $output)
				$fcodec = "$fcodec -strict -2";
		}
		if($output == 'm3u8'){
			$fcodec = " -c:v copy -c:a aac -b:a 96k";
			$dcodec = " -f hls -g 2 -hls_time 10 -hls_list_size 0 -start_number 1 -hls_segment_filename";
		}
		if($this->vfilter){
			$fcodec = "";
		}
		return [$fcodec,$dcodec];
	}
	public function initVideoFilter($output){
		if($this->fps){
			$this->vfilter[] = $this->fps;
		}
		if($this->scale){			
			if($output == 'gif'){
				$this->vfilter[] = "$this->scale:flags=lanczos";
			}else{
				$this->vfilter[] = $this->scale;
			}
		}
		if($this->crop){
			$this->vfilter[] = $this->crop;
		}	
		if($output == 'gif'){
			$this->vfilter[] = "split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse";
		}
		$this->vfilter = $this->vfilter ? ' -vf "'.implode(",",$this->vfilter).'"' : null; 

	}
	public static function fps($str=30)
	{
		$self = self::thisClass(true);
		if($str){
			$self->fps = "fps=$str";
		}
		return $self;
	}
	public static function crop($w,$h="-1",$lr=0,$tb=0)
	{
		$self = self::thisClass(true);
		// being include on "video filter params [-vf]"
		$lr = $lr ? ":$lr" : null;
		$tb = $tb ? ":$tb" : null;
		$self->crop = is_numeric($w) && is_numeric($h)? "crop=$w:$h$lr$tb" :null;
		return $self;
	}
	public static function scale($num,$revers=false)
	{
		$self = self::thisClass(true);
		$vals = is_numeric($num) ? $num : false;
		$self->scale = "scale=".($revers ? "$vals:ih*$vals/iw" : "iw*$vals/ih:$vals");
		return $self;
	}
	public static function cut()
	{
		$self = self::thisClass(true);
		$self->split = true;
		return $self;
	}
	public static function scenes($var)
	{
		$self = self::thisClass(true);
		
		if(is_callable($var)){
			call_user_func($var,$self);
		}
		$self->remove_tmp();
		$self->command = [];
	}
	public static function convert($var,$val)
	{
		$self   = self::thisClass();
		$input  = $self->initFile($var,true);
		$output = $self->initFile($val);
		$self->initVideoFilter($output[1]);

		$fcodec = $self->initCodec($output[1]);
		$export = "{$output[0]}.{$output[1]}";
		if($output[1]=='m3u8'){
			$export = "{$output[0]}-%3d.ts";
		}
		$self->command[] = "ffmpeg -i \"{$input[0]}.{$input[1]}\" {$fcodec[0]}{$self->vfilter}{$fcodec[1]} \"$export\"";
		$self->remove_tmp();
		$self->command = [];		
	}
}