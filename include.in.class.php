<?php
require_once('autoload.php');
$test = inClassInclude::element(); 
$test->renderElement(function($wdb){
    $wdb->add->page = true;
    $wdb->add->html = '<h4>navbar</h4>';
});
