<?php
require_once __DIR__."/site-variables.php";

Class Endpoint extends API {

    public function get_content() {

        $this->query(
            "SELECT * FROM users WHERE id = ? OR id = ? OR email = ?",
            array(1, 2, 'cedricipkiss@gmail.com'),
            "user"
        );

        $this->respond(
            array(
                "user" => $this->query_data["user"]["data"]
            )
        );
    }
}