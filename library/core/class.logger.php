<?php

class Logger {

  /**
    * Container for singleton pattern.
    */
  private static $_instances = array();

  /**
    * Getter for singleton pattern.
    */
  public static function getInstance($_logLevel = self::ALL, $_logDirectory = FALSE) {
    if (self::ALL < $_logLevel || self::NONE > $_logLevel) {
      return NULL;
    }

    if (!isset($_instances[$_logLevel])) {
      $_instances[$_logLevel] = new self($_logLevel, $_logDirectory);
    }

    return $_instances[$_logLevel];
  }

  /**
    * Default constructor.
    * @param $_level the default level at and above which to log
    * @param $_logDirectory the default folder where to put the logs
    * @throws Exception on OS write / open failures
    */
  private function __construct($_level = self::ALL, $_logDirectory = FALSE) {
    $this->_level = $_level;
    // default location
    if (FALSE === $_logDirectory) {
      $_logDirectory = "/tmp/geekrpglog";
    }
    $_logFilePath = $_logDirectory 
      . DIRECTORY_SEPARATOR 
      . "log_" 
      . date("Y-m-d") 
      . ".log";
    $this->_logFilePath = $_logFilePath;
    // extract directory
    // $logDirectory = dirname($_logFilePath);
    // create if not there
    if (!file_exists($_logDirectory)) {
      if(!mkdir($_logDirectory, 0777, true)) {
        throw new Exception($this->_errorMessages['write']); 
      }
    }

    // try to open it
    $this->_fileHandler = fopen($this->_logFilePath, 'a');
    if (FALSE === $this->_fileHandler) {
      throw new Exception($this->_errorMessages['open']);
    }
  }


  // LOG levels
  const ALL = 0;
  const DEBUG = 1;
  const INFO = 2;
  const WARN = 3;
  const ERROR = 4;
  const FATAL = 5;
  const NONE = 6;

  private $_errorMessages = array(
    'open' => 'Failed to open', 
    'write' => 'Failed to write to',
  );

  private $_level;

  private $_fileHandler = null;

  private $_logFilePath;

  public static function setLevel($_level) {
    if (Logger::ALL > $_level || Logger::NONE < $_level) {
      //TODO: retard :))
    } else {
      $this->$_level = $_level;
    }
  }

  public static function getLevel() {
    return self::$_level;
  }

  public static function log($_level, $_message) {
    if ($this->_level <= $_level && self::NONE >= $_level) {
      $line = "";
      $line .= date("Y-m-d G:i:s");
      $line .= "\t";
      $line .= $this->_decodeLevel($_level);
      $line .= "\t";
      $line .= $_message;
      $line .= "\n";
      
      if(FALSE === fwrite($this->_fileHandler, $line)) {
        throw new Exception($this->_errorMessages['write']);
      }
    }
  }

  private static function _decodeLevel($_level) {
    switch ($_level) {
      case self::ALL :
        return "ALL";
      case self::DEBUG :
        return "DEBUG";
      case self::INFO :
        return "INFO";
      case self::WARN :
        return "WARN";
      case self::ERROR :
        return "ERROR";
      case self::FATAL :
        return "FATAL";
      case self::NONE :
        return "NONE";
      default :
        return "LOG";
    }
  }

  public function __destruct() {
    if ($this->_fileHandler) {
      fclose($this->_fileHandler);
    }
  }
}

?>
