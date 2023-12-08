<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Authentication Configurations
 * 
 * @category JSON_Web_Token_Configuration
 * @package  JWT_Authentication_Class_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/JWTCodec.php
 */

/**
 * JSON Web Token Class
 * 
 * @category JSON_Web_Token
 * @package  JWT_Authentication_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/JWTCodec.php
 */
class JWTCodec
{
    //*===========================================================================*//
    /**
     * The encode() returns the users credintials encrypted
     * 
     * @param array $payload This contains the users credintials for the payload 
     * 
     * @access public  
     * 
     * @return string
     */
    public function encode(array $payload): string 
    {
        // CONVERT THE HEADER AND PAYLOAD TO JSON
        $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);
        $json_payload = json_encode($payload);

        // CONVERT THE JSON FORMATTED HEADER AND PAYLOAD TO URL SAFE VALUES
        $urlsafe = $this->base64UrlEncode($header);
        $urlpayload = $this->base64UrlEncode($json_payload);

        // A 256 ENCRYPTION HEX KEY
        $key = "afb15e7ba5262c7bd7a5d74ad3533510c3f311230634894e952dd0d37ffd88a2";

        // GENERATE A KEYED HASH VALUED WITH THE $urlsafe, $urlpayload, $key
        $signature = hash_hmac("sha256", $urlsafe . "." . $urlpayload, $key, true);

        // CONVERT THE GENERATE KEYED HASH TO A URL SAFE VALUE
        $url_safe_signature= $this->base64UrlEncode($signature);

        // RETURN THE JWT FORMATTED WITH THE HEADER . PAYLOAD . SIGNATURE 
        $JWT_signature = $urlsafe . "." . $urlpayload . "." . $url_safe_signature;

        return $JWT_signature;
    }
    //*===========================================================================*//


    //*===========================================================================*//
    /**
     * The decode() returns the users credintials after being decoded
     * 
     * @param string $token This contains the encrypted credintials for the user 
     * 
     * @access public  
     * 
     * @return string
     */
    public function decode(string $token)
    {
        $check_preg = preg_match(
            "/(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/", 
            $token, 
            $matches
        );

        if ($check_preg !== 1) {

            throw new InvalidArgumentException("Invalid token");

        }
        // THE ORIGINAL 256 ENCRYPTION HEX KEY FROM THE ENCODE METHOD
        $key = "afb15e7ba5262c7bd7a5d74ad3533510c3f311230634894e952dd0d37ffd88a2";

        $signature = hash_hmac(
            "sha256", 
            $matches["header"] . "." . $matches["payload"], 
            $key, 
            true
        );
        
        $signature_from_token = $this->base64UrlDecode($matches["signature"]);

        $checked_hashes = hash_equals($signature, $signature_from_token);

        if (! $checked_hashes) {
          
            throw new Exception("The signatures do not match");

        }

        $payload = $this->base64UrlDecode($matches["payload"]);

        $decoded_payload = json_decode($payload, true);

        return $decoded_payload;

    }
    //*===========================================================================*//

    



    //*===========================================================================*//
    /**
     * The baseUrlEncode() returns header strings with url safe values
     * 
     * @param string $text This holds the strings to be replaced with url safe values
     * 
     * @access public  
     * 
     * @return string
     */
    public function base64UrlEncode(string $text): string 
    {
        $convert_string = base64_encode($text);
        $safe_string = str_replace(["+", "/", "="], ["-", "_", ""], $convert_string);
        return $safe_string;
    }
    //*===========================================================================*//

    
    //*===========================================================================*//
    /**
     * The baseUrlDecode() returns header strings with the original values
     * 
     * @param string $text This holds the strings to be replaced with url safe values
     * 
     * @access public  
     * 
     * @return string
     */
    public function base64UrlDecode(string $text): string 
    {
        $org_string = str_replace(["-", "_"], ["+", "/"], $text);
        $convert_string = base64_decode($org_string);
        return $convert_string;
    }
    //*===========================================================================*//

}