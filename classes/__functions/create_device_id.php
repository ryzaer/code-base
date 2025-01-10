<?php function create_device_id($add=null) {
    // Attempt to get the MAC address using system commands
    $os = strtolower(PHP_OS);
    $id = null;
    if ($os === 'linux')
        // get mac in linux
        $id = shell_exec("cat /sys/class/net/$(ip route show default | awk '/default/ {print $5}')/address");
    
    if ($os === 'winnt'){
        $macAddress = shell_exec("getmac");
        foreach (explode("\n",$macAddress) as $str)
            if(preg_match("/Device\\\\Tcpip/is",$str))
                $id = substr($str,0,17);
    }

    if(!$id)
        $id = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'; // Fetch user agent

    // Hash the data to create a unique ID
    return (is_string($add)?"{$add}_":null).md5($id);
}