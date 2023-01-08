<?php

namespace app\HelperClass\Des;

class DES
{
    private $key;
    private $iv;
    function __construct($key, $iv = 0) {
        $this->key = $key;
        if($iv == 0) {
            $this->iv = $key; //\Illuminate\Support\Str::random(8);
        } else {
            $this->iv = $iv;
        }
    }

    function encrypt($str) {
        return base64_encode( openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv  ) );
    }

    
 
}