<?php function create_device_id() {
    // Attempt to get the MAC address using system commands
    $os = strtolower(PHP_OS);
    $macAddress = 'unknown';
    if ($os === 'linux')
        $macAddress = shell_exec("cat /sys/class/net/$(ip route show default | awk '/default/ {print $5}')/address");
    
    if ($os === 'winnt'){
        $macAddress = shell_exec("getmac");
        foreach (explode("\n",$macAddress) as $str)
            if(preg_match("/Device\\\\Tcpip/is",$str))
                $macAddress = substr($str,0,17);
    }
    
    $macAddress = trim($macAddress);

    // $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown'; // Fetch IP address
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'; // Fetch user agent

    // Combine the data into a unique string
    $deviceData = "$macAddress$userAgent";

    // Hash the data to create a unique ID
    return hash('sha256', $deviceData);
}