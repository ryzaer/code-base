<?php
include_once('autobase.php');

$dom = new parse\html();

// make a variable to style with heredoc
$dom->say = (object)[];
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
              ->link("[rel=stylesheet][href=https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css][integrity=sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N][crossorigin=anonymous]")
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
            });
        });
        foreach ([
            "[src=https://code.jquery.com/jquery-3.6.1.slim.min.js][integrity=sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=][crossorigin=anonymous]",
            "[src=https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js][integrity=sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct][crossorigin=anonymous]"
        ] as $src) {
            $c->script($src);
        }
        $c->footer("[id=app-footer]");        
    });
});

//all script will minified in d browser if false (true default)
echo $dom->print();

//var_dump($_POST);
//var_dump($dom->say->data);