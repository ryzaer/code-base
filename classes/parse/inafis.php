<?php
namespace parse;
/*
  * FINGER FORMULA GENERATOR
  * Source	: https://github.com/ryzaer/php-finger-formula-generator
  * Author	: Riza TTNT
  * Desc	: -
  * Date Created : 07th, Sep 2018
  * Last Updated : 19th, Oct 2020
  * License : MIT
  * 
  * 
  * EXAMPLE USE
  * inafis::demo();
  */ 

  class inafis {
	
	// kotak angka{2 s/d 40}, kotak merge{I,M,O} if WORL, kotak huruf{W,R,L,A,T} 	 

	//////////////////////////////////////////////////////////////
	//    1     //     2    //     3    //     4    //     5    //
	//  16      //   16     //    8     //    8     //    4     //
	//          //          //          //          //          //
	//          //          //          //          //          //
	//          //          //          //          //          //
	//////////////////////////////////////////////////////////////
	//    6     //    7     //     8    //    9     //    10    //
	// 4        //   2      //   2      //   1      //   1      //
	//          //          //          //          //          //
	//          //          //          //          //          //
	//          //          //          //          //          //
	//////////////////////////////////////////////////////////////

	// nilai tetap pada tiap kotak 1 s/d 10
	private $preval = [[16,16,8,8,4],[4,2,2,1,1]];

	// [whorl] jika kotak [1,2,3,4,6,7,8,9] 
	private $boxs_Whorl = ["i","m","o"];

	// [loop] jika kotak [2,3,4,7,8,9]
	private $boxs_loop = ["i","o"];

	// [loop] jika kotak [2,3,4,7,8,9]
	private $supercode = ["r","t","a"];

	// contoh query input
	private $example = [
		[
			[12,13,14,15,16],
			['w','w','w','w','w']
		],
		[
			[17,18,19,20,21],
			['w','w','w','w','w']
		],		
		[
			// alternatif jika [whorl]
			["i","i","i","i"],
			["i","i","i","i"]
		]
	];


	public function __construct($array=[]){
		$this->args   = $array ? $array : $this->example;		
		$this->major  = $this->major();
		$this->thumb  = $this->thumbs();	
		$this->whorl  = $this->whorl();	
		$this->index  = $this->index();	
		$this->margin = $this->margin();	
		$this->pinkie = $this->pinkie();	
	}

	private function deathcode(){
		$check = false;
		foreach ($this->supercode as $key => $val) {
			if($this->args[0][1][0] == $val || $this->args[1][1][0] == $val){
				$check = true;
			}
		}
		return $check;
	}

	private function major(){		
		function chk_major($arrs){
			$num = [];
			foreach ($arrs[1] as $key => $val){
				if($key < 4)					
					foreach (['r','u'] as $k => $v) {
						if($arrs[0][$key] && $val == $v){
							$num[] = $key;
						}
					}
			}
			return $num ? $arrs[0][$num[0]] : '';
		}
				
		$value  = chk_major($this->args[0]);

		if(!$value){
			$value = chk_major($this->args[1]);			
		}
		
		return $value;
	}

	private function thumbs(){

		// [loop] jika kotak [1,6]
		function loop_thumb($num,$left_thumb=0){
			$str = "s";	
			if($left_thumb >= 17){				
				if($num >= 22){ $str = "m";}
				if($num >= 23){ $str = "l";}
			}else{
				if($num >= 12){ $str = "m";}
				if($num >= 17){ $str = "l";}
			}
			return $str;
		}

		$thumb1 = '.';
		$thumb2 = '';
		
		if(!$this->deathcode()){
			$nilai1 = $this->args[0][0][0];
			$nilai2 = $this->args[1][0][0];

			$thumb1 = loop_thumb($nilai1,$nilai2);
			$thumb2 = loop_thumb($nilai2);

			if($this->args[0][1][0] == 'w'){
				$thumb1 = $this->args[2][0][0];
			}

			if($this->args[1][1][0] == 'w'){
				$thumb2 = $this->args[2][1][0];
			}

			$thumb1 = strtoupper($thumb1);
			$thumb2 = strtoupper($thumb2);
		}
		
		return [$thumb1,$thumb2];
	}

	private function whorl(){
		
		function chk_whorl($arrs,$preval){
			$chk = [];
			foreach ($arrs as $r => $v) {
				$vars = $preval[$r];
				foreach (['r','a','t','u'] as $k => $s) {
					if($v == $s){
						$vars= 0;
					}
				}
				$chk[] = $vars;
			}	

			return $chk;
		}

		$death  = $this->deathcode();
		$dead1  = null;
		$dead2  = null;
		if($death){
			$dead1  = str_replace(['w','u'],null,$this->args[0][1][0]);
			$dead2  = str_replace(['w','u'],null,$this->args[1][1][0]);
		}

		$comb1  = chk_whorl($this->args[0][1],$this->preval[0]);
		$comb2  = chk_whorl($this->args[1][1],$this->preval[1]);
		$thumb1 = $comb2[0]+$comb1[1]+$comb2[2]+$comb1[3]+$comb2[4]+1;
		$thumb2 = $comb1[0]+$comb2[1]+$comb1[2]+$comb2[3]+$comb1[4]+1;

		return [$thumb1.$dead1,$thumb2.$dead2];
	}

	private function index(){
		$index1 = strtoupper($this->args[0][1][1]);
		$index2 = strtoupper($this->args[1][1][1]);
		return [$index1,$index2];
	}

	private function margin(){
		$bpattern = [1,2,3];

		function chk_superior($boxs=[],$superior){
			$pattern = false;
			foreach ($superior as $key => $val) {
				foreach ($boxs as $k => $v) {
					if($v==$val){
						$pattern = true;
					}
				}
			}
			return $pattern;
		}

		function chk_loop($num,$finger=0){
			// $finger 1 == telunjuk
			// $finger 2 == tengah
			// $finger 3 == manis

			$str = 'o';
			if($finger == 1 && $num <= 9){
				$str = 'i';
			}

			if($finger == 2 && $num <= 10){
				$str = 'i';
			}

			if($finger == 3 && $num <= 13){
				$str = 'i';
			}

			return $str;
		}

		function chk_varian($margs){

			$varc = preg_match('/-/i',$margs);
			$data = [];
			if(!$varc){
				$margin = array_count_values(str_split($margs));
				foreach ($margin as $key => $value) {
					$data[]= $value > 1 ? $value.$key : $key ;
				}
			}
			
			return $varc ? $margs : implode('',$data);
		}

		function margin_dead($boxs=[],$pattern){
			
			$marginal=[];			
			foreach ($pattern as $v) {
				$marginal[] = $boxs[$v];					
			}
			
			$marginal = preg_replace('/(w|u)/i','-',implode('',$marginal));
			$varian = str_replace(
				['r-r','t-t','a-a','rrr','ttt','aaa','rr','tt','aa'],
				['2r','2t','2a','3r','3t','3a','2r','2t','2a'],
				$marginal
			);			

			return chk_varian($varian);
			
		}

		function margin_life($data1=[],$data2=[],$pattern){
			$array=[];
			foreach ($pattern as $k => $n) {
				$array[] = ($data1[1][$n] == 'r' || $data1[1][$n] == 'u')? chk_loop($data1[0][$n],($k+1)) : ($data1[1][$n] == 'w' ? $data2[$n] : '-');
			}
			return strtoupper(implode("",$array));
		}

		//$shape1 = chk_superior([$this->args[0][1][1],$this->args[1][1][1]],['a','t']);
		$shuffle = chk_superior(
						[
							$this->args[0][1][2],
							$this->args[0][1][3],
							$this->args[0][1][4],
							$this->args[1][1][2],
							$this->args[1][1][3],
							$this->args[1][1][4]
						],
						$this->supercode
				  );

		if($shuffle){
			$bpattern = [2,3,4];
			$margin1  = margin_dead($this->args[0][1],$bpattern);
			$margin2  = margin_dead($this->args[1][1],$bpattern);			
		}else{
			$margin1 = margin_life($this->args[0],$this->args[2][0],$bpattern);
			$margin2 = margin_life($this->args[1],$this->args[2][1],$bpattern);
		}

		return [$margin1,$margin2];
	}

	private function pinkie(){
		function chk_pinkie($code=''){
			$avail = false;
			foreach (['w','u','r'] as $key => $val) {
				if($code == $val){
					$avail = $code;
				}
			}
			return $avail;
		}

		$chpink1 = chk_pinkie($this->args[0][1][4]);
		$chpink2 = chk_pinkie($this->args[1][1][4]);
		
		$pinkie1 = $chpink1 ? $this->args[0][0][4] : ''; 
		$pinkie2 = $chpink1 || $chpink1 == $chpink2 ? '' : ($chpink2 ? $this->args[1][0][4]:'');

		if($chpink2 == 'r' || $chpink2 == 'u'){
			if($chpink1 == 'w'){
				$pinkie1 = '';
				$pinkie2 = $chpink2 ? $this->args[1][0][4]:'';
			}
		}


		return [$pinkie1,$pinkie2];
	}

	public function generate(){
		return [
			[
				$this->major,
				$this->thumb[0],
				$this->whorl[0],
				$this->index[0],
				$this->margin[0],
				$this->pinkie[0]
			],
			[
				$this->thumb[1],
				$this->whorl[1],
				$this->index[1],
				$this->margin[1],
				$this->pinkie[1],
			]
		];
	}

	public static function demo(){
		$p    = $_POST;
		$form = [];
		$html = '<style>
				table { 					
					border-collapse: collapse; 
				}
				button {
					margin-top:20px;
				}
				.formula td {
					font-family: Courier;
					font-weight:bold;
					width:30px;
				}
				@media screen and (max-width: 480px), screen and (max-device-width: 767px) and (orientation: portrait), screen and (max-device-width: 415px) and (orientation: landscape){
					.tbmain {
						font-size:30px;						
					}
					
					input, select, button {
						font-size:20px;
					}
					.tbmain td{
						font-size:20px;
						margin:auto!important;
						height:25px;
					}
				}
				</style>
				<center><table class="tbmain"><form method="post">';
		for($i=0;$i<5;$i++){
			if($i <4){
				$input[1][] = '<td><select name="rght_lw[]" style="width:50">
									<option value="i" '.(($p && isset($p['rght_lw'][$i]) && strtolower($p['rght_lw'][$i]) == "i" )? 'selected': ($p ? null : 'selected')).' >I</option>
									<option value="m" '.(($p && isset($p['rght_lw'][$i]) && strtolower($p['rght_lw'][$i]) == "m" )? 'selected': null).' >M</option>
									<option value="o" '.(($p && isset($p['rght_lw'][$i]) && strtolower($p['rght_lw'][$i]) == "o" )? 'selected': null).' >O</option>
								</select></td>';	
			}else{
				$input[1][] = '<td></td>';
			}		

			$input[2][] = '<td><input type="number" name="numr[]" value="'.(($p && isset($p['numr'][$i]))? $p['numr'][$i] : ($i == 4 ? 23 : null)).'" style="width:50"></td>';
			
			$input[3][] = '<td><select name="rght_lr[]" style="width:50">
								<option value="w" '.(($p && isset($p['rght_lr'][$i]) && strtolower($p['rght_lr'][$i]) == "w" )? 'selected': null).' >'.($i == 1 ? 'W' : 'w').'</option>						
								<option value="u" '.(($p && isset($p['rght_lr'][$i]) && strtolower($p['rght_lr'][$i]) == "u" )? 'selected': null).' >'.($i == 1 ? 'U' : 'u').'</option>
								<option value="r" '.(($p && isset($p['rght_lr'][$i]) && strtolower($p['rght_lr'][$i]) == "r" )? 'selected': null).' >'.($i == 1 ? 'R' : 'r').'</option>
								<option value="a" '.(($p && isset($p['rght_lr'][$i]) && strtolower($p['rght_lr'][$i]) == "a" )? 'selected': null).' >'.($i == 1 ? 'A' : 'a').'</option>
								<option value="t" '.(($p && isset($p['rght_lr'][$i]) && strtolower($p['rght_lr'][$i]) == "t" )? 'selected': null).' >'.($i == 1 ? 'T' : 't').'</option>
							</select></td>';
			if($i <4){				
				$input[4][] = '<td><select name="left_lw[]" style="width:50">
									<option value="i" '.(($p && isset($p['left_lw'][$i]) && strtolower($p['left_lw'][$i]) == "i" )? 'selected': ($p ? null : 'selected')).' >I</option>
									<option value="m" '.(($p && isset($p['left_lw'][$i]) && strtolower($p['left_lw'][$i]) == "m" )? 'selected': null).' >M</option>
									<option value="o" '.(($p && isset($p['left_lw'][$i]) && strtolower($p['left_lw'][$i]) == "o" )? 'selected': null).' >O</option>
								</select></td>';
			}else{
				$input[4][] = '<td></td>';
			}
							
			$input[5][] = '<td><input type="number" name="numl[]" value="'.(($p && isset($p['numl'][$i]))? $p['numl'][$i] : null ).'" style="width:50"></td>';
			$input[6][] = '<td><select name="left_lr[]" style="width:50">
								<option value="w" '.(($p && isset($p['left_lr'][$i]) && strtolower($p['left_lr'][$i]) == "w" )? 'selected': null).' >'.($i == 1 ? 'W' : 'w').'</option>						
								<option value="u" '.(($p && isset($p['left_lr'][$i]) && strtolower($p['left_lr'][$i]) == "u" )? 'selected': null).' >'.($i == 1 ? 'U' : 'u').'</option>
								<option value="r" '.(($p && isset($p['left_lr'][$i]) && strtolower($p['left_lr'][$i]) == "r" )? 'selected': null).' >'.($i == 1 ? 'R' : 'r').'</option>
								<option value="a" '.(($p && isset($p['left_lr'][$i]) && strtolower($p['left_lr'][$i]) == "a" )? 'selected': null).' >'.($i == 1 ? 'A' : 'a').'</option>
								<option value="t" '.(($p && isset($p['left_lr'][$i]) && strtolower($p['left_lr'][$i]) == "t" )? 'selected': null).' >'.($i == 1 ? 'T' : 't').'</option>
							</select></td>';	

			$form[0][0][] = isset($p['numr'][$i])? $p['numr'][$i] : '';
			$form[0][1][] = isset($p['rght_lr'][$i])? $p['rght_lr'][$i] : '';

			$form[1][0][] = isset($p['numl'][$i])? $p['numl'][$i] : '';
			$form[1][1][] = isset($p['left_lr'][$i])? $p['left_lr'][$i] : '';	
			
			if($i <4){	
				$form[2][0][] = isset($p['rght_lw'][$i])? $p['rght_lw'][$i] : '';		
				$form[2][1][] = isset($p['left_lw'][$i])? $p['left_lw'][$i] : '';
			}
		}

		$html .= '<tr><td colspan="5"><center>Jari Kanan</center></td></tr>';
		$html .= '<tr>'.implode("",$input[1])."</tr>";
		$html .= '<tr>'.implode("",$input[2])."</tr>";
		$html .= '<tr>'.implode("",$input[3])."</tr>";
		$html .= '<tr><td colspan="5"><center>Jari Kiri</center></td></tr>';
		$html .= '<tr>'.implode("",$input[4])."</tr>";	
		$html .= '<tr>'.implode("",$input[5])."</tr>";
		$html .= '<tr>'.implode("",$input[6])."</tr>";
		$html .= '<tr><td colspan="5"><center><button>GENERATE</button></center></td></tr>';	
		$html .= '</table></form><br><br>';


		$fin = [
			['','I','32','W','III','23'],
		    ['I','32','W','III','']
		];

		if($p){
			$inafis = new inafis($form);
			$fin = $inafis->generate();
		}

		$html .= '<table class="formula">';
		$html .= '	<tr><td>'.$fin[0][0].'</td><td style="text-align:center">'.$fin[0][1].'</td><td>'.$fin[0][2].'</td><td style="text-align:center">'.$fin[0][3].'</td><td>'.$fin[0][4].'</td><td style="width:40px;text-align:right">'.$fin[0][5].'</td></tr>';
		$html .= '	<tr><td></td><td style="text-align:center">'.$fin[1][0].'</td><td>'.$fin[1][1].'</td><td style="text-align:center">'.$fin[1][2].'</td><td>'.$fin[1][3].'</td><td style="width:40px;text-align:right">'.$fin[1][4].'</td></tr>';
		$html .= '</table></center>';

		echo $html;
		
	}
}
