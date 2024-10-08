<?php
$filename = 'css.svg.hover.css';
function css_svg($str){
    return preg_replace(['/>(\n+|\s+)</','/</','/>/','/"/'],['%3E%3C','%3C','%3E','\''],$str);
}
$svg_str = <<<HTML
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/></svg>
HTML;
$svg_str_hover = <<<HTML
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"  fill="green"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/></svg>
HTML;
$svg_str = css_svg($svg_str);
$svg_str_hover = css_svg($svg_str_hover);
// $css = <<<CSS
// .icon_str {
//     background-image: url("data:image/svg+xml, $svg_str");
//     background-size: cover;
// }
// CSS;
// file_put_contents(__DIR__."/$filename",$css);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVG Icon</title>
    
    <style>
        .icon {
            display: inline-block;
            width: 70px;
            height: 70px;
        }
        .icon-weather {
            content:url('assets/images/WhatsApp.svg');
        }
        .icon-wa {
            background-image: url("data:image/svg+xml, <?=$svg_str?>");
            background-size: cover;
        }
        .icon-wa:hover,
        .icon-wa:focus {
            background-image: url("data:image/svg+xml, <?=$svg_str_hover?>");
        }

        .icon-bike {
            background-image: url("data:image/svg+xml, %3Csvg height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m5 20.5a3.5 3.5 0 0 1 -3.5-3.5 3.5 3.5 0 0 1 3.5-3.5 3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1 -3.5 3.5m0-8.5a5 5 0 0 0 -5 5 5 5 0 0 0 5 5 5 5 0 0 0 5-5 5 5 0 0 0 -5-5m9.8-2h4.2v-1.8h-3.2l-1.94-3.27c-.29-.5-.86-.83-1.46-.83-.47 0-.9.19-1.2.5l-3.7 3.69c-.31.31-.5.71-.5 1.21 0 .63.33 1.16.85 1.47l3.35 2.03v5h1.8v-6.5l-2.25-1.65 2.32-2.35m5.93 13a3.5 3.5 0 0 1 -3.5-3.5 3.5 3.5 0 0 1 3.5-3.5 3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1 -3.5 3.5m0-8.5a5 5 0 0 0 -5 5 5 5 0 0 0 5 5 5 5 0 0 0 5-5 5 5 0 0 0 -5-5m-3-7.2c1 0 1.8-.8 1.8-1.8s-.8-1.8-1.8-1.8-1.8.8-1.8 1.8.8 1.8 1.8 1.8z'/%3E%3C/svg%3E");
            background-size: cover;
        }

        .icon-bike:hover,
        .icon-bike:focus {
            background-image: url("data:image/svg+xml, %3Csvg height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='red' d='m5 20.5a3.5 3.5 0 0 1 -3.5-3.5 3.5 3.5 0 0 1 3.5-3.5 3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1 -3.5 3.5m0-8.5a5 5 0 0 0 -5 5 5 5 0 0 0 5 5 5 5 0 0 0 5-5 5 5 0 0 0 -5-5m9.8-2h4.2v-1.8h-3.2l-1.94-3.27c-.29-.5-.86-.83-1.46-.83-.47 0-.9.19-1.2.5l-3.7 3.69c-.31.31-.5.71-.5 1.21 0 .63.33 1.16.85 1.47l3.35 2.03v5h1.8v-6.5l-2.25-1.65 2.32-2.35m5.93 13a3.5 3.5 0 0 1 -3.5-3.5 3.5 3.5 0 0 1 3.5-3.5 3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1 -3.5 3.5m0-8.5a5 5 0 0 0 -5 5 5 5 0 0 0 5 5 5 5 0 0 0 5-5 5 5 0 0 0 -5-5m-3-7.2c1 0 1.8-.8 1.8-1.8s-.8-1.8-1.8-1.8-1.8.8-1.8 1.8.8 1.8 1.8 1.8z'/%3E%3C/svg%3E");
        }

        .icon-bell {
            background-image: url("data:image/svg+xml, %3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11.5,22C11.64,22 11.77,22 11.9,21.96C12.55,21.82 13.09,21.38 13.34,20.78C13.44,20.54 13.5,20.27 13.5,20H9.5A2,2 0 0,0 11.5,22M18,10.5C18,7.43 15.86,4.86 13,4.18V3.5A1.5,1.5 0 0,0 11.5,2A1.5,1.5 0 0,0 10,3.5V4.18C7.13,4.86 5,7.43 5,10.5V16L3,18V19H20V18L18,16M19.97,10H21.97C21.82,6.79 20.24,3.97 17.85,2.15L16.42,3.58C18.46,5 19.82,7.35 19.97,10M6.58,3.58L5.15,2.15C2.76,3.97 1.18,6.79 1,10H3C3.18,7.35 4.54,5 6.58,3.58Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
        }

        .icon-bell:hover,
        .icon-bell:focus {
            background-image: url("data:image/svg+xml, %3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='red' d='M11.5,22C11.64,22 11.77,22 11.9,21.96C12.55,21.82 13.09,21.38 13.34,20.78C13.44,20.54 13.5,20.27 13.5,20H9.5A2,2 0 0,0 11.5,22M18,10.5C18,7.43 15.86,4.86 13,4.18V3.5A1.5,1.5 0 0,0 11.5,2A1.5,1.5 0 0,0 10,3.5V4.18C7.13,4.86 5,7.43 5,10.5V16L3,18V19H20V18L18,16M19.97,10H21.97C21.82,6.79 20.24,3.97 17.85,2.15L16.42,3.58C18.46,5 19.82,7.35 19.97,10M6.58,3.58L5.15,2.15C2.76,3.97 1.18,6.79 1,10H3C3.18,7.35 4.54,5 6.58,3.58Z'%3E%3C/path%3E%3C/svg%3E");
        }

        .icon-arrow {
            background-image: url("data:image/svg+xml, %3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath  d='M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            transition: 0.5s;
            transform: rotate(0deg);
        }

        .icon-arrow:hover,
        .icon-arrow:focus {
            background-image: url("data:image/svg+xml, %3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='red' d='M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z'%3E%3C/path%3E%3C/svg%3E");
            transition: 0.1s;
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <div>    
        <span class="icon icon-weather"></span>
        <span class="icon icon-wa"></span>
        <span class="icon icon-bike"></span>
        <span class="icon icon-bell"></span>
        <span class="icon icon-arrow"></span>    
    </div>
</body>
</html>

