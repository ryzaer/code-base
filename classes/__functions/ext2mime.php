<?php

function ext2mime($check=true){
	$arrs = [
		//'hqx'	=>	array('application/mac-binhex40', 'application/mac-binhex', 'application/x-binhex40', 'application/x-mac-binhex40'),
		//'cpt'	=>	'application/mac-compactpro',
		'csv'	=>	'text/csv',//array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'),
		'bin'	=>	'application/x-binary',//array('application/macbinary', 'application/mac-binary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'),
		//'dms'	=>	'application/octet-stream',
		//'lha'	=>	'application/octet-stream',
		//'lzh'	=>	'application/octet-stream',
		'dmg'	=>	'application/octet-stream',
		'iso'	=>	'application/octet-stream',
		'msi'	=>	'application/octet-stream',
		'exe'	=>	'application/octet-stream',//array('application/octet-stream', 'application/x-msdownload'),
		//'class'	=>	'application/octet-stream',
		'psd'	=>	'application/x-photoshop', //array('application/x-photoshop', 'image/vnd.adobe.photoshop'),
		//'so'	=>	'application/octet-stream',
		//'sea'	=>	'application/octet-stream',
		'dll'	=>	'application/octet-stream',
		'eot'	=>  'application/vnd.ms-fontobject',
		'oda'	=>	'application/oda',
		'pdf'	=>  'application/pdf',//	array('application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream'),
		//'ai'	=>	array('application/pdf', 'application/postscript'),
		//'eps'	=>	'application/postscript',
		//'ps'	=>	'application/postscript',
		//'smi'	=>	'application/smil',
		//'smil'	=>	'application/smil',
		//'mif'	=>	'application/vnd.mif',
		'wbxml'	=>	'application/wbxml',
		//'wmlc'	=>	'application/wmlc',
		//'dcr'	=>	'application/x-director',
		//'dir'	=>	'application/x-director',
		//'dxr'	=>	'application/x-director',
		//'dvi'	=>	'application/x-dvi',
		//'gtar'	=>	'application/x-gtar',
		'gz'	=>	'application/x-gzip',
		'gzip'  =>	'application/x-gzip',
		'php'	=>	'application/php', //array('application/php', 'application/x-httpd-php', 'application/x-php', 'text/php', 'text/x-php', 'application/x-httpd-php-source'),
		'php4'	=>	'application/x-httpd-php',
		'php3'	=>	'application/x-httpd-php',
		//'phtml'	=>	'application/x-httpd-php',
		'phps'	=>	'application/x-httpd-php-source',
		'js'	=>	'application/javascript', //array('application/javascript','application/x-javascript', 'text/plain'),
		'jsx'	=>	'text/typescript-jsx', 
		'jsp'	=>	'application/javascript', //array('application/javascript','application/x-javascript', 'text/plain'),
		'swf'	=>	'application/x-shockwave-flash',
		//'sit'	=>	'application/x-stuffit',
		'tar'	=>	'application/x-tar',
		'tgz'	=>	'application/x-tar', //array('application/x-tar', 'application/x-gzip-compressed'),
		//'z'	=>	'application/x-compress',
		'xhtml'	=>	'application/xhtml+xml',
		//'xht'	=>	'application/xhtml+xml',
		'zip'	=>	'application/zip', // array('application/zip','application/x-zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'),
		'rar'	=>	'application/rar', //array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
		//'mid'	=>	'audio/midi',
		'midi'	=>	'audio/midi',
		'mpga'	=>	'audio/mpeg',
		'mp2'	=>	'audio/mpeg',
		'mp3'	=>	'audio/mp3', //array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
		//'aif'	=>	array('audio/x-aiff', 'audio/aiff'),
		//'aiff'	=>	array('audio/x-aiff', 'audio/aiff'),
		//'aifc'	=>	'audio/x-aiff',
		//'ram'	=>	'audio/x-pn-realaudio',
		'rm'	=>	'audio/x-pn-realaudio',
		//'rpm'	=>	'audio/x-pn-realaudio-plugin',
		//'ra'	=>	'audio/x-realaudio',
		//'rv'	=>	'video/vnd.rn-realvideo',
		'wav'	=>	'audio/wav', //array('audio/x-wav', 'audio/wave', 'audio/wav'),
		'bmp'	=>	'image/bmp', //array('image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'),
		'gif'	=>	'image/gif',
		'jpeg'	=>	'image/jpeg', //array('image/jpeg', 'image/pjpeg'),
		'jpg'	=>	'image/jpeg', //array('image/jpeg', 'image/pjpeg'),
		'jpe'	=>	'image/jpeg', //array('image/jpeg', 'image/pjpeg'),
		'jp2'	=>	'image/jp2', //array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		//'j2k'	=>	array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'jpf'	=>	'image/jp2', //array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'jpg2'	=>	'image/jp2', //array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'jpx'	=>	'image/jp2',//array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'jpm'	=>	'image/jp2',//array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		//'mj2'	=>	array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		//'mjp2'	=>	array('image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'),
		'png'	=>	'image/png', //array('image/png',  'image/x-png'),
		'tiff'	=>	'image/tiff',
		'tif'	=>	'image/tiff',
		'ttf'	=>	'font/ttf',
		'woff'	=>	'font/woff',
		'woff2'	=>	'font/woff2',
		'css'	=>	'text/css', //array('text/css', 'text/plain'),
		'scss'	=>	'text/css', 
		'html'	=>	'text/html', //array('text/html', 'text/plain'),
		'htm'	=>	'text/html', //array('text/html', 'text/plain'),
		'shtml'	=>	'text/html', //array('text/html', 'text/plain'),
		'txt'	=>	'text/plain',
		'text'	=>	'text/plain',
		'log'	=>	'text/plain',//array('text/plain', 'text/x-log'),
		'rtx'	=>	'text/richtext',
		'rtf'	=>	'text/rtf',
		'rdf'	=>	'text/plain',
		'xml'	=>	'application/xml', //array('application/xml', 'text/xml', 'text/plain'),
		//'xsl'	=>	array('application/xml', 'text/xsl', 'text/xml'),
		'mpeg'	=>	'video/mpeg',
		'mpg'	=>	'video/mpeg',
		'mpe'	=>	'video/mpeg',
		'ts'	=>	'video/mp2t',
		'qt'	=>	'video/quicktime',
		'mov'	=>	'video/quicktime',
		//'avi'	=>	array('video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'),
		'movie'	=>	'video/x-sgi-movie',
		'doc'	=>	'application/vnd.ms-office', //array('application/msword', 'application/vnd.ms-office'),
		'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',//array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip'),
		'dot'	=>	'application/msword', //array('application/msword', 'application/vnd.ms-office'),
		//'dotx'	=>	array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword'),
		'ppt'	=>	'application/vnd.ms-powerpoint',//array('application/powerpoint', 'application/vnd.ms-powerpoint', 'application/vnd.ms-office', 'application/msword'),
		'pptx'	=> 	'application/vnd.openxmlformats-officedocument.presentationml.presentation',//array('application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip', 'application/zip'),
		'xl'	=>	'application/excel',
		'xls'	=>	'application/vnd.ms-excel', //array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword'),
		'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip'),
		//'word'	=>	array('application/msword', 'application/octet-stream'),
		//'eml'	=>	'message/rfc822',
		'json'  =>	'application/json', //array('application/json', 'text/json'),
		'jsonld'  =>	'application/ld+json',
		//'pem'   =>	array('application/x-x509-user-cert', 'application/x-pem-file', 'application/octet-stream'),
		//'p10'   =>	array('application/x-pkcs10', 'application/pkcs10'),
		//'p12'   =>	'application/x-pkcs12',
		//'p7a'   =>	'application/x-pkcs7-signature',
		//'p7c'   =>	array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
		//'p7m'   =>	array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
		//'p7r'   =>	'application/x-pkcs7-certreqresp',
		//'p7s'   =>	'application/pkcs7-signature',
		//'crt'   =>	array('application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'),
		//'crl'   =>	array('application/pkix-crl', 'application/pkcs-crl'),
		//'der'   =>	'application/x-x509-ca-cert',
		//'kdb'   =>	'application/octet-stream',
		//'pgp'   =>	'application/pgp',
		'gpg'   =>	'application/gpg-keys',
		//'sst'   =>	'application/octet-stream',
		//'csr'   =>	'application/octet-stream',
		'rsa'   =>	'application/x-pkcs7',
		//'cer'   =>	array('application/pkix-cert', 'application/x-x509-ca-cert'),
		'3g2'   =>	'video/3gpp2',
		'3gp'   =>	'video/3gp', //array('video/3gp', 'video/3gpp'),
		'mp4'   =>	'video/mp4',
		'm4a'   =>	'audio/x-m4a',
		//'f4v'   =>	array('video/mp4', 'video/x-f4v'),
		'mkv' 	=> 'video/x-matroska',		
		'mka' 	=> 'audio/x-matroska',
		'flv'	=>	'video/x-flv',
		'webm'	=>	'video/webm',
		'aac'   =>	'audio/acc',
		//'m4u'   =>	'application/vnd.mpegurl',
		'n3'   =>	'text/n3',
		'm3u'   =>	'text/plain',
		'm3u8'   =>	'text/plain',
		//'xspf'  =>	'application/xspf+xml',
		'vlc'   =>	'application/videolan',
		'wma'	=>	'audio/x-ms-wma',//array('audio/x-ms-wma', 'video/x-ms-asf'),
		'wmv'   =>	'video/x-ms-wmv',//array('video/x-ms-wmv', 'video/x-ms-asf'),
		'au'    =>	'audio/x-au',
		'ac3'   =>	'audio/ac3',
		'flac'  =>	'audio/x-flac',
		'ogg'   =>	'audio/ogg', //array('audio/ogg', 'video/ogg', 'application/ogg'),
		//'kmz'	=>	array('application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'),
		//'kml'	=>	array('application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'),
		//'ics'	=>	'text/calendar',
		//'ical'	=>	'text/calendar',
		//'zsh'	=>	'text/x-scriptzsh',
		'7z'	=>	'application/x-7z-compressed',//array('application/x-7z-compressed', 'application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
		'7zip'	=>	'application/x-7z-compressed',//array('application/x-7z-compressed', 'application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
		'cdr'	=>	'application/cdr',//array('application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'),
		'jar'	=>	'application/java-archive',//array('application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed'),
		'svg'	=>	'image/svg+xml', //array('image/svg+xml', 'application/xml', 'text/xml'),
		'vcf'	=>	'text/x-vcard',
		'srt'	=>	'text/plain', //array('text/plain', 'text/srt'),
		'vtt'	=>	'text/vtt', //array('text/vtt', 'text/plain'),
		'ico'	=>	'image/x-icon', //'image/x-ico', //array('image/x-icon', 'image/x-ico', 'image/vnd.microsoft.icon'),
		'odc'	=>	'application/vnd.oasis.opendocument.chart',
		//'otc'	=>	'application/vnd.oasis.opendocument.chart-template',
		'odf'	=>	'application/vnd.oasis.opendocument.formula',
		//'otf'	=>	'application/vnd.oasis.opendocument.formula-template',
		'odg'	=>	'application/vnd.oasis.opendocument.graphics',
		//'otg'	=>	'application/vnd.oasis.opendocument.graphics-template',
		'odi'	=>	'application/vnd.oasis.opendocument.image',
		//'oti'	=>	'application/vnd.oasis.opendocument.image-template',
		'odp'	=>	'application/vnd.oasis.opendocument.presentation',
		//'otp'	=>	'application/vnd.oasis.opendocument.presentation-template',
		'ods'	=>	'application/vnd.oasis.opendocument.spreadsheet',
		//'ots'	=>	'application/vnd.oasis.opendocument.spreadsheet-template',
		'odt'	=>	'application/vnd.oasis.opendocument.text',
		'odm'	=>	'application/vnd.oasis.opendocument.text-master',
		//'ott'	=>	'application/vnd.oasis.opendocument.text-template',
		//'oth'	=>	'application/vnd.oasis.opendocument.text-web'
		'yml' => 'text/x-yaml',
		'yaml' => 'application/yaml',
	 ];

	if(is_bool($check) && $check == true ){
		$check = $arrs;
	}

	if(is_string($check) && $check){
		$check = strtolower(trim($check)); 
		if(isset($arrs[$check]))
			$check = $arrs[$check]; 
	}

	return $check ? $check : '';
}					 