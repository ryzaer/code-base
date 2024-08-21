<?php
require_once 'autoload.php';
// $test = [
//     ["ksj\nk\n\n\n\ndj","asld\nj\nldks","asldjldks"],
//     ["ksjkdj","asldldks","asldjldks"]
// ];
// $tb=[];
// foreach ($test as $vars) {
//     $arr=[];
//     $wdt= 0;
//     foreach ($vars as $j => $value) {
//         $col=preg_split('/\n/s',$value);
//         if (($width ?? 0) < ($width = count($col))  ) {
//             $wdt = $width;					
//         }
//         $arr[]= $col;
//     }
//     if($wdt){
//         for ($i=0; $i < $wdt; $i++) { 
//             $ts=[];
//             foreach ($arr as $v) {
//                 $ts[]=isset($v[$i])?$v[$i]:"";
//             }
//             $tb[]=$ts;
//         }
//     }else{
//         $tb[]=$vars;
//     }
// }
// var_dump($tb);
// die;

$comments = [
    ["num" => 1, "name" => "something here", "description" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit.\nMagni deserunt necessitatibus maxime ut nobis est numquam\nconsequuntur omnis sapiente repudiandae, aspernatur\nvoluptas cum vero ipsa nulla recusandae quia, beatae dolor.", "value" => 3,"rogue" => "das"],
    ["num" => 2, "name" => "and a boolean", "description" => "this is another thing", "value" => true,"rogue" => "ram"],
    ["num" => 3, "name" => "a duck and a dog", "description" => "Lorem ipsum dolor sit.", "value" => "truly weird","rogue" => "van"],
    ["num" => 4, "name" => "with rogue field", "description" => "Link 1:\nhttps://github.com/search?q=php+yaml&type=repositories&p=2\nLink 2:\nhttps://github.com/symfony/yaml", "value" => false, "rogue" => "nie"],
    ["num" => 5, "name" => "some kind of array", "description" => "array i tell you", "value" => [3, 4, 'banana'], "rogue" => "ars"],
    ["num" => 6, "name" => "can i handle null?", "description" => function(){}, "value" => null,"rogue" => "dar"],
    ["num" => 7, "name" => "unknown", "description" => "amet consectetur adipisicing elit", "value" => "","rogue" => "dar"],
];
// $table = Tools\formatter\table::Ascii($comments);
$table = Tools\formatter\table::Ascii("#",$comments);
// $text = Tools\formatter::yaml("test\n$table",function($s){
//     $s->data("status","data");
//     // $s->push("result",['admin' => "data",'contact' => 'akjslj','firm' => "kajshdkjh"]);
//     $s->push("result",['admin','contact','firm']);
//     $s->info("ini key 2");
//     $s->data("version",function($s){
//         $s->data('number','code1');
//         $s->data('data','code2');   
//         $s->info("ini var 3");    
//         $s->data('varian',function($s){
//             //$s->data(['admin','contact','firm']);
//             $s->data(function($s){
//                 $s->info("print query"); 
//                 $s->data('array1','code2');
//                 $s->data('array2','code2');
//                 $s->data('array3','code2');
//                 $s->data('array4','code2');
//             });
//             $s->data(function($s){
//                 $s->data('array1','code2');
//                 $s->data('array2','code2');
//                 $s->data('array3','code2');
//                 $s->data('array4','code2');
//             });
//         });
//         //
//     });
    
// })->emit();

$array[] = 'Sequence item';
$array['The Key'] = 7897987;
$array[] = array('A sequence','of a sequence');
$array[] = array('first' => 'A sequence','second' => 'of mapped values');
$array['Mapped'] = array('A sequence','which is mapped');
$array['A Note']["test1"][] = 'Since this text is awesome, what do you think?';
$array['A Note']["test1"][] = 'how about adding more data here...';
$array['A Note']["test1"][] = [
    ["array1","array2","array3"],
    ["array4","array5","array6"],
];
$array['A Note']["test2"] = 'What if your text is too long?';
$array['Another Note'] = 'If that is the case, the dumper will probably fold your text by using a block. The trick is that we overrode the default indent, 2, to 4 and the default wordwrap, 40, to 60.';
$array['The trick?'] = 'This trick will work as long as the text is not use single quotes \' sign like this, or the text will be set as string';
$array['lorem example'] = 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Inventore, labore nihil amet aspernatur assumenda unde beatae temporibus alias deleniti eligendi rem veritatis. Optio excepturi praesentium necessitatibus, vitae exercitationem labore suscipit. There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which do not look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there is not anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
$array['Old Dog'] = "And if you want\n to preserve line breaks, \ngo ahead! i recomanded to use fat free framework\nbcz it has dynamic GET|POST statement routers";
$array['key:withcolon'] = "Should support this to";

$string = \__fn::yaml($array);
print "<pre><h3>(yaml::emit) array to string:</h3>\n$table\n$string</pre>";

print '<pre><h3>(yaml::parse) string to json :</h3><br/>'.json_encode(\__fn::yaml($string),JSON_PRETTY_PRINT).'</pre>';
