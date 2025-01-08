<?php function get_site(...$args){
    
    $rslt = null;
    $link = null;
    $arrs = false;
    $code = false;
    $prms = (object)[];
    $data = [];
    $blob = true;

    if(!function_exists("__makeCurlFile")){
        function __makeCurlFile($file){
            $mime = mime_content_type($file);
            $info = pathinfo($file);
            $name = $info['basename'];
            $output = new CURLFile($file, $mime, $name);
            return $output;
        }
    }
    
    foreach ($args as $val) {
        if(is_string($val) && $val){
            // string of link
            $link = $val;
        }
        if(is_bool($val)){
            // (json type data only) param true will give array result, if not will empty / null 
            $arrs = $val;
        }
        if(is_numeric($val)){
            // allow result based by request code
            $code = $val;
        }
        if(is_object($val)){
            /* object are post format
             * ->http array (send header) CURLOPT_HTTPHEADER exp.['X-HTTP-Method-Override: PUT']
             * ->file bin
             * ->code string
             * ->size int
             * ->data array
             */
            $prms = $val;
        }
        if(is_array($val)){
            foreach($val as $var => $vals){
                if(file_exists($vals)){
                    $data[$var] = __makeCurlFile($vals);
                }else{
                    $data[$var] = $vals;
                }
            }
        }
    }
    
    if($link){ 
        
        $send = curl_init($link);
        // $code = isset($prms->code) ? $prms->code : $code;

        curl_setopt($send,CURLOPT_RETURNTRANSFER,true); 
        curl_setopt($send,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($send,CURLOPT_SSL_VERIFYPEER,false);         
        
        /* if method put available */  
        // $file = isset($prms->file) && is_string($prms->file) && $prms->file ? $prms->file : null;
        // $size = isset($prms->size) && is_numeric($prms->size) && $prms->size ? $prms->size : null;
        // $http = isset($prms->http) && is_array($prms->http) && $prms->http ? $prms->http : [];

        // if(!$http){
        //     curl_setopt($send,CURLOPT_HEADER,false);
        // }else{
        //     curl_setopt($send,CURLOPT_HTTPHEADER,$http); 
        // }        

        // if($file && $size){
        //     !$http or curl_setopt($send,CURLOPT_PUT,1);
        //     curl_setopt($send,CURLOPT_INFILE,$file) ;
        //     curl_setopt($send,CURLOPT_INFILESIZE,$size);
        // }        

        /* if available method post data */    
        // $data = isset($prms->data) && is_array($prms->data) && $prms->data ? $prms->data : [];
        !$data or curl_setopt($send,CURLOPT_POST,true);
        !$data or curl_setopt($send,CURLOPT_POSTFIELDS,$data);        
        curl_setopt($send,CURLOPT_BINARYTRANSFER,true);        
        
        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] ?  $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36' ;
        curl_setopt($send,CURLOPT_USERAGENT,$u_agent);
        curl_setopt($send,CURLOPT_CONNECTTIMEOUT,120);
        curl_setopt($send,CURLOPT_TIMEOUT,120);
        
        
        // curl_setopt_array($send, $options);
        // allow result based by code
        $rslt = curl_exec($send);
        
        if(is_numeric($code)){
            $getcode = curl_getinfo($send, CURLINFO_HTTP_CODE);
            if($getcode !== $code){
                $rslt = null;
            }
        }

        curl_close($send);
    }
    
    return $arrs ? json_decode($rslt,true) : $rslt;
}