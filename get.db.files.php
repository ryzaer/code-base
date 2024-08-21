<?php
require_once('autoload.php');

/*
 * used clases;
 * - html_parse_form
 */
$form = new \parse\html_form();
$gals = new \Manage\Galleries([
    "F:/WEB/@PROJECT_WEBS/@class.collections/assets/get.db.files/videos",
    "F:/WEB/@PROJECT_WEBS/@class.collections/assets/get.db.files/other",
    "F:/WEB/@PROJECT_WEBS/@class.collections/assets/get.db.files/musics"
]);
//var_dump($gals->get_artist('https://www.javdatabase.com/idols/yuna-tsubaki/'));
var_dump($gals->check_file('vids'));