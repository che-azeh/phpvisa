<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_content() {
        $this->respond(
            array(
                'version' => '1.1'
            )
        );
    }
}