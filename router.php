<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once __DIR__ . '/site-variables.php';

Class Route {
    public function __construct() {
        if(empty($_REQUEST['route']) && isset($_GET['status'])) {
            $_REQUEST['route'] = 'deposit-callback';
            $_REQUEST['key'] = "dkrh2387eruhxbslo89223476weyui3jxnaql1kdk3490vngjsalkjew4rui";
        } else if(empty($_REQUEST['route'])) { // Exit with error if route unspecified
            echo json_encode(array('error' => 'No route specified'));
            exit();
        }
    }

    public function route() {

        $requests = array(
            'signin'                    => '/signin.php',
            'otp'        				=> '/submit-otp.php',
        );

        if(array_key_exists($_REQUEST['route'], $requests)) {

            // Set up sessions
            //$_SESSION['s_id'] = empty(json_decode($_REQUEST['session'])) ? "" : json_decode($_REQUEST['session'])->s_id;

		    require_once __DIR__.$requests[$_REQUEST['route']];
            $endpoint = new Endpoint();
            $endpoint->get_content();
        } else {
            echo json_encode((array('error' => "Invalid Route!")));
            exit();
        }
    }
}

$route = new Route();
$route->route();