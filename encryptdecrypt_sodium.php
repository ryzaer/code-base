<?php
require_once 'autobase.php';
print "SODIUM ENCRYPTION WORK ONLY PHP >= 7.4<br><br>";

// folder binary storage
$folder = "assets/sodium";
is_dir($folder) || mkdir($folder,0755);

// Generate a binary secret key. This value must be stored securely.
$key = sodium_crypto_aead_xchacha20poly1305_ietf_keygen();

// Generate a binary nonce for EACH MESSAGE. This can be public, and must be provided to decrypt the message.
$nonce = \random_bytes(\SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);

// Text to encrypt.
$message = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique cum, magnam sapiente nihil suscipit in repellat nam ab totam quam. Corporis, repudiandae repellendus autem ad officiis dolor cumque soluta a!";

// Encrypt as bin
$encrypted_text = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($message, '', $nonce, $key);

// concat all into file
file_put_contents("$folder/poly1305_data.bin",$key.$nonce.$encrypted_text);

// process the file
$getcrypt = file_get_contents("$folder/poly1305_data.bin");
$getkey   = substr($getcrypt,0,32);
$getnonce = substr($getcrypt,32,24);
$getdata  = substr($getcrypt,56);

// Decrypt
$original_message = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($getdata, '', $getnonce, $getkey);
print "<br><br>".implode("<br>",[
   "=================================================================>",
   "==== RANDOM KEY SODIUM WITH POLY1305 (BIN) =======================>",
   "=================================================================>",
   "<br>Encrypted Binary : key length ".strlen($key)." , nonce length ".strlen($nonce)." <pre>$encrypted_text </pre>Original Messeage:<br>$original_message<br>"
]);

// Encrypt
$encrypted_text = sodium_bin2hex(sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($message, '', $nonce, $key));
// Decrypt
$original_message = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(sodium_hex2bin($encrypted_text), '', $nonce, $key);

print "<br><br>".implode("<br>",[
   "=================================================================>",
   "==== RANDOM KEY SODIUM WITH POLY1305 (HEX) =======================>",
   "=================================================================>",
   "<br>Encrypted Text:<br>$encrypted_text<br>Original Messeage:<br>$original_message<br>"
]);

print "<br><br>".implode("<br>",[
"=================================================================>",
"==== RANDOM KEY SODIUM NONCEBYTE (2 directions/device) ==============>",
"=================================================================><br>"
]);

// example crypto current
// On Alice's device
$alice_keypair = sodium_crypto_box_keypair();
$alice_secret_key = sodium_crypto_box_secretkey($alice_keypair);
$alice_public_key = sodium_crypto_box_publickey($alice_keypair);

// On Bob's device
$bob_keypair = sodium_crypto_box_keypair();
$bob_secret_key = sodium_crypto_box_secretkey($bob_keypair);
$bob_public_key = sodium_crypto_box_publickey($bob_keypair);

// Exchange keys:
// - Send Alice's public key to Bob.
// - Send Bob's public key to Alice.

// On sender:
// Create nonce

$nonce = \random_bytes(\SODIUM_CRYPTO_BOX_NONCEBYTES);

// Create enc/sign key pair.
$sender_keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($alice_secret_key, $bob_public_key);

$message = "Hi Bob, I'm Alice";

// Encrypt and sign the message
$encrypted_signed_text = sodium_crypto_box($message, $nonce, $sender_keypair);

// On recipient:
$recipient_keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($bob_secret_key, $alice_public_key);

// Authenticate and decrypt message
$orig_msg = sodium_crypto_box_open($encrypted_signed_text, $nonce, $sender_keypair);

print "ENCRYPTED ON BOB DEVICE : <pre>$encrypted_signed_text </pre>MESSAGE ON BOB DEVICE : $orig_msg"; // "Hi Bob, I'm Alice"

// $hash = base64_encode(hash('haval192,5',microtime()));
// var_dump($hash);
// var_dump(substr($hash,0,32));
// var_dump(substr($hash,32,32));
// die();
// START CUSTOM CLASS SODIUM 

// $salt = "your_salty_key";
$salt = uniqid();
print "<br><br>".implode("<br>",[
"=================================================================>",
"==== CUSTOM SALT SODIUM (BIN) ===================================>",
"=================================================================>",
"Saltkey : $salt <br><br>ENCRYPTED BINARY : using algo haval192,5<br>"
]);

$hash = new \Crypto\sodium($salt);

