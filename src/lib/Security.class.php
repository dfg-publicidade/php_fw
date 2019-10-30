<?php

/*
 * Copyright (C) 2017 DFG Studio.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

/**
 * Security class
 */
class Security {
    const CIPHER     = 'AES-256-CBC';
    const IV_STORE   = TMP_PATH . DS . 'ivstore';
    static $iv = '';

    /**
     * Encode a string with safe base 64 encoding
     * @param $str String to encode
     * @return Encoded data
     */
    private static function safe_b64encode($str) {
        $data = base64_encode($str);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

        return $data;
    }

    /**
     * Decode a string with safe base 64 encoding
     * @param $str Data to decode
     * @return Original string
     */
    private static function safe_b64decode($str) {
        $data = str_replace(array('-', '_'), array('+', '/'), $str);
        $mod4 = strlen($data) % 4;

        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    private static function mysql_aes_key($key) {
        $new_key = str_repeat(chr(0), 16);

        for ($i = 0, $len = strlen($key); $i < $len; $i++) {
            $new_key[$i % 16] = $new_key[$i % 16] ^ $key[$i];
        }

        return $new_key;
    }

    /**
     * Encode a string with safe base 64 encoding (SECURITY_KEY must be defined)
     * @param $str String to encode
     * @return Encoded data
     */
    public static function encode($val) {
        if (!$val) {
            return false;
        }

        $key   = hash('sha256', self::mysql_aes_key(SECURITY_KEY));
        $ivlen = openssl_cipher_iv_length(Security::CIPHER);

        $iv = null;

        if (file_exists(TMP_PATH . DS . 'ivstore')) {
            $iv = file_get_contents(Security::IV_STORE);
        }

        if ($iv == null) {
            $iv = openssl_random_pseudo_bytes($ivlen);
            file_put_contents(Security::IV_STORE, $iv);
        }

        $crypttext = openssl_encrypt($val, Security::CIPHER, $key, 0, $iv);

        return trim(self::safe_b64encode($crypttext));
    }

    /**
     * Decode a string with safe base 64 encoding (SECURITY_KEY must be defined)
     * @param $str Data to decode
     * @return Original string
     */
    public static function decode($val) {
        if (!$val) {
            return false;
        }

        $val = self::safe_b64decode($val);

        $key        = hash('sha256', self::mysql_aes_key(SECURITY_KEY));
        $iv         = file_get_contents(Security::IV_STORE);
        $ciphertext = openssl_decrypt($val, Security::CIPHER, $key, 0, $iv);

        return trim($ciphertext);
    }

}
