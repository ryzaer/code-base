<?php require_once "../autobase.php";

$time = microtime(true);
if(isset($_GET['open']) && $_GET['open']){
    // example link "code-base/sodium.php?open=677b7b1eb0afa.bin"
    $deimg = "<i><b style=\"color:red\">Image not exists!</b></i>";
    $filex = "../assets/sodium/{$_GET['open']}";
    if(file_exists($filex)){
        $image = file_get_contents($filex);
        $enkey = preg_replace("/\.(jpg|bin)/","",$_GET['open']);
        $deimg = Crypto\sodium::decrypt($image,$enkey);        
        if(!$deimg){
            $deimg = "<i><b style=\"color:red\">Wrong data crypted image!</b></i>";
        }else{
            header("Content-Disposition: inline; name=\"$enkey\"; filename=\"$enkey.jpg\"");
            header("Content-Type:image/jpg");
            print $deimg;
            $deimg = null;
        }
    }
    die($deimg);
}


$plaintext = <<<JSON
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

$folder = "../assets/sodium";

print "<br><br>".implode("<br>",[
"<b style=\"font-family:monospace;font-size:18px\">=======================================================================>",
"======== SODIUM CLASS (Singleton) EXAMPLE : haval192,5 ================>",
"======== In this algo encrypt/decrypt must using salt key =============>",
"======== Salt key will auto create if not provided ====================>",
"======== example encrypt : Crypto\sodium::encrypt(data,saltkey); ======>",
"======== example decrypt : Crypto\sodium::decrypt(data,saltkey); ======>",
"=======================================================================></b>"
])."<br>";
// $plaintext = file_get_contents("../assets/images/arini.jpg");
var_dump($plaintext);
// Example Default Salt Key
$ciphertext = Crypto\sodium::encrypt($plaintext);
// Example Using Salt Key
// $ciphertext = Crypto\sodium::encrypt($plaintext);
echo "<b>Encrypted Data [Binary]</b>";
var_dump($ciphertext);

echo "<b>Encryption codes</b>";
$dataCode = Crypto\sodium::encode(false);
var_dump($dataCode);
$get_keys = Crypto\sodium::encode();
$get_salt = $dataCode['salt'];

// save to file
file_put_contents("$folder/data.bin",$ciphertext);
// file_put_contents("$folder/$get_salt.bin",$ciphertext);

Crypto\sodium::close();

echo "<b>Decrypt Using Key [{$get_keys['key']}] & Nonce [{$get_keys['nonce']}]</b>";
var_dump(Crypto\sodium::decrypt(file_get_contents("../assets/sodium/data.bin"),$get_keys));
echo "<b>Decrypt Using Salt Key [$get_salt]</b>";
var_dump(Crypto\sodium::decrypt(file_get_contents("../assets/sodium/data.bin"),$get_salt));

Crypto\sodium::close();


print implode("<br>",[
"<b style=\"font-family:monospace;font-size:18px\">=======================================================================>",
"== SODIUM CLASS (Singleton) EXAMPLE : poly1305 ========================>",
"== In this algo encrypt/decrypt salt key are optional =================>",
"== example encrypt : Crypto\sodium::poly1305_encrypt(data,saltkey); ===>",
"== example decrypt : Crypto\sodium::poly1305_decrypt(data,saltkey); ===>",
"=======================================================================></b>"
])."<br>";

var_dump($plaintext);
// Example Default Salt Key
$ciphertext = Crypto\sodium::poly1305_encrypt($plaintext);
file_put_contents("$folder/poly1305_data.bin",$ciphertext);
echo "<b>Encrypted Data [Poly 1305 Binary]</b>";
var_dump($ciphertext);
echo "<b>Decrypted Data [Poly 1305 Binary]</b>";
var_dump(Crypto\sodium::poly1305_decrypt(file_get_contents("$folder/poly1305_data.bin")));
echo "<b>Encryption codes</b>";
$dataCode = Crypto\sodium::encode(false);
var_dump($dataCode);

$time = number_format((microtime(true) - $time),5);
print "<br><i>execution time : $time</i>";