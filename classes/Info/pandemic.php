<?php
namespace Info;

class pandemic {

	private static $ins;
	public $result=[],
		   $province,
		   $ptk_official,
		   $kb_official;
	public function __construct($args=[]){		
		$this->global 	 = null;
		$this->propinsi	 = null;
		$this->indonesia = null;
		$this->kalbar	 = null;
		$this->pontianak = null;
		$this->export = null;
	}
	
	private function kalbar(){
		$html = \__fn::get_site($this->kalbar);
		$data = [];
		if($html){
			// header('Content-Type:text/plain');
			// echo $html;
			// die();
			$html   = \__fn::dom_site($html);
			foreach ($html->find('span[class="bdt-count-this"]') as $num => $span) {
				$data['total'][] = abs($span->innertext);
				$num++;
			}
			$this->kb_official = $data['total'];
			foreach ($html->find('table[id="table_3"]') as $val) {
				$tdval = [];
				foreach ($val->find('tr') as $tr) {
					$wil = isset($tr->find('td',0)->innertext)? ucwords(strtolower(trim($tr->find('td',0)->innertext))) : 0;
					if($wil){
						$tdval[$wil][] = isset($tr->find('td',1)->innertext)? \__fn::abs_num(trim($tr->find('td',1)->innertext)) : 0;
						$tdval[$wil][] = isset($tr->find('td',3)->innertext)? \__fn::abs_num(trim($tr->find('td',3)->innertext)) : 0;
						$tdval[$wil][] = isset($tr->find('td',2)->innertext)? \__fn::abs_num(trim($tr->find('td',2)->innertext)) : 0;
						$tdval[$wil][] = isset($tr->find('td',4)->innertext)? \__fn::abs_num(trim($tr->find('td',4)->innertext)) : 0;
					}
					
				}
				$data['districs'][] = $tdval;				
			}
			// header('Content-Type:text/plain');
			// echo json_encode($data, JSON_PRETTY_PRINT);
			// die();
			$this->ptk_official = $tdval["Pontianak"];
		}
		$this->result['kalbar'] = $data;
		
	}
	private function pontianak(){
		$html = \__fn::get_site($this->pontianak);
		$data = [];
		if($html){
			$html   = \__fn::dom_site($html);
			$arrptk = ["odp","pdp","total"];
			$num    = 0;
			foreach ($html->find('section[class="corona-count-section"]') as $key => $val) {
				$ket = isset($arrptk[$num])? $arrptk[$num]: "nan";				
				$data[$ket] = [
					isset($val->find('p',0)->innertext)? abs(trim($val->find('p',0)->innertext)) : 0,
					isset($val->find('p',1)->innertext)? abs(trim($val->find('p',1)->innertext)) : 0,
					isset($val->find('p',4)->innertext)? abs(trim($val->find('p',4)->innertext)) : 0,
					isset($val->find('p',7)->innertext)? abs(trim($val->find('p',7)->innertext)) : 0
				];
				$num++;
			}
		}
		if($this->ptk_official){
			unset($data['total']);
			$data['total'] = $this->ptk_official;
		}
		$this->result['pontianak'] = $data;
	}
	private function propinsi(){
		$prov = array(
			"Aceh"=>"Aceh",
			"Bali"=>"Bali",
			"Bangka Belitung Islands"=>"Babel",
			"Banten"=>"Banten",
			"Bengkulu"=>"Bengkulu",
			"Central Java"=>"Jateng",
			"Central Kalimantan"=>"Kalteng",
			"Central Sulawesi"=>"Sulteng",
			"East Java"=>"Jatim",
			"East Kalimantan"=>"Kaltim",
			"East Nusa Tenggara"=>"NTT",
			"Gorontalo"=>"Gorontalo",
			"Jakarta"=>"Jakarta",
			"Jambi"=>"Jambi",
			"Lampung"=>"Lampung",
			"Maluku"=>"Maluku",
			"North Kalimantan"=>"Kaltara",
			"North Maluku"=>"Malut",
			"North Sulawesi"=>"Sulut",
			"North Sumatra"=>"Sumut",
			"Papua"=>"Papua",
			"Riau"=>"Riau",
			"Riau Islands"=>"Kepri",
			"South Kalimantan"=>"Kalsel",
			"South Sulawesi"=>"Sulsel",
			"South Sumatra"=>"Sumsel",
			"Southeast Sulawesi"=>"Sultra",
			"Special Region of Yogyakarta"=>"DIY",
			"West Java"=>"Jabar",
			"West Kalimantan"=>"Kalbar",
			"West Nusa Tenggara"=>"NTB",
			"West Papua"=>"Pabar",
			"West Sulawesi"=>"Sulbar",
			"West Sumatra"=>"Sumbar"    
		);
		
		$html  = \__fn::get_site($this->propinsi);
		$html  = preg_split('/(<\/?tbody[^>]*>)/',strip_tags($html,'<table><tbody><thead><tr><th><td>'));
		$html  = \__fn::dom_site('<table>'.$html[1].'</table>'); // urutan table covid 
		
		$num   = 0;
		$data  = [];
		$chars = [];

		foreach (range('a','z') as $key => $value) {
			$chars[] = '['.$value.']';
		}
		
		foreach($html->find('tr') as $d => $row) {
			if(        
				isset($row->find('td',0)->innertext)
			){
				$pid = isset($row->find('th',0)->innertext)? str_replace($chars, "",trim(strip_tags(html_entity_decode($row->find('th',0)->innertext)))) : "";
				if(isset($prov[$pid])){ 
					$add   = 0;//(count($row->find('td')) > 6 )? 1 : 0;  
					
					if($prov[$pid] == 'Kalbar' && $this->kb_official){
						$data[$prov[$pid]] = $this->kb_official;
					}else{
						$data[$prov[$pid]] = [
							isset($row->find('td',(0+$add))->innertext)?\__fn::abs_num($row->find('td',(0+$add))->innertext):0,
							isset($row->find('td',(1+$add))->innertext)?\__fn::abs_num($row->find('td',(1+$add))->innertext):0,
							isset($row->find('td',(3+$add))->innertext)?\__fn::abs_num($row->find('td',(3+$add))->innertext):0,
							isset($row->find('td',(2+$add))->innertext)?\__fn::abs_num($row->find('td',(2+$add))->innertext):0,
						];   
					}
				}
			} 
			$num++;    
		}		
		array_multisort($data, SORT_DESC);
		$this->province = $data;
	}
	private function indonesia(){
		$html = \__fn::get_site($this->indonesia);
		$data = [];
		if($html){	
			foreach (json_decode($html, true) as $key => $value) {				
				foreach (['updated','todayCases','todayRecovered', 'todayDeaths','cases','active','recovered','deaths'] as $var){
					if($key == $var){
						$data[$var] =  $value;
					}
				}
			}
			if($this->province){
				$data['provinces'] = $this->province;
			}
		}
		$this->result['indonesia'] = $data;
	}
	private function global(){
		$html = \__fn::get_site($this->global);
		$data = [];
		if($html){	
			foreach (json_decode($html, true) as $key => $value) {				
				foreach (['updated','todayCases','todayRecovered', 'todayDeaths','cases','active','recovered','deaths'] as $var){
					if($key == $var){
						$data[$var] =  $value;
					}
				}
			}
		}
		$this->result['global'] = $data;
	}
	private function create_data(){
		if($this->propinsi){
			$this->propinsi();
		}
		foreach (['kalbar','pontianak','propinsi','indonesia','global'] as $site) {
			if($this->$site){
				$this->$site();
			}
		}
		if($this->export){
			file_put_contents($this->export, json_encode($this->result,JSON_PRETTY_PRINT));
		}
		return $this->result;
	}
	public static function covid($call=null)
	{
		if(!isset(self::$ins)){
			self::$ins = new pandemic();
		}
		if(is_callable($call)){
			call_user_func($call,self::$ins);
		}		
		return self::$ins->create_data();
	}
}