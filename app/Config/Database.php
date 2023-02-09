<?php namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */
class Database extends \CodeIgniter\Database\Config {

  /**
   * The directory that holds the Migrations
   * and Seeds directories.
   *
   * @var string
   */
  public $filesPath = APPPATH . 'Database/';

  /**
   * Lets you choose which connection group to
   * use if no other is specified.
   *
   * @var string
   */
  public $defaultGroup = 'default';

  /**
   * The default database connection.
   *
   * @var array
   */
  public $default = [
   'DSN'      => '',
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => 'sso&koke_2911',
   'database' => 'hckwqroy_gestion',
   'DBDriver' => 'MySQLi',
   'DBPrefix' => '',
   'pConnect' => FALSE,
   'DBDebug'  => (ENVIRONMENT !== 'production'),
   'cacheOn'  => FALSE,
   'cacheDir' => '',
   'charset'  => 'utf8',
   'DBCollat' => 'utf8_general_ci',
   'swapPre'  => '',
   'encrypt'  => FALSE,
   'compress' => FALSE,
   'strictOn' => FALSE,
   'failover' => [],
   'port'     => 3307,
  ];

  /**
   * This database connection is used when
   * running PHPUnit database tests.
   *
   * @var array
   */
 

  //--------------------------------------------------------------------

  public function __construct() {
    parent::__construct();

    // Ensure that we always set the database group to 'tests' if
    // we are currently running an automated test suite, so that
    // we don't overwrite live data on accident.
    if (ENVIRONMENT === 'testing') {
      $this->defaultGroup = 'tests';

      // Under Travis-CI, we can set an ENV var named 'DB_GROUP'
      // so that we can test against multiple databases.
      if ($group = getenv('DB')) {
        if (is_file(TESTPATH . 'travis/Database.php')) {
          require TESTPATH . 'travis/Database.php';

          if (!empty($dbconfig) && array_key_exists($group, $dbconfig)) {
            $this->tests = $dbconfig[$group];
          }
        }
      }
    }
  }

  //--------------------------------------------------------------------

}
