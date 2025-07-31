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
    $folder = 'F:\Movies\Gaspel Noir\.soul';
    // $folder = 'I:\Videos\@Movies\ASIA\JAPAN\.18JP\comp-totte';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "hz-1863";
    $sname  = "$fname";
    $fmove  = "$sname";
    // $fmove = "D:/$sname";
    // in mode avi you will get fast result
    // but precission depending on fps
    // $x->mode('avi'); // params : avi, m3u8_hls, image_gif, image_webp   
    // $x->fps(30);  // mode avi will ignored if have value
    // $x->scale(720);  // mode avi will ignored if have value
    // $x->fixtimecut("-1.5","+1.5");
    
    // $x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"],
        ["22:53.039","31:52.485",true,"$fmove-scane-%s.mp4"],
        ["47:31.385","50:50.666"],
        ["51:39.869","51:52.932"],
        ["52:25.627","53:30.190"],
        ["54:03.774","54:35.943"],
        ["55:06.481","55:29.888"],
        ["57:34.360","58:51.274"],
        ["59:40.582","01:00:49.672"],
        ["01:01:19.037","01:02:27.041",true,"$fmove-scane-%s.mp4"],
        ["01:02:27.041","01:05:39.619",true,"$fmove-scane-%s.mp4"]
    ])->print();
    // ])->print("D:");
});
