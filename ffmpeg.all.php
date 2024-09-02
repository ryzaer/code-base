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
    //$folder = 'G:\@MYPROJECT\DATABASE_NAMA\videobind_02_jav\@shrinking_plan\@wait';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "WANZ-979";
    $sname  = "$fname";
    $fmove  = "$sname";
    // $fmove  = "D:/$sname";
    //$x->mode('fast');  
    // $x->fps(25);  
    // $x->scale(720);  
    //$x->fixtimecut("-5","+5"); 
    //$x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"], 
        ["45:19.871","45:49.071"],
        ["46:24.442","47:23.249"],
        ["49:13.819","50:16.471",true,"$fmove-scane-%s.mp4"],
        ["01:16:52.161","01:17:20.357",true,"$fmove-scane-%s.mp4"],
        ["01:44:55.130","01:45:47.004",true,"$fmove-scane-%s.mp4"],

    ])->print();
});
