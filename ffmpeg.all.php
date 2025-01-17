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
    $folder = 'F:\.attachments\11250111\fc2';
    // $folder = 'I:\Videos\@Movies\ASIA\JAPAN\.18JP\comp-totte';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "4575756";
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
        ["11:19.952","12:03.778"],
        ["12:20.926","13:51.426"],
        ["14:57.205","16:17.205",true,"$fmove-scane-%s.mp4"],

        ["22:30.682","22:56.972"],
        ["24:25.391","27:52.938",true,"$fmove-scane-%s.mp4"],
        ["29:35.419","30:53.604"],
        ["31:13.148","31:25.788"],
        ["32:44.028","33:02.820",true,"$fmove-scane-%s.mp4"],
        ["33:26.105","35:03.187",true,"$fmove-scane-%s.mp4"],
        ["35:03.187","39:00.934",true,"$fmove-scane-%s.mp4"],
        ["39:01.700","41:37.74",true,"$fmove-scane-%s.mp4"],
        ["41:37.746","43:34.035"],
        ["43:45.241","44:18.889"],
        ["44:50.051","45:08.529"],
        ["45:51.479","46:15.695"],
        ["52:37.846","53:09.848",true,"$fmove-scane-%s.mp4"],

        ["01:16:13.996","01:19:56.220",true,"$fmove-scane-%s.mp4"],
        ["01:19:56.220","01:23:19.654",true,"$fmove-scane-%s.mp4"],

        ["01:29:40.841","01:30:02.252"],
        ["01:30:37.823","01:31:36.812",true,"$fmove-scane-%s.mp4"],
    ])->print();
    // ])->print("D:");
});
