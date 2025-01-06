<?php
require_once 'autoload.php';
// create env data
// Array data
$data = array('key1' => 'value1', 'key2' => 'value2');

// Serialize the array into a string
$serializedData = json_encode($data);
$sql_prms = implode(";",[
    "mysql:host=localhost",
    "port=3315",
    "johnDoe",
    "#@6%%^-HG^",
    "dbinvoice"
]);
$blob_jpg = 'data:image/jpeg;'.base64_encode(file_get_contents("assets/images/arini.jpg"));
$add_data = <<<ENV
APP_ENV=dev
OWNER_IMG=$blob_jpg
DATABASE=$sql_prms
MODULE_ENABLED=true
NUMBER_LITERAL=0
NULL_VALUE=null
ENV;

// Store the serialized data in the environment variable file
file_put_contents('assets/.env', "$add_data\nDATA_JSON=$serializedData");


// using class
use DevCoder\DotEnv;

(new DotEnv('assets/.env'))->load();

echo getenv('DATA_JSON');
var_dump($_ENV);

echo "<h3>simple example create env</h3>";
print "<pre>".Tools\formatter\env::emit([
    "ashdkjhdkj jkhaskjd" => [1,2,3],
    "kjaksdj jahsdkjh " => 123,
    "klalskjaskjd" => null,
    "lkaskljlk"=> false
])."</pre>";

use Tools\formatter\yaml;
yaml::title("filosofi kopi hajar saja agar pecah bibirnya\nlagian apa ada orang jenis kayak gitu?",
[
    ['@' => 1 , 'type' => 'History API','code' => 'jh778n898237kj987'],
    ['@' => 2 , 'type' => 'API Vue.js','code' => '8978972738njuyhhk'],
]);
yaml::data('key1',function($fn){
    $fn->add(function($fn){
        $fn->add("anusha1","aksjdlkj");
        $fn->add("anusha2","aksjdlkj");
        $fn->add("anusha3","aksjdlkj");
        $fn->info("anusha1 tidak bisa di tinggalkan");
        $fn->add("anusha4","aksjdlkj");
    });
    $fn->add(function($fn){
        $fn->add("anusha1","aksjdlkj");
        $fn->add("anusha2","aksjdlkj");
        $fn->info("llsakjdlkjasdlkasdjlaskjasl\nlaksdj laskjasdl\nkaljlkja");
        $fn->add("anusha3","aksjdlkj");
        $fn->add("anusha4","aksjdlkj");
    });

});
yaml::data('key2',function($fn){
    $fn->add("anusha",function($fn){
        $fn->add(function($fn){
            $fn->add("anusha1","aksjdlkj");
            $fn->add("anusha2","aksjdlkj");
            $fn->info([
                ['@' => 1 , 'http' => 'cdn.api.com','code' => 'jh778n898237kj987'],
                ['@' => 2 , 'http' => 'cdn.cloudestrans.com','code' => '8978972738njuyhhk'],
              ]);
            $fn->add("anusha3","aksjd
            lkj");
            $fn->add("anusha4","aksjdlkj");
        });
        
        $fn->add(function($fn){
            $fn->add("anusha1",true);
            $fn->add("anusha2",date("Y-m-d"));            
            $fn->add("anusha3","aksjdlkj");
            $fn->add("anusha4","aksjdlkj");
        });
        
    });
});

print "<pre>".yaml::emit()."</pre>";