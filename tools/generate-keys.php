<?php
$pair=sodium_crypto_sign_keypair();echo 'LICENSE_PRIVATE_KEY=base64:'.base64_encode(sodium_crypto_sign_secretkey($pair)).PHP_EOL;echo 'LICENSE_PUBLIC_KEY=base64:'.base64_encode(sodium_crypto_sign_publickey($pair)).PHP_EOL;
