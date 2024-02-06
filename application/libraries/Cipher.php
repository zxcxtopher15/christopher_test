<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
*
* @version 1.0
* @author Carlo Cano <carlocano03@gmail.com>
* @copyright Copyright &copy; 2022,
*
*/


class Cipher {

  public function __construct() {

  }

    public function encrypt($val){
        $key = 'wehealasone';
        //$key previously generated safely, ie: openssl_random_pseudo_bytes
        $plaintext = $val;
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = $this->base64url_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
    }

    public function decrypt($val){
        $key = 'wehealasone';
        // $get = str_replace(" ","+",$_GET['class']);
        $get = str_replace(" ","+",$val);
        $c = $this->base64url_decode($get);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);

        // Verify the MAC for integrity
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        } else {
            show_404('Invalid MAC');
        }
    }

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

}