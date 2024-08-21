<?php
function app_location_host(){
    $params = explode("/",$_SERVER['SCRIPT_NAME']);
    unset($params[count($params)-1]);
    return implode("/",$params);
}