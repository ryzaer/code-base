<?php
    require_once 'autobase.php';
    $array = [
        ["num" => 1  ,"name" => "something here"        ,"description" => "a description here to see"                                  ,"value" => 3                  ,"salary"=> "{¥ -128383}" ,"info" => "paid"],
        ["num" => 2  ,"name" => "and a boolean"         ,"description" => "this is another thing"                                      ,"value" => true               ,"salary"=> "{Rp. 13265523}"   ,"info" => "paid"],
        ["num" => 3  ,"name" => "a duck and a dog"      ,"description" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit.\nMagni deserunt necessitatibus maxime ut nobis est numquam\nconsequuntur omnis sapiente repudiandae, aspernatur\nvoluptas cum vero ipsa nulla recusandae quia, beatae dolor."  ,"value" => "truly weird"      ,"salary"=> "{\$ 18122}"  ,"info" => "paid"],
        ["num" => 4  ,"name" => "with rogue field"      ,"description" => "Link 1:\nhttps://github.com/search?q=php+yaml&type=repositories&p=2\nLink 2:\nhttps://github.com/symfony/yaml"                                      ,"value" => false              ,"salary"=> "{Rp. 1821}"   ,"info" => "paid"],
        ["num" => 5  ,"name" => "some kind of array"    ,"description" => "array i tell you"                                           ,"value" => [3, 4, 'banana']   ,"salary"=> "{\$ 2355}"   ,"info" => "paid"],
        ["num" => 6  ,"name" => "can i handle null?"    ,"description" => function(){}                                                 ,"value" => null               ,"salary"=> "{\$ 232}"    ,"info" => "paid"],
        ["num" => 7  ,"name" => "unknown"               ,"description" => ""                                                           , "value" => ""                ,"salary"=> "{\$ 0}"      ,"info" => "paid"],
    ];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <pre><?=\ascii\table::emit($array);?></pre>
    <!-- example adding comments -->
    <pre><?=\ascii\table::emit('#',$array);?></pre>
</body>
</html>