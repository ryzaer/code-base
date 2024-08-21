<?php 
namespace parse;

class html {
	public  $var, $htm, $url;
	private $prv;
	
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

		$this->var = (object)[];
		$this->url = (object)[];
		$this->url->depth = 0;

		$this->htm = (object)[];
		$this->htm->doctype		= "<!DOCTYPE html>";
		/* for minifying json,js,css&html scripts strings ex. <tags>(will be minify)</tags>*/
		$this->htm->shrinked 	= true;

		$this->prv = (object)[];
		$this->prv->ignore		= "blockquote|textarea|code|pre";
		$this->prv->form_sts	= false;			
		$this->prv->form_prm	= [];
		$this->prv->result		= [];			
		$this->prv->singleton   = "area|base|br|col|command|embed|hr|img|isindex|input|keygen|link|meta|param|source|track|wbr";

		// HTML5 anonymous tag functions
		// https://www.w3schools.com/tags/;
			
		$this->a = function(...$args){
			$this->add(...$this->attr_param("a",$args)); return $this;
		};
		$this->abbr = function(...$args){
			$this->add(...$this->attr_param("abbr",$args)); return $this;
		};
		$this->address = function(...$args){
			$this->add(...$this->attr_param("address",$args)); return $this;
		};
		$this->area = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("area",$arrs));
			return $this;
		};
		$this->article = function(...$args){
			$this->add(...$this->attr_param("article",$args)); return $this;
		};
		$this->aside = function(...$args){
			$this->add(...$this->attr_param("aside",$args)); return $this;
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
			$this->add(...$this->attr_param("audio",$arrs));
			return $this;
		};
		$this->b = function(...$args){
			$this->add(...$this->attr_param("b",$args)); return $this;
		};
		$this->base = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("base",$arrs));
			return $this;
		};
		$this->bdi = function(...$args){
			$this->add(...$this->attr_param("bdi",$args)); return $this;
		};
		$this->bdo = function(...$args){
			$this->add(...$this->attr_param("bdo",$args)); return $this;
		};
		$this->blockquote = function(...$args){
			$this->add(...$this->attr_param("blockquote",$args)); return $this;
		};
		$this->body = function(...$args){
			$this->add(...$this->attr_param("body",$args)); return $this;
		};
		$this->br = function($num=1){
			for($n=0;$n<abs($num);$n++){
				$this->add("br");
			}
			return $this;
		};
		$this->button = function(...$args){
			$this->add(...$this->attr_param("button",$args)); return $this;
		};
		$this->canvas = function(...$args){
			$this->add(...$this->attr_param("canvas",$args)); return $this;
		};
		$this->caption = function(...$args){
			$this->add(...$this->attr_param("caption",$args)); return $this;
		};
		$this->center = function(...$args){
			$this->add(...$this->attr_param("center",$args)); return $this;
		};
		$this->cite = function(...$args){
			$this->add(...$this->attr_param("cite",$args)); return $this;
		};
		$this->code = function(...$args){
			$this->add(...$this->attr_param("code",$args)); return $this;
		};
		$this->col = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("col",$arrs));
			return $this;
		};
		$this->colgroup = function(...$args){
			$this->add(...$this->attr_param("colgroup",$args)); return $this;
		};
		$this->data = function(...$args){
			$this->add(...$this->attr_param("data",$args)); return $this;
		};
		$this->datalist = function(...$args){
			$this->add(...$this->attr_param("datalist",$args)); return $this;
		};
		$this->dd = function(...$args){
			$this->add(...$this->attr_param("dd",$args)); return $this;
		};
		$this->del = function(...$args){
			$this->add(...$this->attr_param("del",$args)); return $this;
		};
		$this->details = function(...$args){
			$this->add(...$this->attr_param("details",$args)); return $this;
		};
		$this->dfn = function(...$args){
			$this->add(...$this->attr_param("dfn",$args)); return $this;
		};
		$this->dialog = function(...$args){
			$this->add(...$this->attr_param("dialog",$args)); return $this;
		};
		$this->div = function(...$args){
			$this->add(...$this->attr_param("div",$args)); return $this;
		};
		$this->dl = function(...$args){
			$this->add(...$this->attr_param("dl",$args)); return $this;
		};
		$this->dt = function(...$args){
			$this->add(...$this->attr_param("dt",$args)); return $this;
		};
		$this->em = function(...$args){
			$this->add(...$this->attr_param("em",$args)); return $this;
		};
		$this->embed = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("embed",$arrs));
			return $this;
		};
		$this->fieldset = function(...$args){
			$this->add(...$this->attr_param("fieldset",$args)); return $this;
		};
		$this->figcaption = function(...$args){
			$this->add(...$this->attr_param("figcaption",$args)); return $this;
		};
		$this->figure = function(...$args){
			$this->add(...$this->attr_param("figure",$args)); return $this;
		};
		$this->footer = function(...$args){
			$this->add(...$this->attr_param("footer",$args)); return $this;
		};
		$this->form = function(...$args){
			$this->add(...$this->attr_param("form",$args)); return $this;
		};
		$this->h1 = function(...$args){
			$this->add(...$this->attr_param("h1",$args)); return $this;
		};
		$this->h2 = function(...$args){
			$this->add(...$this->attr_param("h2",$args)); return $this;
		};
		$this->h3 = function(...$args){
			$this->add(...$this->attr_param("h3",$args)); return $this;
		};
		$this->h4 = function(...$args){
			$this->add(...$this->attr_param("h4",$args)); return $this;
		};
		$this->h5 = function(...$args){
			$this->add(...$this->attr_param("h5",$args)); return $this;
		};
		$this->h6 = function(...$args){
			$this->add(...$this->attr_param("h6",$args)); return $this;
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
			$this->add(...$this->attr_param("head",$arrs));
			return $this;
		};
		$this->header = function(...$args){
			$this->add(...$this->attr_param("header",$args)); return $this;
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
			$this->add(...$this->attr_param("html",$arrs));
			return $this;
		};
		$this->i = function(...$args){
			$this->add(...$this->attr_param("i",$args)); return $this;
		};
		$this->iframe = function(...$args){
			$this->add(...$this->attr_param("iframe",$args)); return $this;
		};
		$this->img = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("img",$arrs));
			return $this;
		};
		$this->input = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("input",$arrs));
			return $this;
		};
		$this->ins = function(...$args){
			$this->add(...$this->attr_param("ins",$args)); return $this;
		};
		$this->kbd = function(...$args){
			$this->add(...$this->attr_param("kbd",$args)); return $this;
		};
		$this->label = function(...$args){
			$this->add(...$this->attr_param("label",$args)); return $this;
		};
		$this->legend = function(...$args){
			$this->add(...$this->attr_param("legend",$args)); return $this;
		};
		$this->li = function(...$args){
			$this->add(...$this->attr_param("li",$args)); return $this;
		};
		$this->link = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("link",$arrs));
			return $this;
		};
		$this->main = function(...$args){
			$this->add(...$this->attr_param("main",$args)); return $this;
		};
		$this->map = function(...$args){
			$this->add(...$this->attr_param("map",$args)); return $this;
		};
		$this->mark = function(...$args){
			$this->add(...$this->attr_param("mark",$args)); return $this;
		};
		$this->marquee = function(...$args){
			$this->add(...$this->attr_param("marquee",$args)); return $this;
		};
		$this->meta = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("meta",$arrs));
			return $this;
		};
		$this->meter = function(...$args){
			$this->add(...$this->attr_param("meter",$args)); return $this;
		};
		$this->nav = function(...$args){
			$this->add(...$this->attr_param("nav",$args)); return $this;
		};
		$this->noscript = function(...$args){
			$this->add(...$this->attr_param("noscript",$args)); return $this;
		};
		$this->object = function(...$args){
			$this->add(...$this->attr_param("object",$args)); return $this;
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
			$this->add(...$this->attr_param("ol",$arrs));
			return $this;
		};
		$this->optgroup = function(...$args){
			$this->add(...$this->attr_param("optgroup",$args)); return $this;
		};
		$this->option = function(...$args){
			$this->add(...$this->attr_param("option",$args)); return $this;
		};
		$this->output = function(...$args){
			$this->add(...$this->attr_param("output",$args)); return $this;
		};
		$this->p = function(...$args){
			$this->add(...$this->attr_param("p",$args)); return $this;
		};
		$this->param = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("param",$arrs));
			return $this;
		};
		$this->picture = function(...$args){
			$this->add(...$this->attr_param("picture",$args)); return $this;
		};
		$this->pre = function(...$args){
			$this->add(...$this->attr_param("pre",$args)); return $this;
		};
		$this->progress = function(...$args){
			$this->add(...$this->attr_param("progress",$args)); return $this;
		};
		$this->q = function(...$args){
			$this->add(...$this->attr_param("q",$args)); return $this;
		};
		$this->rp = function(...$args){
			$this->add(...$this->attr_param("rp",$args)); return $this;
		};
		$this->rt = function(...$args){
			$this->add(...$this->attr_param("rt",$args)); return $this;
		};
		$this->ruby = function(...$args){
			$this->add(...$this->attr_param("ruby",$args)); return $this;
		};
		$this->s = function(...$args){
			$this->add(...$this->attr_param("s",$args)); return $this;
		};
		$this->samp = function(...$args){
			$this->add(...$this->attr_param("samp",$args)); return $this;
		};
		$this->script = function(...$args){
			$this->add(...$this->attr_param("script",$args)); return $this;
		};
		$this->section = function(...$args){
			$this->add(...$this->attr_param("section",$args)); return $this;
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
			$this->add(...$this->attr_param("select",$arrs));
			return $this;
		};
		$this->small = function(...$args){
			$this->add(...$this->attr_param("small",$args)); return $this;
		};
		$this->source = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("source",$arrs));
			return $this;
		};
		$this->span = function(...$args){
			$this->add(...$this->attr_param("span",$args)); return $this;
		};
		$this->strong = function(...$args){
			$this->add(...$this->attr_param("strong",$args)); return $this;
		};
		$this->style = function(...$args){
			$this->add(...$this->attr_param("style",$args)); return $this;
		};
		$this->sub = function(...$args){
			$this->add(...$this->attr_param("sub",$args)); return $this;
		};
		$this->summary = function(...$args){
			$this->add(...$this->attr_param("summary",$args)); return $this;
		};
		$this->sup = function(...$args){
			$this->add(...$this->attr_param("sup",$args)); return $this;
		};
		$this->svg = function(...$args){
			$this->add(...$this->attr_param("svg",$args)); return $this;
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
			$this->add(...$this->attr_param("table",$arrs));
			return $this;
		};
		$this->tbody = function(...$args){
			$this->add(...$this->attr_param("tbody",$args)); return $this;
		};
		$this->td = function(...$args){
			$this->add(...$this->attr_param("td",$args)); return $this;
		};
		$this->template = function(...$args){
			$this->add(...$this->attr_param("template",$args)); return $this;
		};
		$this->textarea = function(...$args){
			$this->add(...$this->attr_param("textarea",$args)); return $this;
		};
		$this->tfoot = function(...$args){
			$this->add(...$this->attr_param("tfoot",$args)); return $this;
		};
		$this->th = function(...$args){
			$this->add(...$this->attr_param("th",$args)); return $this;
		};
		$this->thead = function(...$args){
			$this->add(...$this->attr_param("thead",$args)); return $this;
		};
		$this->time = function(...$args){
			$this->add(...$this->attr_param("time",$args)); return $this;
		};
		$this->title = function(...$args){
			$this->add(...$this->attr_param("title",$args)); return $this;
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
			$this->add(...$this->attr_param("tr",$arrs));
			return $this;
		};
		$this->track = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("track",$arrs));
			return $this;
		};
		$this->u = function(...$args){
			$this->add(...$this->attr_param("u",$args)); return $this;
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
			$this->add(...$this->attr_param("ul",$arrs));
			return $this;
		};
		$this->var = function(...$args){
			$this->add(...$this->attr_param("var",$args)); return $this;
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
			$this->add(...$this->attr_param("video",$arrs));
			return $this;
		};
		$this->wbr = function(...$args){
			$arrs = [];
			foreach ($args as $child){
				if($this->attr_model($child)){
					$arrs[] = $child;
				}
			}
			$this->add(...$this->attr_param("wbr",$arrs));
			return $this;
		};
	}
	private function attribute(...$args){
		$arrs = [];
		foreach ($args as $var) {
			$var = $this->attr_parse($var);
			// collect all form data attributes
			if($var){
				$input =[];
				$typemail = false;
				foreach ($var as $key => $val) {
					$autocom  = $key == 'autocomplete' ? true : false;
					if($val){						
						$arrs[] = "$key=\"$val\"";
						if($this->prv->form_sts){
							if($key=='name'|| $key=='id'|| $key=='value' || preg_match('/data(\-)?/',$key))
								$input[$key] = $val;								
						}
						if($val == 'email' || $val == 'password'){
							if($this->isinput){
								!$autocom or $typemail = true;
							}
						}						
					}else{
						$arrs[] = $key;
					}
				}
				$arrs = $typemail ? array_merge($arrs,["autocomplete"]) : $arrs;				
				if($input)
					$this->prv->form_prm[] = $input;
			}
		}
		$this->isinput = false;
		return $arrs ? " ".implode(" ",$arrs) : null;
	}
	private function callback(...$call){
		$cfunc=[];
		foreach ($call as $func) {
			if(is_callable($func)){
				if($this->htm->doctype){
					$this->htm->doctype = null;
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
		foreach (explode("|",$this->prv->singleton) as $key) {
			if($parent==$key){
				$unclst = true;
			}
		}

		$this->isinput = false;
		foreach (explode("|","button|textarea|input|select") as $htmform) {
			if($parent == $htmform){
				$this->prv->form_sts = true;
				if($htmform == 'input'){
					// give true for checking attribute
					$this->isinput = true;
				}
			}			
		}		
		$attr = $this->attribute(...$childs);
		$vals = $this->value(...$childs);
		$vald = false ;
		if($vals){
			$vals = trim($vals);
			if($parent == 'script'){
				$vals = $this->htm->shrinked? $this->shrink_js($vals) : $vals ;
			}else if($parent == 'style'){
				$vals = $this->htm->shrinked? $this->shrink_css($vals) : $vals;
			}else{
				$no_shrink = false;
				foreach (explode("|",$this->prv->ignore) as $tag_ignore) {
					if($tag_ignore == $parent){
						$no_shrink = true;
					}
				}
				$no_shrink or $vals = $this->htm->shrinked? $this->shrink_html($vals) : $vals ;
				$vald = true ;
			}
		}

		$arrw = $unclst? "/>" : ">$vals" ;
		$this->prv->result[]= ($parent == 'html' ? $this->htm->doctype : null)."<$parent$attr$arrw";
		$this->callback(...$childs);
		$this->prv->form_sts = false;
		$unclst or $this->prv->result[]= "</$parent>";
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
	public function addElementByTag($tag,$attr=null,$inner=null,$closure=true){
		preg_match("/a|abbr|address|area|article|aside|audio|b|base|bdi|bdo|blockquote|body|br|button|canvas|caption|center|cite|code|col|colgroup|data|datalist|dd|del|details|dfn|dialog|div|dl|dt|em|embed|fieldset|figcaption|figure|footer|form|h1|h2|h3|h4|h5|h6|head|header|hr|html|i|iframe|img|input|ins|kbd|label|legend|li|link|main|map|mark|meta|meter|nav|noscript|object|ol|optgroup|option|output|p|param|picture|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|source|span|strong|style|sub|summary|sup|svg|table|tbody|td|template|textarea|tfoot|th|thead|time|title|tr|track|u|ul|var|video|wbr/",$tag,$match);
		$tag_name = isset($match[0]) && $match[0] == $tag ? true : false ;
		$attr = $attr? " ".$attr." " : null;
		if(!$tag_name){
			$closure = $closure ? "</".$tag.">" : null;
			$this->prv->result[] = $this->depth("<$tag$attr>$inner$closure");
		}else{
			$this->dump("$tag function tag element already exiest!");
		}
		return $this;
	}
	public function sq_json($arrs)
	{
		// return json with single quotes
		return preg_replace('/\"(\w+)\":/',"'\$1':",json_encode($arrs));
	}
	private function attr_param($t,$a){
		return array_merge([$t],$a);
	}	
	public function beautify($str){
		$html = new beautifier\_html([
			'indent_inner_html' => true,
			'indent_char' => " ",
			'indent_size' => 4,
			'wrap_line_length' => 32786,
			'unformatted' => explode("|",$this->prv->ignore),
			'preserve_newlines' => false,
			'max_preserve_newlines' => 32786,
			'indent_scripts'	=> 'normal' // keep|separate|normal
		]);
		return $html->beautify($str);
	}
	private function depth($html){
		$spar = str_repeat('\.\.\/',$this->url->depth);	
		// list url scheme, see here https://en.wikipedia.org/wiki/List_of_URI_schemes
		$schm = implode("|",[
			"chrome-extension","attachment","javascript","telephone","resource","adiumxtra","callto","chrome","mailto","telnet","payto","proxy","http(s)?",
			"aaa([s])?","data","file","acap","afp","aim","apt","ftp","nfs","smb","ssh","tcp","urn","aw"
		]);
		$prg1 = $this->url->depth > 0 ? [			
			'/(href|src|url)="(.+?)"/is',
			"/=\"$spar(($schm):|\/\/|#)/is",		
			'/="'.$spar.'\.?\//is',		
			'/(href|src|url)=""/is',		
			'/url\(\'(.+?)\'\)/is',	
			"/url\(\'$spar(($schm):|\/\/|#)/is",		
			"/url\(\'$spar(\.?\/)/is"
		]:[];
		$prg2 = $this->url->depth > 0 ? [			
			'$1="'.stripslashes($spar).'$2"',
			'="$1',
			'="'.stripslashes($spar),
			'$1="./"',
			'url(\''.stripslashes($spar).'$1\')',
			'url(\'$1',
			'url(\''.stripslashes($spar),
		]:[];
		$html = preg_replace(array_merge(['/\.(js(on)?|s?css)\?_\=\$/is'],$prg1),array_merge(['.$1?_='.time()],$prg2),$html);
		return $html;
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
		if(!is_a($this->$method,'Closure')){
			die("BANGKE");
		}else{
			trigger_error("Call to undefined method " . get_called_class() . '::' . $method, E_USER_ERROR);
		}
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
		$this->prv->result = implode("",$this->prv->result);	
		$implodeHtmlResult = $this->depth($this->prv->result);
		$this->prv->result = [];
		if($beauty):
			return $this->beautify($implodeHtmlResult);
		else:
			return $implodeHtmlResult;
		endif;
		
	}
	public function shrink_js($script){
		return \__fn::minify($script);
	}
	public function shrink_html($script,$bool=false){
		$obj = (object)[];
		$obj->ignore = $bool? $bool:$this->prv->ignore;
		return \__fn::minify('html',$obj,$script);
	}	
	public function shrink_css($script){
		return \__fn::minify('css',$script);
	}
	public function convert($script){
		$script = preg_replace('/>(.*?)<\/script>/',"></script>",$script);
		$script = $this->beautify($script);
		$script = preg_replace(['/(<(\!(.*)|\/title)>)/','/<\//','/<\/(\w+)>/','/\/>/','/>/','/\"\s/','/</'],['',"\n</",'});',');',' > ','"~,~ ','$c->('],$script);
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
				$exp[]=break_obj_html($value,$this->prv->singleton);
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