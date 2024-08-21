<?php function get_days($day="Monday",$format="Y-m-d"){
    $date = new \DateTime("first $day of this month");
    $thisMonth = $date->format("m");
    $dateofsync = [];
    while ($date->format("m") === $thisMonth) {
        $dateofsync[$date->format($format)] = false;
        $date->modify("next $day");
    }
    return $dateofsync;
}