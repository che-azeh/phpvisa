<?php
require_once __DIR__ . "/../site-variables.php";

Class SectionVariables extends API {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
    }

    /**
     * Check if the user is logged in
     * @param integer $userId: The ID of the user logged in
     * @return void: disconnects if the user isn't logged in, else proceeds with request
     * 
     **/
    private function check_login() {
        $access_token = $this->getBearerToken();
        if(!$this->verifyJWTToken($access_token)) {
            $this->respond(array(
              "error" => array(
                  "type" => "general",
                  "message" => "You've been logged out of the server. Please log in to continue"
              )
            ));
        }
    }

    /** 
     * Get header Authorization
     * */
    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Get user's current balance
     * @param $userId: user ID
     * @return balance
     * */
    public function get_user_balance($userId) {

        // Get sum of all transactions
        $this->query(
          "SELECT SUM(amount) AS total FROM transactions WHERE userId = ? AND transaction_type = 'deposit' AND status = 'SUCCESSFUL' UNION SELECT SUM(amount) FROM transactions WHERE userId = ? AND transaction_type != 'deposit' AND status != 'FAILED'",
          array($userId, $userId),
          "balance"
        );

        return $this->query_data["balance"]["data"];
    }
}