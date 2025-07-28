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
    $fname  = "092122-001";
    $sname  = "$fname";
    $fmove  = "$sname";
    // $fmove = "D:/$sname";
    // in mode avi you will get fast result
    // but precission depending on fps
    // $x->mode('avi'); // params : avi, m3u8_hls, image_gif, image_webp   
    // $x->fps(30);  // mode avi will ignored if have value
    $x->scale(720);  // mode avi will ignored if have value
    // $x->fixtimecut("-1.5","+1.5");
    
    // $x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"],
        // ["11:25.587","15:53.551",true,"$fmove-scane-%s.mp4"],
        // ["47:10.832","48:11.176"],
        // ["49:26.119","50:05.270"],
        // ["50:52.569","51:43.539",true,"$fmove-scane-%s.mp4"],
        // ["59:36.851","01:00:26.242"],
        // ["01:00:43.604","01:00:58.527"],
        // ["01:01:40.807","01:03:08.814"],
        // ["01:03:51.742","01:05:32.875",true,"$fmove-scane-%s.mp4"],
        // ["01:05:32.875","01:08:10.471",true,"$fmove-scane-%s.mp4"],
        ["45:34.617","46:16.886"],
        ["46:37.190","48:32.074"],
        ["49:13.029","49:25.703",true,"$fmove-scane-%s.mp4"],
        ["57:39.282","01:00:32.337",true,"$fmove-scane-%s.mp4"],
    ])->print();
    // ])->print("D:");
});
