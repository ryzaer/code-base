<?php
namespace setasign\tools;

class PDFJavascript extends \setasign\Fpdi\FpdiProtection {

    protected $javascript;
    protected $n_js;
    var $script='';

    function IncludeJS($script) {
        $this->javascript = $script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS '.$this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }

    function _JScolor($color)
    {
        static $aColors=array('transparent','black','white','red','green','blue','cyan','magenta',
            'yellow','dkGray','gray','ltGray');

        if(substr($color,0,1)=='#')
        {
            return sprintf("['RGB',%.3f,%.3f,%.3f]", hexdec(substr($color,1,2))/255,
                hexdec(substr($color,3,2))/255, hexdec(substr($color,5,2))/255);
        }
        if(!in_array($color,$aColors))
            $this->Error('Invalid color: '.$color);
        return 'color.'.$color;
    }

    function _addfield($type,$name,$x,$y,$w,$h,$prop)
    {
        $k=$this->k;
        $this->script.=sprintf("f=addField('%s','%s',%d,[%.2f,%.2f,%.2f,%.2f]);",
            $name,$type,$this->PageNo()-1,$x*$k,($this->h-$y)*$k+1,($x+$w)*$k,($this->h-$y-$h)*$k+1);
        $this->script.='f.textSize='.$this->FontSizePt.';';
        if(isset($prop['value']))
            $this->script.="f.value='".addslashes($prop['value'])."';";
        if(isset($prop['TextColor']))
            $this->script.='f.textColor='.$this->_JScolor($prop['TextColor']).';';
        if(isset($prop['FillColor']))
            $this->script.='f.fillColor='.$this->_JScolor($prop['FillColor']).';';
        if(isset($prop['BorderColor']))
            $this->script.='f.strokeColor='.$this->_JScolor($prop['BorderColor']).';';
        if(isset($prop['BorderStyle']))
            $this->script.="f.borderStyle='".$prop['BorderStyle']."';";
        $this->x+=$w;
    }

    function TextField($name,$w,$h,$prop=array())
    {
        $this->_addfield('text',$name,$this->x,$this->y,$w,$h,$prop);
        if(isset($prop['multiline']) and $prop['multiline'])
            $this->script.='f.multiline=true;';
    }

    function ComboBox($name,$w,$h,$values,$prop=array())
    {
        $this->_addfield('combobox',$name,$this->x,$this->y,$w,$h,$prop);
        $s='';
        foreach($values as $value)
            $s.="'".addslashes($value)."',";
        $this->script.='f.setItems(['.substr($s,0,-1).']);';
    }

    function CheckBox($name,$w,$checked=false,$prop=array())
    {
        $prop['value']=($checked ? 'Yes' : 'Off');
        if(!isset($prop['BorderColor']))
            $prop['BorderColor']='black';
        $this->_addfield('checkbox',$name,$this->x,$this->y,$w,$w,$prop);
    }

    function Button($name,$w,$h,$caption,$action,$prop=array())
    {
        if(!isset($prop['BorderColor']))
            $prop['BorderColor']='black';
        $prop['BorderStyle']='beveled';
        $this->_addfield('button',$name,$this->x,$this->y,$w,$h,$prop);
        $this->script.="f.buttonSetCaption('".addslashes($caption)."');";
        $this->script.="f.setAction('MouseUp','".addslashes($action)."');";
        $this->script.="f.highlight='push';";
        $this->script.='f.print=false;';
    }
}