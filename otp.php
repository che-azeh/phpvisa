<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function load_data() {
        $this->query(
            "SELECT * FROM users WHERE email = ? AND stat > ?",
            array($_GET["email"], 0),
            "user"
        );
    }

    public function get_content() {
        $this->load_data();
        if (empty($this->query_data["user"]["stat"])) {
            if ( !empty($this->query_data["user"]["data"]) && password_verify($_GET["password"], $this->query_data["user"]["data"][0]["password"]) ) {

                unset($this->query_data["user"]["data"][0]["password"]);
                $data = $this->query_data["user"]["data"][0];

                $user = array(
                  "uuid"        => $data['id'],
                  "from"        => "",
                  "password"    => "",
                  "role"        => $data["role"],
                  "stat"        => $data["stat"],
                  "data"        => array(
                    "displayName"     => $data["names"],
                    "photoURL"        => $data["photoURL"],
                    "email"           => $data["email"],
                  )
                );

                $jwtToken = $this->generateJWTToken($user);

                $this->respond(array(
                  "user" => $user,
                  "access_token" => $jwtToken,
                ));
            } else {
              $this->report_invalid();
            }
        }
    }

    private function report_invalid() {
      $this->respond(
        array(
          "error" => array(
            array(
              "type" => "password",
              "message" => "Invalid credentials!"
            )
          )
        )
      );
    }
}