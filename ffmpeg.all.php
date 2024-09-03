<?php
include_once('autobase.php');
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
    $folder = 'I:\Videos\@Movies\BARAT\@best';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "Justice League (2021)";
    $sname  = "$fname";
    $fmove  = "$sname";
    // $fmove = "D:/$sname";
    // in mode avi you will get fast result
    // but precission depending on fps
    $x->mode('avi'); // params : avi, m3u8_hls, image_gif, image_webp   
    // $x->fps(27);  // mode avi will ignored if have value
    // $x->scale(720);  // mode avi will ignored if have value
    // $x->fixtimecut("-1.5","+1.5");
    
    // $x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"], 
        ["18:47.545","18:51.651"],
        ["18:55.006","19:00.182"],
        ["24:36.847","24:41.383",true,"$fmove-scane-%s.mp4"],

        ["02:36:26.668","02:38:26.668"],
        ["02:40:26.668","02:45:26.668"],
        ["02:50:26.668","02:56:26.668",true,"$fmove-scane-%s.mp4"],
        
    ])->print();
});
