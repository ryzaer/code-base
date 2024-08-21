<?php
require_once 'autobase.php';
Info\pandemic::covid(function($get){
    $get->kalbar	= 'https://dinkes.kalbarprov.go.id/sebaran-covid19/';
    $get->pontianak = 'https://covid19.pontianakkota.go.id/';	
    $get->propinsi	= 'https://en.wikipedia.org/wiki/Statistics_of_the_COVID-19_pandemic_in_Indonesia';
    $get->indonesia = 'https://corona.lmao.ninja/v2/countries/indonesia';
    $get->global 	= 'https://corona.lmao.ninja/v2/all';
    $get->export = __DIR__."/assets/info.covid.v2.json";    
});?>
