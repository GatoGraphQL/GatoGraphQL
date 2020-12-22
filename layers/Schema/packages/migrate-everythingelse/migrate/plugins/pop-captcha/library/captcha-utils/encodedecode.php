<?php

class PoP_CaptchaEncodeDecode
{

    // Taken from https://gist.github.com/joashp/a1ae9cb30fa533f4ad94
    protected static function encryptDecrypt($action, $string, $random)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = POP_CAPTCHA_PRIVATEKEYS_CAPTCHA.$random;
        $secret_iv = POP_CAPTCHA_PRIVATEKEYS_IV;
        
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public static function encode($data, $random)
    {
        return self::encryptDecrypt('encrypt', $data, $random);
    }

    public static function decode($data, $random)
    {
        return self::encryptDecrypt('decrypt', $data, $random);
    }
}
