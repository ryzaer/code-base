<?php
function mkdir_based_months($maindir=null,$beginY=null,$endY=null){
    $allyear = range($beginY ? $beginY : 2019,$endY ? $endY : date('Y'));
    $mfolder = $maindir ? $maindir : "/usr/home/yourfolder/";
    $dirs = [];
    foreach ($allyear as $year) {    
        foreach (range(1, $allyear[count($allyear)-1] == date('Y') ? date('m') : 12) as $month) {
            $dirs[] = $mfolder.$year.(strlen($month) == 1 ? "0$month" : $month);     
        }
    }
    return $dirs;
}