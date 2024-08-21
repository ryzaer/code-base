<?php 
function get_zodiac_sign($date="")
{
    $zodiac[356] = "Capricorn";
    $zodiac[326] = "Sagittarius";
    $zodiac[296] = "Scorpio";
    $zodiac[266] = "Libra";
    $zodiac[235] = "Virgo";
    $zodiac[203] = "Leo";
    $zodiac[172] = "Cancer";
    $zodiac[140] = "Gemini";
    $zodiac[111] = "Taurus";
    $zodiac[78]  = "Aries";
    $zodiac[51]  = "Pisces";
    $zodiac[20]  = "Aquarius";
    $zodiac[0]   = "-";

    $date = strtotime($date);  

    $dayOfTheYear = date("z",$date);
    $isLeapYear = date("L",$date);
    if ($isLeapYear && ($dayOfTheYear > 59)) $dayOfTheYear = $dayOfTheYear - 1;
    foreach($zodiac as $day => $sign) if ($dayOfTheYear > $day) break;
    return $sign;    
}