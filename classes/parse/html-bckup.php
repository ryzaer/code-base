<?php 
namespace parse;

class html {
	public  $doctype	= "<!DOCTYPE html>",
			/* for minifying json,js,css&html scripts strings ex. <tags>(will be minify)</tags>*/
			$shrinked 	= true;
	private $result 	= [],
			$_form_prm	= [],
			$_form_sts  = false,			
			$singleton  = "area|base|br|col|embed|hr|img|isindex|input|keygen|link|meta|param|source|track|wbr";
	
	public function __construct(){

		// header('Content-Type:text/plain');
		// foreach(explode('|','a|abbr|address|area|article|aside|audio|b|base|bdi|bdo|blockquote|body|br|button|canvas|caption|center|cite|code|col|colgroup|data|datalist|dd|del|details|dfn|dialog|div|dl|dt|em|embed|fieldset|figcaption|figure|footer|form|h1|h2|h3|h4|h5|h6|head|header|hr|html|i|iframe|img|input|ins|kbd|label|legend|li|link|main|map|mark|meta|meter|nav|noscript|object|ol|optgroup|option|output|p|param|picture|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|source|span|strong|style|sub|summary|sup|svg|table|tbody|td|template|textarea|tfoot|th|thead|time|title|tr|track|u|ul|var|video|wbr') as $tags){
		// 	if($tags == 'hr'){
		// 		print("\$this->$tags = function(){ \$this->add(\"$tags\"); return \$this; };\n");
		// 	}elseif($tags == 'br'){
		// 		print("\$this->$tags = function(\$num=1){\n\tfor(\$n=0;\$n<abs(\$num);\$n++){\n\t\t\$this->add(\"$tags\");\n\t}\n\treturn \$this;\n};\n");
		// 	}else{
		// 		$novalue = false;
		// 		foreach (explode("|","audio|video|select|html|head|table|tr|ul|ol") as $vattr) {
		// 			if($tags == $vattr){
		// 				$novalue = true;
		// 			}
		// 		}
		// 		$nocallvalue = false;
		// 		foreach (explode("|","area|base|col|embed|img|isindex|input|keygen|link|meta|param|source|track|wbr") as $vattr) {
		// 			if($tags == $vattr){
		// 				$nocallvalue = true;
		// 			}
		// 		}
		// 		if($novalue){
		// 			print("\$this->$tags = function(...\$args){\n\t\$arrs = [];\n\tforeach (\$args as \$child){\n\t\tif(is_callable(\$child)){\n\t\t\t\$arrs[] = \$child;\n\t\t}\n\t\tif(\$this->attr_model(\$child)){\n\t\t\t\$arrs[] = \$child;\n\t\t}\n\t}\n\t\$this->add(...\$this->tag(\"$tags\",\$arrs));\n\treturn \$this;\n};\n");
		// 		}elseif($nocallvalue){
		// 			print("\$this->$tags = function(...\$args){\n\t\$arrs = [];\n\tforeach (\$args as \$child){\n\t\tif(\$this->attr_model(\$child)){\n\t\t\t\$arrs[] = \$child;\n\t\t}\n\t}\n\t\$this->add(...\$this->tag(\"$tags\",\$arrs));\n\treturn \$this;\n};\n");
		// 		}else{
		// 			print("\$this->$tags = function(...\$args){\n\t\$this->add(...\$this->tag(\"$tags\",\$args)); return \$this;\n};\n");
		// 		}
		// 	}
		// }	
		// die();	

		$this->say = (object)[];
		$this->say->data = [];

		// HTML5 tag functions
		// https://www.w3schools.com/tags/;
			
		$this->a = function(...$args){
			$this->add(...$this->tag("a",$args)); return $this;
		};
		$this->abbr = function(...$args){
			$this->add(...$this->tag("abbr",$args)); return $this;
		};
		$this->address = function(...$args){
			$this->add(...$this->tag("address",$args)); return $this;
		};
		$this->area = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("area",$arrs));
			return $this;
		};
		$this->article = function(...$args){
			$this->add(...$this->tag("article",$args)); return $this;
		};
		$this->aside = function(...$args){
			$this->add(...$this->tag("aside",$args)); return $this;
		};
		$this->audio = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("audio",$arrs));
			return $this;
		};
		$this->b = function(...$args){
			$this->add(...$this->tag("b",$args)); return $this;
		};
		$this->base = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("base",$arrs));
			return $this;
		};
		$this->bdi = function(...$args){
			$this->add(...$this->tag("bdi",$args)); return $this;
		};
		$this->bdo = function(...$args){
			$this->add(...$this->tag("bdo",$args)); return $this;
		};
		$this->blockquote = function(...$args){
			$this->add(...$this->tag("blockquote",$args)); return $this;
		};
		$this->body = function(...$args){
			$this->add(...$this->tag("body",$args)); return $this;
		};
		$this->br = function($num=1){
			for($n=0;$n<abs($num);$n++){
				$this->add("br");
			}
			return $this;
		};
		$this->button = function(...$args){
			$this->add(...$this->tag("button",$args)); return $this;
		};
		$this->canvas = function(...$args){
			$this->add(...$this->tag("canvas",$args)); return $this;
		};
		$this->caption = function(...$args){
			$this->add(...$this->tag("caption",$args)); return $this;
		};
		$this->center = function(...$args){
			$this->add(...$this->tag("center",$args)); return $this;
		};
		$this->cite = function(...$args){
			$this->add(...$this->tag("cite",$args)); return $this;
		};
		$this->code = function(...$args){
			$this->add(...$this->tag("code",$args)); return $this;
		};
		$this->col = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("col",$arrs));
			return $this;
		};
		$this->colgroup = function(...$args){
			$this->add(...$this->tag("colgroup",$args)); return $this;
		};
		$this->data = function(...$args){
			$this->add(...$this->tag("data",$args)); return $this;
		};
		$this->datalist = function(...$args){
			$this->add(...$this->tag("datalist",$args)); return $this;
		};
		$this->dd = function(...$args){
			$this->add(...$this->tag("dd",$args)); return $this;
		};
		$this->del = function(...$args){
			$this->add(...$this->tag("del",$args)); return $this;
		};
		$this->details = function(...$args){
			$this->add(...$this->tag("details",$args)); return $this;
		};
		$this->dfn = function(...$args){
			$this->add(...$this->tag("dfn",$args)); return $this;
		};
		$this->dialog = function(...$args){
			$this->add(...$this->tag("dialog",$args)); return $this;
		};
		$this->div = function(...$args){
			$this->add(...$this->tag("div",$args)); return $this;
		};
		$this->dl = function(...$args){
			$this->add(...$this->tag("dl",$args)); return $this;
		};
		$this->dt = function(...$args){
			$this->add(...$this->tag("dt",$args)); return $this;
		};
		$this->em = function(...$args){
			$this->add(...$this->tag("em",$args)); return $this;
		};
		$this->embed = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("embed",$arrs));
			return $this;
		};
		$this->fieldset = function(...$args){
			$this->add(...$this->tag("fieldset",$args)); return $this;
		};
		$this->figcaption = function(...$args){
			$this->add(...$this->tag("figcaption",$args)); return $this;
		};
		$this->figure = function(...$args){
			$this->add(...$this->tag("figure",$args)); return $this;
		};
		$this->footer = function(...$args){
			$this->add(...$this->tag("footer",$args)); return $this;
		};
		$this->form = function(...$args){
			$this->add(...$this->tag("form",$args)); return $this;
		};
		$this->h1 = function(...$args){
			$this->add(...$this->tag("h1",$args)); return $this;
		};
		$this->h2 = function(...$args){
			$this->add(...$this->tag("h2",$args)); return $this;
		};
		$this->h3 = function(...$args){
			$this->add(...$this->tag("h3",$args)); return $this;
		};
		$this->h4 = function(...$args){
			$this->add(...$this->tag("h4",$args)); return $this;
		};
		$this->h5 = function(...$args){
			$this->add(...$this->tag("h5",$args)); return $this;
		};
		$this->h6 = function(...$args){
			$this->add(...$this->tag("h6",$args)); return $this;
		};
		$this->head = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("head",$arrs));
			return $this;
		};
		$this->header = function(...$args){
			$this->add(...$this->tag("header",$args)); return $this;
		};
		$this->hr = function(){ $this->add("hr"); return $this; };
		$this->html = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("html",$arrs));
			return $this;
		};
		$this->i = function(...$args){
			$this->add(...$this->tag("i",$args)); return $this;
		};
		$this->iframe = function(...$args){
			$this->add(...$this->tag("iframe",$args)); return $this;
		};
		$this->img = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("img",$arrs));
			return $this;
		};
		$this->input = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("input",$arrs));
			return $this;
		};
		$this->ins = function(...$args){
			$this->add(...$this->tag("ins",$args)); return $this;
		};
		$this->kbd = function(...$args){
			$this->add(...$this->tag("kbd",$args)); return $this;
		};
		$this->label = function(...$args){
			$this->add(...$this->tag("label",$args)); return $this;
		};
		$this->legend = function(...$args){
			$this->add(...$this->tag("legend",$args)); return $this;
		};
		$this->li = function(...$args){
			$this->add(...$this->tag("li",$args)); return $this;
		};
		$this->link = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("link",$arrs));
			return $this;
		};
		$this->main = function(...$args){
			$this->add(...$this->tag("main",$args)); return $this;
		};
		$this->map = function(...$args){
			$this->add(...$this->tag("map",$args)); return $this;
		};
		$this->mark = function(...$args){
			$this->add(...$this->tag("mark",$args)); return $this;
		};
		$this->meta = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("meta",$arrs));
			return $this;
		};
		$this->meter = function(...$args){
			$this->add(...$this->tag("meter",$args)); return $this;
		};
		$this->nav = function(...$args){
			$this->add(...$this->tag("nav",$args)); return $this;
		};
		$this->noscript = function(...$args){
			$this->add(...$this->tag("noscript",$args)); return $this;
		};
		$this->object = function(...$args){
			$this->add(...$this->tag("object",$args)); return $this;
		};
		$this->ol = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("ol",$arrs));
			return $this;
		};
		$this->optgroup = function(...$args){
			$this->add(...$this->tag("optgroup",$args)); return $this;
		};
		$this->option = function(...$args){
			$this->add(...$this->tag("option",$args)); return $this;
		};
		$this->output = function(...$args){
			$this->add(...$this->tag("output",$args)); return $this;
		};
		$this->p = function(...$args){
			$this->add(...$this->tag("p",$args)); return $this;
		};
		$this->param = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("param",$arrs));
			return $this;
		};
		$this->picture = function(...$args){
			$this->add(...$this->tag("picture",$args)); return $this;
		};
		$this->pre = function(...$args){
			$this->add(...$this->tag("pre",$args)); return $this;
		};
		$this->progress = function(...$args){
			$this->add(...$this->tag("progress",$args)); return $this;
		};
		$this->q = function(...$args){
			$this->add(...$this->tag("q",$args)); return $this;
		};
		$this->rp = function(...$args){
			$this->add(...$this->tag("rp",$args)); return $this;
		};
		$this->rt = function(...$args){
			$this->add(...$this->tag("rt",$args)); return $this;
		};
		$this->ruby = function(...$args){
			$this->add(...$this->tag("ruby",$args)); return $this;
		};
		$this->s = function(...$args){
			$this->add(...$this->tag("s",$args)); return $this;
		};
		$this->samp = function(...$args){
			$this->add(...$this->tag("samp",$args)); return $this;
		};
		$this->script = function(...$args){
			$this->add(...$this->tag("script",$args)); return $this;
		};
		$this->section = function(...$args){
			$this->add(...$this->tag("section",$args)); return $this;
		};
		$this->select = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("select",$arrs));
			return $this;
		};
		$this->small = function(...$args){
			$this->add(...$this->tag("small",$args)); return $this;
		};
		$this->source = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("source",$arrs));
			return $this;
		};
		$this->span = function(...$args){
			$this->add(...$this->tag("span",$args)); return $this;
		};
		$this->strong = function(...$args){
			$this->add(...$this->tag("strong",$args)); return $this;
		};
		$this->style = function(...$args){
			$this->add(...$this->tag("style",$args)); return $this;
		};
		$this->sub = function(...$args){
			$this->add(...$this->tag("sub",$args)); return $this;
		};
		$this->summary = function(...$args){
			$this->add(...$this->tag("summary",$args)); return $this;
		};
		$this->sup = function(...$args){
			$this->add(...$this->tag("sup",$args)); return $this;
		};
		$this->svg = function(...$args){
			$this->add(...$this->tag("svg",$args)); return $this;
		};
		$this->table = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("table",$arrs));
			return $this;
		};
		$this->tbody = function(...$args){
			$this->add(...$this->tag("tbody",$args)); return $this;
		};
		$this->td = function(...$args){
			$this->add(...$this->tag("td",$args)); return $this;
		};
		$this->template = function(...$args){
			$this->add(...$this->tag("template",$args)); return $this;
		};
		$this->textarea = function(...$args){
			$this->add(...$this->tag("textarea",$args)); return $this;
		};
		$this->tfoot = function(...$args){
			$this->add(...$this->tag("tfoot",$args)); return $this;
		};
		$this->th = function(...$args){
			$this->add(...$this->tag("th",$args)); return $this;
		};
		$this->thead = function(...$args){
			$this->add(...$this->tag("thead",$args)); return $this;
		};
		$this->time = function(...$args){
			$this->add(...$this->tag("time",$args)); return $this;
		};
		$this->title = function(...$args){
			$this->add(...$this->tag("title",$args)); return $this;
		};
		$this->tr = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("tr",$arrs));
			return $this;
		};
		$this->track = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("track",$arrs));
			return $this;
		};
		$this->u = function(...$args){
			$this->add(...$this->tag("u",$args)); return $this;
		};
		$this->ul = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("ul",$arrs));
			return $this;
		};
		$this->var = function(...$args){
			$this->add(...$this->tag("var",$args)); return $this;
		};
		$this->video = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if(is_callable($child)){
					$arrs[] = $child;
				}
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("video",$arrs));
			return $this;
		};
		$this->wbr = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->tag("wbr",$arrs));
			return $this;
		};
	}
	private function attribute(...$args){
		$arrs = [];
		foreach ($args as $var) {
			$var = $this->attr_parse($var);
			if($var){
				$input =[];
				foreach ($var as $key => $val) {
					if($val){						
						$arrs[] = "$key=\"$val\"";
						if($this->_form_sts){
							if($key=='name'|| $key=='id'|| $key=='value' || preg_match('/(.*)-data/',$key))
								$input[$key] = $val;
						}
					}else{
						$arrs[] = $key;
					}
				}
				if($input)
					$this->_form_prm[] = $input;
			}
		}
		return $arrs ? " ".implode(" ",$arrs) : null;
	}
	private function callback(...$call){
		$cfunc=[];
		foreach ($call as $func) {
			if(is_callable($func)){
				if($this->doctype){
					$this->doctype = null;
				}				
				call_user_func($func,$this);
			}
		}
	}
	private function value(...$varr){
		$value = [];
		foreach ($varr as $val) {
			$chval = $this->attr_parse($val);
			if(!is_callable($val) && !$chval){
				$value[] = trim($val);
			}
		}	
		return implode("",$value);
	}	
	private function add(...$tags)
	{
		$parent='div';
		$childs=[];
		foreach ( $tags as $num => $value) {
			if($num == 0){
				$parent = $value;
			}else{
				$childs[] = $value;
			}
		}
		$unclst = false;
		$parent = preg_replace('/[^a-z0-9]/','',strtolower($parent));
		foreach (explode("|",$this->singleton) as $key) {
			if($parent==$key){
				$unclst = true;
			}
		}
		foreach (explode("|","button|textarea|input|select") as $htmform) {
			if($parent == $htmform){
				$this->_form_sts = true;
			}
		}		
		$attr = $this->attribute(...$childs);
		$vals = $this->value(...$childs);
		if($vals){
			$vals = trim($vals);
			if($parent == 'script' || $parent == 'style'){
				$vals = $this->shrinked? $this->shrink_js($vals) : $vals ;
			}else{
				if(substr($vals,0,1) == '<' && substr($vals,-1) == '>'){
					$vals = $this->shrinked? $this->shrink_html($vals) : $vals ;
				}
			}
		}
		$arrw = $unclst? "/>" : ">$vals" ;
		$this->result[]= ($parent == 'html' ? $this->doctype : null)."<$parent$attr$arrw";
		$this->callback(...$childs);
		$this->_form_sts = false;
		if(!$unclst)
			$this->result[]= "</$parent>";
	}
	private function attr_model($parse){
		$check = [];
		if($parse && !is_callable($parse) && !is_array($parse)){
			$parse = trim($parse);
			$parrs = str_split($parse);
			if($parrs[0] == "[" && $parrs[count($parrs) - 1] == "]" ){
				$check = $parrs;
			}
		}
		return $check;
	}
	private function attr_parse($parse=null){
		$rslts = [];
		$parrs = $this->attr_model($parse);
		if($parrs){
			unset($parrs[count($parrs)-1]);
			unset($parrs[0]);
			foreach (explode("][",implode("",$parrs))as $varrs){
				$ff = explode("=",$varrs);
				$kff= trim($ff[0]);										
				if(isset($ff[1]) && $ff[1]){
					if(count($ff) >= 3){
						unset($ff[0]);
						$vff = implode("=",$ff);
					}else{
						$vff = $ff[1];
					}
					$curff = trim($vff);
					$chkff = str_replace("'","\"",$curff);
					$splff = str_split($chkff);
					if($splff[0] == '"' &&  $splff[count($splff)-1] == '"'){
						$curff = str_split($curff);
						unset($curff[count($curff)-1]);
						unset($curff[0]);
						$curff = implode("",$curff);
					}
					$rslts[$kff] = $curff ;
				}else{
					$rslts[$kff] = "" ;
				}
			}
		}
		return $rslts;
	}
	public function sq_json($arrs)
	{
		// return json with single quotes
		return preg_replace('/\"(\w+)\":/',"'\$1':",json_encode($arrs));
	}
	private function tag($t,$a){
		return array_merge([$t],$a);
	}	
	public function beautify($str){
		$html = new beautifier\_html([
			'indent_inner_html' => true,
			'indent_char' => " ",
			'indent_size' => 3,
			'wrap_line_length' => 32786,
			'unformatted' => ['code', 'pre'],
			'preserve_newlines' => false,
			'max_preserve_newlines' => 32786,
			'indent_scripts'	=> 'normal' // keep|separate|normal
		]);
		return $html->beautify($str);
	}
	public function dump($args){
		$this->style(".dump_style{word-wrap:break-word;margin-bottom:10px;padding:10px;width:100uvw;border-radius:3px;border:1px solid red;background-color:#ffe7a5}");
		$this->div("[class=dump_style]",$this->var_export($args));
		// $result = str_replace("&lt;?php&nbsp;","",highlight_string("<?php ".var_export($args,true), true));
		// $this->div("[class=dump_style]",preg_replace('/<br\/>/','\n',$result));
		return $this;
	}
	public function __call($method, $args)
    {
        if (isset($this->$method) && $this->$method instanceof \Closure) {
            return call_user_func_array($this->$method, $args);
        }
        trigger_error("Call to undefined method " . get_called_class() . '::' . $method, E_USER_ERROR);
    }
	public function tb(...$call){
		$attr = $this->attr_model($call[0])? $call[0] : null ;
		$this->tb_call=[];
		foreach ($call as $arrs) {
			if(is_array($arrs)){
				foreach ($arrs as $f) {
					if(is_callable($f))
						$this->tb_call[] = $f;
				}
			}
		}
		$this->table($attr,function($tb){
			foreach ($this->tb_call as $arr) {
				$tb->tr($arr);
			}
		});
		return $this;
	}
	public function print($beauty=true)
	{
		$html_result 	 = implode("",$this->result);
		$this->say->data = $this->_form_prm;
		$this->_form_prm = [];			
		$this->result    = [];
		if($beauty):
			return $this->beautify($html_result);
		else:
			return $html_result;
		endif;
	}
	public function shrink_html($script,$ignore="code|pre"){
		$buffer = null;
		$regex = [
			['/[\n\r]/i','/(\t+|\s+|<!--(.*?)-->)/','/\s+?>\s+?/','/\s+?<\s+?/','/>\s+</'],
			['',' ','> ',' <','><']
		];		
		if($ignore){
            $ignore = preg_split('/(<\/?'.$ignore.'[^>]*>)/', $script, null, PREG_SPLIT_DELIM_CAPTURE); 
            foreach($ignore as $i => $path)
            {
                if($i % 4 == 2)
                    $buffer .= $path; //break out <pre>...</pre> with \n's
                else 
                    $buffer .= preg_replace($regex[0],$regex[1],$path);
            }
        }else{
            $buffer = preg_replace($regex[0],$regex[1],$script);
        }
		return $buffer;
	}
	public function shrink_js($script){
		return trim(preg_replace([
            // Remove comments ("//.......")
            //'/\/\/(.*)\n/', //untested
            // Remove comments ("/*....*/")
            '~//?\s*\*[\s\S]*?\*\s*//?~',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove space > 1
            '!\s+!',
            // Remove the last semicolon
            '!;+(?=\s+)\}!',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i', 
            // Remove breakline
            '#[\n\r]#i',
            // remove space before if,var,$ html tags
            '#(?<=(,|;|\{|\}|\>))(\s+)(?=(if|var|\$|\<))#i'
		],
		["$1","$1$2"," ","}","$1$3","$1.$3","$1",""],$script));
	}
	public function convert($script){
		$script = preg_replace('/>(.*?)<\/script>/',"></script>",$script);
		$script = $this->beautify($script);
		$script = preg_replace([
			'/(<(\!(.*)|\/title)>)/',
			'/<\//',
			'/<\/(\w+)>/',
			'/\/>/',
			'/>/',
			'/\"\s/',
			'/</'
		],
		[
			'',
			"\n</",
			'});',
			');',
			' > ',
			'"~,~ ',
			'$c->('
		],$script);
		$exp=[];

		if(!function_exists('break_obj_html')){
			function break_obj_html($str,$noenclose){
				$get_space = explode('$c->(',str_replace('"','',$str));
				$space = 0;
				if(count($get_space) > 1){
					$space = substr_count($get_space[0],' ');
					$objek = explode(" ",$get_space[1]);
					$nattr = $objek[0];
					unset($objek[0]);									
					$attrs = array_filter(explode("~,~",implode(' ',$objek)));								
					$last_prop = isset($attrs[count($attrs)-1]) ? trim($attrs[count($attrs)-1]): [];
										
					$value = null;
					if($last_prop && substr($last_prop,0,1) == '>'){
						$last_prop = trim($last_prop);
						unset($attrs[count($attrs)-1]);
						$value = trim(substr($last_prop,1));
						$value = $value? "\"$value\"" : null;	
						$close = 'function($c){';
						$sprs = ',';						
					}else{
						$close = ');';
						$sprs = null;
					}
					foreach (explode("|","$noenclose|script|style|title") as $atts) {
						if($nattr == $atts){
							$close = ');';
							$sprs = null;
						}						
					}
					foreach (explode("|","a|i|u|s|b|p|li|th|td|h1|h2|h3|h4|h5|h6|span") as $atta) {
						if($nattr == $atta && $value){
							$close = ');';
							$sprs = null;
						}			
					}
					if($attrs){
						$attrs = preg_replace('/\);/','',trim(implode('][',array_map('ltrim', $attrs))));					
					}
					$str = str_repeat(" ",$space).'$c->'.$nattr.'('.($attrs? '"['.$attrs.']"'.($value ?',':null).$value.$sprs:($value?$value.$sprs:null)).$close;
				}
				
				return $str;
			}
		}

		foreach (explode("\n",$script) as $value) {
			if(trim($value))
				$exp[]=break_obj_html($value,$this->singleton);
		}
		return implode("\n",$exp);
	}	

	public function var_type($var) {
		if (is_null($var) OR $var == 'null' OR $var == 'NULL') {
			return "(Type of NULL)";
		}	 
		if (is_object($var)) {
			return "object";
		}
		if (is_array($var)) {
			if (in_array($var, array("true", "false"), true)) {
				return "boolean";
			}else{
				return "(array)";
			}
		}	 
		if (is_numeric($var)) {
			if (is_float($var)) {
				return "float" . '(' . strlen($var) . ')';
			}else{
				return "integer" . '(' . strlen($var) . ')';
			}
		}	
		if (strpos($var, 'resource') !== false AND strpos($var, 'of type ') !== false) {
			return "resource";
		}	 
		if (is_string($var)) {
			return "string" . '(' . strlen($var) . ')';
		}	 
		return "unknown";
	}
	/*function to know if Exist a resource in the text:*/
	public function var_resource($Var) {
		$wrappedArray = [];
		foreach ($Var as $k => $v) {
			if (is_array($v)) {
				$wrappedArray[$k] = $this->var_resource($v);
			} else {
				if (is_resource($v)) {
					ob_start();
					var_dump($v);
					$v = ob_get_clean();
					$v = preg_replace('~\R~', '', $v);
				}
				$wrappedArray[$k] = $v;
			}
		}
		return $wrappedArray;
	}	 
	/*Main function to Format:*/
	public function var_export($Var) {
		if (!is_array($Var)) {
			$textvar       = var_export($Var, true);
			$textvar       = preg_replace('~^ +~m', '$0$0', $textvar);
			$typeval       = $this->var_type($Var);
			$textvarArr[0] = $typeval . ' ' . var_export($Var, true);
		} else {
			/*Check Point A Start*/
			$Var     = $this->var_resource($Var);
			$textvar = var_export($Var, true);
			$textvar = preg_replace('~^ +~m', '$0$0', $textvar);
			$textvar = preg_split("~\R~", $textvar);
			$textvar = preg_replace_callback(
				"~ => \K\V+(?=,)~",
				function ($m) {
					return $this->var_type(str_replace("'", "", $m[0])) . ": {$m[0]}";
				}, $textvar
			);
			
			$textvarArr = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ')$1', ' => array ('], $textvar);
			/*Check Point A END*/
		}
		
		if (!isset($textvarArr[1])) {
			$textvar = PHP_EOL . $textvarArr[0];
		} else {
			$textvar = join(PHP_EOL, array_filter(["array ("] + $textvarArr));
		}

		/*Check Point B Start*/ 
		$textvar = preg_replace(['/(\K\v+\s+)(.+\()(\v+\s+\)],)/','/\((\s+|\n+)\)/'],['$1    $2object))[],$1],','()'], $textvar);
	 	/*Check Point B END*/
		$textvar = highlight_string("<?php ".trim($textvar), true);
		return str_replace("&lt;?php&nbsp;","",$textvar);
	}
}