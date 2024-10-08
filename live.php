<?php 
/**
 * Set various variables based on whether app is live or local.
 * Typically, only one variable will be changed in this file, the $live variable.
 * Set to false when local, and to true when live.
 *
 * @author  Cedric Che-Azeh
 * @license Blogvisa Terms of Use
 */

 session_start();

class Live  {
  public $live = true; // Change based on state of app

  /**
   * Return base URL for app API
   *
   * @return string
   */
  public function getHome() {
    return $this->live ? 'https://drone.bleaglee.com/' : 'http://localhost/bleaglee-drone/';
  }

  /**
   * Return root resource for app API
   *
   * @return string
   */
  public function getRoot() {
    return $this->live ? $_SERVER['DOCUMENT_ROOT'].'/' : $_SERVER['DOCUMENT_ROOT'].'/bleaglee-drone/';
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
      'password'  => '',
      'database'  => ''
    );

    if($this->live) {
      $dbo = array(
        'host'      => 'localhost',
        'user'      => '',
        'password'  => '',
        'database'  => ''
      );
    }

    return $dbo;
  }
  
  	
}

