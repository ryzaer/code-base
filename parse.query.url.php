<?php 
require_once "autoload.php";
/* this script need .htaccess config. have to double check!
 * if does not contain '&=?' script url cleaner will running
 * make sure u have the right url combination
 * 
 * example regular query
 * $class = url\parse::get("movie?title=blah-blah&year=2021");  
 * 
 * example cleaner query 1
 * $class = url\parse::get("movie/title/blah-blah/year/2021");
 * 
 * example cleaner query 2
 * $class = url\parse::get("movie/blah-blah/2021");
 * set param as keys exp below
 * $class->params = "{title}/{year}";
 * 
 */

$param = 'movie?title=who-am-i&genre=comedy&year=1997';
echo "# Example 1 regular query | $param ";
$data = new parse\url();
$data->set_param($param);
var_dump($data->get());

$param = 'movie/title/who-am-i/genre/comedy/year/1997';
echo "# Example 2 clean query | $param";
$data->set_param($param);
/* remove keys */
$data->rkey = false;
/* revers keys */
$data->keys = false;
var_dump($data->get());

$param = 'movie/title/who-am-i/genre/comedy/year/1997';
echo "# Example 3 clean query & remove key '{genre}' | $param";
$data->set_param($param);
$data->rkey = 'genre';
$data->keys = false;
var_dump($data->get());

$param = 'movie/who-am-i/comedy/1997';
echo "# Example 4 clean query & revers keys '{title}/{genre}/{year}'| $param";
$data->set_param($param);
$data->rkey  = false;
$data->keys  = "title/genre/year";
var_dump($data->get());

$param = 'movie/base/indie?title=who-am-i&genre=comedy&year=1997';
echo "# Example 5 clean query & combine | $param";
$data->set_param($param);
$data->rkey  = false;
$data->keys  = "title/year";
var_dump($data->get());

echo '# Example 6 (default REQUEST_URI) try yourself!';
$data->set_param();
$data->rkey  = false;
$data->keys  = null;
var_dump($data->get());