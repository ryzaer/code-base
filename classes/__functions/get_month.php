<?php function get_month($ynmth=null){
    $ynmth = explode("-", $ynmth ? $ynmth : date("Y-m") );
    $list  = [];    
    for($d=1; $d<=31; $d++)
    {
        $time=mktime(12, 0, 0, $ynmth[1], $d, $ynmth[0]);          
        if (date('m', $time)==$ynmth[1])       
            $list[] = date('Y-m-d D', $time);
    }
    return $list;
}