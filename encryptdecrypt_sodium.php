<?php
require_once 'autobase.php';
print "SODIUM ENCRYPTION WORK ONLY PHP >= 7.4<br><br>";

// folder binary storage
$folder = "assets/sodium";
// Additional, authenticated data
$salt_key = "authdata";
is_dir($folder) || mkdir($folder,0755); 

print "<br><br>".implode("<br>",[
"=================================================================>",
"==== RANDOM KEY SODIUM NONCEBYTE (2 directions/device) ==============>",
"=================================================================><br>"
]);

// example crypto current
// On Alice's device
$alice_keypair = sodium_crypto_box_keypair();
$alice_secret_key = sodium_crypto_box_secretkey($alice_keypair);
$alice_public_key = sodium_crypto_box_publickey($alice_keypair);

// On Bob's device
$bob_keypair = sodium_crypto_box_keypair();
$bob_secret_key = sodium_crypto_box_secretkey($bob_keypair);
$bob_public_key = sodium_crypto_box_publickey($bob_keypair);

// $bob_secret_key = '40a1b64224eaf408ce99357952617bfd69fdd4a3f16a4dafbb2abeab07f3e93f';
// $bob_secret_key = sodium_bin2hex($bob_secret_key);
// var_dump(sodium_bin2hex($bob_secret_key));

// Exchange keys:
// - Send Alice's public key to Bob.
// - Send Bob's public key to Alice.

// On sender:
// Create nonce

$nonce = \random_bytes(\SODIUM_CRYPTO_BOX_NONCEBYTES);

// Create enc/sign key pair.
$sender_keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($alice_secret_key, $bob_public_key);

$message = "Hi Bob, I'm Alice";

// Encrypt and sign the message
$encrypted_signed_text = sodium_crypto_box($message, $nonce, $sender_keypair);
// var_dump($encrypted_signed_text);

// On recipient:
$recipient_keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($bob_secret_key, $alice_public_key);
// $recipient_keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(sodium_hex2bin($bob_secret_key), $alice_public_key);
// echo "\$recipient_keypair";
var_dump($recipient_keypair);

// Authenticate and decrypt message
$orig_msg = sodium_crypto_box_open($encrypted_signed_text, $nonce, $sender_keypair);

print "ENCRYPTED ON BOB DEVICE : <pre>$encrypted_signed_text </pre>MESSAGE ON BOB DEVICE : $orig_msg"; // "Hi Bob, I'm Alice"