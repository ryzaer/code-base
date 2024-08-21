<?php
function linked_data(){
	return json_encode([
		"@context" => "http://schema.org",
		"@type" => "CreativeWork",
		"@language" => "English",
		"@graph" => ''
	],JSON_PRETTY_PRINT);
};