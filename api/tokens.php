<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Create Access Refresh Token Page for Api To-Do-List
 * 
 * @category Create_Access_Refresh_Token
 * @package  Src-Folder
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/api/tokens.com
 */


//=================CREATE AN ACCESS TOKEN TO RETRIEVE USER DATA================//
//THE ACCESS TOKEN WILL BE A SINGLE STRING WHICH IS SENT IN THE REQUEST HEADER//
//==========THIS ACCESS TOKEN WILL BE GENERATED USING base64_encode()==========//

// FIRST STORE THE AUTHENTICATED USER DATA IN AN ASSOCIATIVE ARRAY
$payload = [
      "sub" => $user["id"],
      "name" => $user["name"],
      "exp" => time() + 300 // 300sec = 5 minute experation time
];

//=====CALL THE encode() FUNCTION AND PASS THE $payload IN AS THE ARGUMENT========//
$access_token = $JWTcodec->encode($payload);
//=================================================================================//


//=============GENERATE A REFRESH TOKEN WHEN THE ACCESS TOKEN EXPIRES==============//
//CREATE A ASSOCIATIVE ARRAY STORING THE USERS ID AND A EXPIRY OF THE REFRESH TOKEN//
//====CALL THE encode() FUNCTION AND PASS THE $refresh_token IN AS THE ARGUMENT====//
$refresh_token_expiry = time() + 432000; // 432000sec = 5 days experation time;
$refresh_token = [
      "sub" => $user["id"],
      "exp" => $refresh_token_expiry
];

//=====CALL THE encode() FUNCTION AND PASS THE $payload IN AS THE ARGUMENT========//
$encoded_refresh_token = $JWTcodec->encode($refresh_token);
//=================================================================================//

//=============ECHO OUT THE JSON ENCODED ACCESS TOKEN AND REFRESH TOKEN============//
echo json_encode(
    [
      "access_token" => $access_token,
      "refresh_token" => $encoded_refresh_token
    ]
);
//=================================================================================//
