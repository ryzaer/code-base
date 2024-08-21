<?php

// Catch cURL/Wget requests
if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/^(curl|wget)/i', $_SERVER['HTTP_USER_AGENT'])) {
    echo 'Hi curl user!';
}
else {
    echo 'Hello browser user!';
}

?>