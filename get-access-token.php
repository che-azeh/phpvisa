<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_content() {

      if ($this->verifyJWTToken($_GET['access_token'])) {

        // Decode
        $user = explode(".", $_GET['access_token'])[1]; // Item 1 of access token is payload
        $user = (array)json_decode(base64_decode($user)); // base64 decode it, then json decode, then convert from STDClass to array
        unset($user['iat']); // unset time
        unset($user['exp']); // unset expiry
        unset($user['iss']); // unset expiry

        $updatedAccessToken = $this->generateJWTToken($user);

        $this->respond(array(
          "user" => $user,
          "access_token" => $updatedAccessToken,
        ));
      }
    }
}