<?php
namespace App\Traits;


trait DecryptData
{
    public function decrypteDes($encrypted){
        $key = "qwervbppnrvr56m123+#";
        $key = substr(str_pad($key, 24, "\0"), 0, 24);
        $decrypted = openssl_decrypt(hex2bin($encrypted), "des-ede3", $key, OPENSSL_RAW_DATA);
        return $decrypted;
    }

}