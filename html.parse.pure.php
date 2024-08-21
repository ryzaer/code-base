<?php
include_once('autobase.php');

$dom = new parse\html();

// make a variable to style with heredoc
$dom->say->css = <<<CSS
body{
    font-family:'Open Sans';
    font-size:16px
}
CSS;

// make a variable to javascript with heredoc
$dom->say->js = <<<JAVASCRIPT
console.log('active javascript')
JAVASCRIPT;

$dom->say->title = "SELAMAT DATANG DI BUMI KHATULISTIWA"; 
$dom->say->meta = [
    "[charset=UTF-8]",
    "[name=viewport][content=width=device-width,initial-scale=1.0 ]"
]; 

// css,js & values in tags will shrinked
$dom->shrinked = true;

// HTML Layer start here
$dom->html("[lang=id-ID][itemtype=http://schema.org/SearchResultsPage]",function($c){
    $c->head(function($c){
        /* array meta */
        foreach ( $c->say->meta as $key => $arr ){ 
            $c->meta($arr); 
        }
        /* title */       
        $c->title($c->say->title)
              ->link("[rel=stylesheet][type=text/css][href=https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext]")
              ->link("[rel=stylesheet][type=text/css][href=https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css]")
              ->style($c->say->css)
              ->script("[type=application/javascript]",$c->say->js);
    });
    $c->body(function($c){
        $c->header(function($c){
            $c->div(function($c){
                $c->h2($c->say->title)
                    ->h3("ini adalah class untuk mempermudah proses <i>regenerate content</i> &nbsp; berbasis web secara dinamis");
            });            
          })
          ->main("[id=app-content]",function($c){
            $c->form("[method=post]",function($c){
                $c->div("[style=margin-left:5px]",function($c){
                    $c->p('contoh form isian <s><b>with javascript</b></s>');
                  })->div("[style=margin:0px 5px 5px 5px]",function($c){
                    $c->label("property input")->br()
                        ->input("[type=text][name=meno][dasar-data=json]")
                        ->input("[id=embuh][class=sm-12 md-12 l-12][type=text][name=oke_man]")->br();
                  })->div("[style=margin:0px 5px 5px 5px]",function($c){
                    $c->label("property textarea")->br()
                        ->textarea("[type=text][name=emok][dasar-data=json]","example text area")->br();
                  })->div("[style=margin-left:5px]",function($c){
                    $c->label("property button")->br()
                        ->button("eksekusi");
                  });
              });
            $c->div('[id=tambah1]',function($c){
                $c->style("[type=text/x-css]",".contoh{position:absolute}");
            },function($c){
                $c->script("[type=application/x-json]","{tambah:'script'}");
            });
          })
          ->footer("[id=app-footer]");        
    });
});

//all script will minified in d browser if false (true default)
echo $dom->print();

//var_dump($_POST);
//var_dump($dom->say->data);