<?php
/**
 * Initiates a connection to the database. Return database connection object
 *
 * @author  Abi Hilary
 * @license Blogvisa Terms of use
 */

class DB
{

	/**
	 * Base URL of API endpoint
	 * @var string
	 */
  public $dbh;


  /**
   * Constructor
   * Connect to the database
   * @param array $params an array containing connection parameters in the format: [host, user, password, database]
   * See live.php for definition of these parameters
   *
   * @return object connection object
   */
  public function __construct($params) {
    try {
      $this->dbh = new PDO(
        'mysql:dbname=' . $params['database'] . ';charset=utf8mb4;host=' . $params['host'], 
        $params['user'], 
        $params['password'], 
        // array(
        //   PDO::ATTR_PERSISTENT => true //cache and avoid reconnecting. See doc for PDO connection management
        // )
      );
    } catch (PDOException $e) {
      print "Error!: ";
      //print "Error!: $e";
      die();
    }
  }
	
  /**
   * Perform a database query
   * 
   * @param array $data: 
   * $data is an array with 3 keys:
   * 1. "query" which holds the query string and parameters
   * 2. "data" which is an array to hold the results from the db
   * 3. "stat" which holds any error info returned from the db
   * For the query key, we need to normalize parameters as multi-dimensionl array, because query parameters can either be:
   * query[0] = query statement,
   * query[1] = parameters
   * or (in which case we don't normalize)
   * query = array(
   *  0 => array(
   *    0 => query statement,
   *    1 => parameters
   *  )
   * ),
   * 1 => array(
   *  0 => query statement,
   *  1 => parameters
   * ),
   * ...
   * 
   * @param array $data: results of the query. Passed as reference
   * @param array $stat: the error message emanating from the query, or an empty array if no error
   *
   */
	public function query( &$data ) {
		$prepared_query = $data['query'][0];
		$param_array = $data['query'][1];

		// Normalize. Parameters should be within an array
		if (!is_array($param_array)) {
			$param_array = array($param_array);
		}

    $sql = $this->dbh->prepare( $prepared_query );

    if($sql->execute( $param_array )) {
      $stat = [];

      // Assign data based on whether query was single or multiple; condition introduced for compatibility
      $data['data'] = $this->order_results($sql);
    } else {
      //Assign error info to status variable. also return empty array as data received
      $data['stat'] = $sql->errorInfo();
      $data = [];
    }

    if(!empty($this->dbh->lastInsertId())) {
      $_SESSION["lastInsertId"] = $this->dbh->lastInsertId();
    }
	}

  /**
   * Order results returned 
   * 
   * @param object $sql: sql query object
   * @return array An associative array of results returned from the database. Array keys are row names and values
   * are column values
   * 
   */
	public function order_results($sql) {
		$a = array();
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$a[] = $row;
		}
		return $a;
	}
}























 ?>