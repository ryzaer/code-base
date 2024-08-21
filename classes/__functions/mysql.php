<?php function mysql(...$args) {
    // example remove database
    // print \__fn::mysql("drop your_current_db","localhost","root","123"); 
    // example remove table
    // print \__fn::mysql("drop table if exists your_current_db.your_tb_db","localhost","root","123");
    // example create database
    // print \__fn::mysql("create your_new_db","localhost","root","123"); 
    // example import database
    // print \__fn::mysql("your_current_db < ./app/data/your_saved_db.sql","localhost","root","123"); 
    // example export database
    // print \__fn::mysql("your_current_db your_tb_db > app/data/yoursaved_db.sql","localhost","root","123");
    $exec=$args[0]; 
    unset($args[0]);
    if(count($args) == 3){
        $args = array_values($args);
        foreach (["host","user","password"] as $n => $arg) {
            if(isset($args[$n]) && $args[$n])
                $sql[]="--$arg=\"{$args[$n]}\"";
        }
        $sql = implode(" ",$sql);  
        $adm = true;   
        preg_match('/\s<\s/',$exec,$mtch);
        if($mtch){
            return shell_exec("mysql $sql $exec");
        }
        preg_match('/drop table\s/',$exec,$mtch);
        if($mtch){
            return shell_exec("mysql $sql --force -e \"$exec\"");
        } 
        preg_match('/\s>\s/',$exec,$mtch);
        if($mtch){
            $exec = preg_split('/\s>\s/',$exec);
            return shell_exec("mysqldump $sql {$exec[0]} --result-file=\"{$exec[1]}\" 2>&1");
        }               
        return shell_exec("mysqladmin $sql --force $exec");
    }else{
        return false;
    }
}