$json =<<<JSON
{"web-app": {
 "servlet": [   
   {
     "servlet-name": "cofaxCDS",
     "servlet-class": "org.cofax.cds.CDSServlet",
     "init-param": {
       "configGlossary:installationAt": "Philadelphia, PA",
       "configGlossary:adminEmail": "ksm@pobox.com",
       "configGlossary:poweredBy": "Cofax",
       "configGlossary:poweredByIcon": "/images/cofax.gif",
       "configGlossary:staticPath": "/content/static",
       "templateProcessorClass": "org.cofax.WysiwygTemplate",
       "templateLoaderClass": "org.cofax.FilesTemplateLoader",
       "templatePath": "templates",
       "templateOverridePath": "",
       "defaultListTemplate": "listTemplate.htm",
       "defaultFileTemplate": "articleTemplate.htm",
       "useJSP": false,
       "jspListTemplate": "listTemplate.jsp",
       "jspFileTemplate": "articleTemplate.jsp",
       "cachePackageTagsTrack": 200,
       "cachePackageTagsStore": 200,
       "cachePackageTagsRefresh": 60,
       "cacheTemplatesTrack": 100,
       "cacheTemplatesStore": 50,
       "cacheTemplatesRefresh": 15,
       "cachePagesTrack": 200,
       "cachePagesStore": 100,
       "cachePagesRefresh": 10,
       "cachePagesDirtyRead": 10,
       "searchEngineListTemplate": "forSearchEnginesList.htm",
       "searchEngineFileTemplate": "forSearchEngines.htm",
       "searchEngineRobotsDb": "WEB-INF/robots.db",
       "useDataStore": true,
       "dataStoreClass": "org.cofax.SqlDataStore",
       "redirectionClass": "org.cofax.SqlRedirection",
       "dataStoreName": "cofax",
       "dataStoreDriver": "com.microsoft.jdbc.sqlserver.SQLServerDriver",
       "dataStoreUrl": "jdbc:microsoft:sqlserver://LOCALHOST:1433;DatabaseName=goon",
       "dataStoreUser": "sa",
       "dataStorePassword": "dataStoreTestQuery",
       "dataStoreTestQuery": "SET NOCOUNT ON;select test='test';",
       "dataStoreLogFile": "/usr/local/tomcat/logs/datastore.log",
       "dataStoreInitConns": 10,
       "dataStoreMaxConns": 100,
       "dataStoreConnUsageLimit": 100,
       "dataStoreLogLevel": "debug",
       "maxUrlLength": 500}},
   {
     "servlet-name": "cofaxEmail",
     "servlet-class": "org.cofax.cds.EmailServlet",
     "init-param": {
     "mailHost": "mail1",
     "mailHostOverride": "mail2"}},
   {
     "servlet-name": "cofaxAdmin",
     "servlet-class": "org.cofax.cds.AdminServlet"},

   {
     "servlet-name": "fileServlet",
     "servlet-class": "org.cofax.cds.FileServlet"},
   {
     "servlet-name": "cofaxTools",
     "servlet-class": "org.cofax.cms.CofaxToolsServlet",
     "init-param": {
       "templatePath": "toolstemplates/",
       "log": 1,
       "logLocation": "/usr/local/tomcat/logs/CofaxTools.log",
       "logMaxSize": "",
       "dataLog": 1,
       "dataLogLocation": "/usr/local/tomcat/logs/dataLog.log",
       "dataLogMaxSize": "",
       "removePageCache": "/content/admin/remove?cache=pages&id=",
       "removeTemplateCache": "/content/admin/remove?cache=templates&id=",
       "fileTransferFolder": "/usr/local/tomcat/webapps/content/fileTransferFolder",
       "lookInContext": 1,
       "adminGroupID": 4,
       "betaServer": true}}],
 "servlet-mapping": {
   "cofaxCDS": "/",
   "cofaxEmail": "/cofaxutil/aemail/*",
   "cofaxAdmin": "/admin/*",
   "fileServlet": "/static/*",
   "cofaxTools": "/tools/*"},

 "taglib": {
   "taglib-uri": "cofax.tld",
   "taglib-location": "/WEB-INF/tlds/cofax.tld"}
   }
}
JSON;

$bin = $hash->encrypt($json);
print "<pre>$bin</pre> DECRYPTED BINARY :<pre>".$hash->decrypt($bin)."</pre>ENCODE KEYS OF : $salt";
var_dump($hash->encode());