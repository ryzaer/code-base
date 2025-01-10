<?php function create_device_id() {
    // Gather device-specific data
    $macAddress = shell_exec('getmac'); // Fetch MAC address (requires shell access)
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown'; // Fetch IP address
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'; // Fetch user agent

    // Combine the data into a unique string
    $deviceData = $macAddress . $ipAddress . $userAgent;

    // Hash the data to create a unique ID
    return hash('sha256', $deviceData);
}