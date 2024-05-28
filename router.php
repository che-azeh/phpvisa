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
            'save-user'                 => '/me/save-user.php',
            'products'                  => '/products.php',
            'product-details'           => '/product-details.php',
            'bot'                       => '/me/bot.php',
            'generate-quote'            => '/me/generate-quote.php',
            'test'                      => '/me/test.php',
            'users'                     => '/users.php',
            'calculate-premium'         => '/me/calculate-premium.php',
            'subscriptions'             =>'/me/subscriptions.php',
            'subscription-details'      =>'/me/subscription-details.php',
            'all-subscriptions'         =>'/all-subscriptions.php',
            'ourAgencies'               =>'/our-agencies.php',
            'agenciesDetails'           =>'/get-agencies-details.php',
            'addAgencies'               =>'/add-agencies.php',
            'modifyAgencies'            =>'/modify-agency.php',
            'deleteAgencies'            =>'/delete-agency.php',
            'healthCenters'             =>'/accredited-health-centers.php',
            'healthCenterDetails'       =>'/get-health-center-details.php',
            'addHealthCenters'          =>'/add-health-center.php',
            'modifyHealthCenters'       =>'/modify-health-center.php',
            'deleteHealthCenters'       =>'/delete-center.php',
            'get-bot-questions'         =>'/get-bot-questions.php',
            'get-question-details'      =>'/get-question-details.php',
            'get-button'                =>'/get-button.php',
            'update-button'             =>'/update-button.php',
            'add-button'                =>'/add-button.php',
            'add-question'              =>'/add-question.php',
            'delete-question'           =>'/delete-question.php',
            'delete-button'             =>'/delete-button.php',
            'pay'                       =>'/pay.php',
            'deposit-callback'          =>'/deposit-callback.php',
            'verify-payment'            =>'/verify-payment.php',
            'save-single-response'      =>'/me/save-single-response.php',
            'get-countries'             =>'/get-countries.php',
            'get-cities'                =>'/get-cities.php',
            'clear-bot'                 =>'/me/clear-bot.php',
            'edit-response'             =>'/me/edit-response.php',
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