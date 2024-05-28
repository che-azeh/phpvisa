<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_content() {
        $this->get_user($_POST['user']);
        
        if (empty($this->query_data["user"]["stat"])) { // No errors
          if(!empty($this->query_data["user"]["data"])) { // User exists and OTP found
            if($this->query_data["user"]["data"][0]["otp"] == $_POST["otp"] && ( (int)$this->query_data["user"]["data"][0]["otp_time"] + 300 ) > time() ) {

              // Code matches, and is less than 5 minutes old; generate token
              $user = $this->query_data["user"]["data"][0];
              unset($user["otp"]);
              unset($user["otp_time"]);

              $this->respond(
                array(
                  "token" => $this->generateJWTToken($user),
                  "stat" => empty($this->query_data["user"]["data"][0]["last_name"]) ? 0 : 1
                )
              );
            } else if( ( (int)$this->query_data["user"]["data"][0]["otp_time"] + 300 ) < time()) {

              // Code expired
              $this->respond(
                array("expired" => 1)
              );
            } else {
              $this->respond(
                array("invalid" => 1)
              );
            }

          }
        }
    }
}