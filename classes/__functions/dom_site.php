<?php
function dom_site($str,$lowercase=true,$stripRN=true,$forceTagsClosed=true)
{
	foreach ([
        'DEFAULT_TARGET_CHARSET' => "UTF-8",
        'DEFAULT_BR_TEXT' 		 => "\r\n",
        'DEFAULT_SPAN_TEXT'		 => " ",
        'MAX_FILE_SIZE' 		 => 6000000
    ] as $add => $const)
    {
        defined($add) or define($add,$const);	
    }

    $dom = parse\dechiper\_html::dom(
		null,
		$lowercase,
		$forceTagsClosed,
		DEFAULT_TARGET_CHARSET,
		$stripRN,
		DEFAULT_BR_TEXT,
		DEFAULT_SPAN_TEXT
	);

	// preg_match('/\n+/',$str,$check);
	// $str = !$check ? \__fn::get_site($str) : $str;

	if (empty($str) || strlen($str) > MAX_FILE_SIZE) {
		$dom->clear();
		return false;
	}

	return $dom->load($str, $lowercase, $stripRN);
}