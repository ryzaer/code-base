<?php
require_once 'autobase.php';
// this yaml functions need library php pecl 
// see manual below how to install
// https://www.php.net/manual/en/book.yaml.php
// // create array from string yaml
// $parsed = yaml_parse($yaml);
// var_dump($parsed);

// // create yaml from array
// $parsed = yaml_emit($parsed,YAML_UTF8_ENCODING,YAML_ANY_BREAK);
// file_put_contents('assets/yaml_test.yaml', $parsed);
// var_dump($parsed);

// // create array from stream file yaml
// $parsed = yaml_parse_file('assets/yaml_test.yaml');
// var_dump($parsed);

$addr = array(
  "given" => "Chris",
  "family"=> "Dumars",
  "address"=> array(
      "lines"=> "458 Walkman Dr.
      Suite #292",
      "city"=> "Royal Oak",
      "state"=> "MI",
      "postal"=> 48046,
    ),
);
$invoice = array (
  "invoice"=> 34843,
  "date"=> "2001-01-23",
  "bill-to"=> $addr,
  "ship-to"=> $addr,
  "product"=> array(
      array(
          "sku"=> "BL394D",
          "quantity"=> 4,
          "description"=> "Basketball",
          "price"=> 450,
        ),
      array(
          "sku"=> "BL4438H",
          "quantity"=> 1,
          "description"=> "Super Hoop",
          "price"=> 2392,
        ),
    ),
  "tax"=> 251.42,
  "total"=> 4443.52,
  "comments"=> "Late afternoon is best. Backup contact is Nancy Billsmer @ 338-4338.",
  );

// generate a YAML representation of the invoice

$yaml = yaml_emit($invoice);

$comment = Tools\formatter\table::commentAscii([
  ['@' => 1 , 'type' => 'History API','code' => 'jh778n898237kj987'],
  ['@' => 2 , 'type' => 'API Vue.js','code' => '8978972738njuyhhk'],
]);

file_put_contents("assets/invoice.yaml","$comment\n$yaml");
file_put_contents("assets/invoice.json",json_encode($invoice,JSON_PRETTY_PRINT)); // to compare the size


echo "<h3>Example JSON Output</h3>";
echo "<pre>".file_get_contents("assets/invoice.json")."</pre>";

echo "<h3>Example  YAML String Format</h3>";
$yaml = file_get_contents("assets/invoice.yaml");
echo "<pre>".$yaml."</pre>";

// create array from url yaml
$parsed = yaml_parse($yaml);
echo "<h3>Example YAML Format Dumped</h3>";
var_dump($parsed);
//header("Content-Type:text");

?>
<!--example in javascript-->
<script>
  var yamlString = '<?=$yaml?>';

  // Using js-yaml to parse YAML
  var parsedData = jsyaml.load(yamlString);

  // Accessing the parsed data
  console.log(parsedData);
</script>