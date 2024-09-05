<?php
include_once('autobase.php');
$num = 1127/30;

// var_dump(112887-$num);

// example sintax
// parse\ffmpeg::convert( your_object [optional & only at first param], callback(x) );
// $x->obj (if has object)
// $x->param($param[0],$param[1])->split(...$split)->moveto($param[2])->unlink()->print(save[false/true/(string location)])
// wait for code
// $x->quality(); default Medium
// $x->copy(); split time will delay 1.5 sec
// $x->codec();
// $x->cmdtxt();
// 45:10.301 
// example 
// for hls parse\ffmpeg::convert("file_video.mp4")->hls()->export("folder/filename");
//
parse\ffmpeg::convert(function($x){
    $folder = 'G:\.lua\videobind_02_jav\@shrinking_plan';
    $folder = 'I:\Videos\@Movies\ASIA\JAPAN\.18JP\comp-totte';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "TOTTE-077";
    $sname  = "$fname";
    $fmove  = "$sname";
    // $fmove = "D:/$sname";
    // in mode avi you will get fast result
    // but precission depending on fps
    // $x->mode('avi'); // params : avi, m3u8_hls, image_gif, image_webp   
    // $x->fps(27);  // mode avi will ignored if have value
    // $x->scale(720);  // mode avi will ignored if have value
    // $x->fixtimecut("-1.5","+1.5");
    
    // $x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"], 
        ["15:25.255","16:30.531"],
        ["27:29.235","29:24.117"],
        ["30:50.305","35:47.589"],
        ["38:04.149","43:37.147",true,"$fmove-scane-%s.mp4"],

    ])->print();
    // ])->print("D:");
});
