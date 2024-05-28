<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function load_data() {
        $this->query(
            "SELECT id FROM users WHERE email = ? AND stat > ?",
            array($_POST["email"], 0),
            "user"
        );
    }

    private function save_otp($otp, $id) {
      $this->query(
        "UPDATE users SET otp = ?, otp_time = NOW() WHERE id = ?",
        array($otp, $id),
        "otp"
      );
    }

    private function save_new_user($email,$otp) {
      $this->query(
        "INSERT INTO users (email,first_name,last_name,otp,otp_time) VALUES(?,?,?,?, NOW())",
        array($email, "No Name", "", $otp),
        "new_user"
      );
    }

    public function get_content() {
        $this->load_data();
        if (empty($this->query_data["user"]["stat"])) {

            // Generate OTP
            $otp = random_int(1000, 9999);
            $this->send_email(
                "Your login code is $otp",
                "Your Zenithe Insurance Login Code",
                "Zenithe Insurance PLC <zenithe@maxwelltechnologiesplc.com>",
                $_POST['email']
            );

            if ( !empty($this->query_data["user"]["data"]) ) {

              // Save OTP
              $this->save_otp($otp, $this->query_data["user"]["data"][0]["id"]);
              $this->respond(array("user" => $this->query_data["user"]["data"][0]["id"]));
            } else {
              
              // User doesn't exist, create them and send OTP
              $this->save_new_user($_POST['email'], $otp);
              $this->respond(array("user" => $_SESSION['lastInsertId']));
            }
        }
    }
}