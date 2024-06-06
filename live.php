<?php 
/**
 * Set various variables based on whether app is live or local.
 * Typically, only one variable will be changed in this file, the $live variable.
 * Set to false when local, and to true when live.
 *
 * @author  Abi Hilary
 * @license Blogvisa Terms of Use
 */

 session_start();

class Live  {
  public $live = false; // Change based on state of app

  /**
   * Return base URL for app API
   *
   * @return string
   */
  public function getHome() {
    return $this->live ? 'https://blogvisa.com/' : 'http://localhost/phpvisa/';
  }

  /**
   * Return root resource for app API
   *
   * @return string
   */
  public function getRoot() {
    return $this->live ? $_SERVER['DOCUMENT_ROOT'].'/' : $_SERVER['DOCUMENT_ROOT'].'/phpvisa/';
  }

  /**
   * Return database connection parameters
   *
   * @return array [host, user, password, database]
   */
  public function getDBParams() {
    $dbo = array(
        'host'      => 'localhost',
        'user'      => 'root',
        'password'  => '12345',
        'database'  => 'phpvisa'
    );

    if($this->live) {
      $dbo = array(
        'host'      => 'localhost',
        'user'      => 'maxwellt_phpvisa',
        'password'  => '3cAZPBHKPyxHakkF3Tqq',
        'database'  => 'maxwellt_phpvisa'
      );
    }

    return $dbo;
  }
  
  	
}

