<?php

class Logger {

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

  private $_class = "Logger";

  public function setLevel($_level) {
    if (Logger::ALL > $_level || Logger::NONE < $_level) {
      //TODO: retard :))
    } else {
      $this->$_level = $_level;
    }
  }

  public function getLevel() {
    return self::$_level;
  }

  public function log($_message, $_level) {
    if ($this->_level <= $_level && self::NONE >= $_level) {
      $line = "";
      $line .= date("Y-m-d G:i:s");
      $line .= "\t";
      $line .= $this->_decodeLevel($_level);
      $line .= "\t";
      $line .= $this->_class;
      $line .= "\t";
      $line .= $_message;
      $line .= "\n";
      
      if(FALSE === fwrite($this->_fileHandler, $line)) {
        throw new Exception($this->_errorMessages['write']);
      }
    }
  }

  private function _decodeLevel($_level) {
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

  public function __construct($_class, $_level = self::ALL, $_logFilePath = FALSE) {
    $this->_class = $_class;
    $this->_level = $_level;
    // default location
    if (FALSE === $_logFilePath) {
      $_logFilePath = "/tmp/geekrpglog/" . "log_" . date("Y-m-d") . ".log";
    }
    print $_logFilePath;
    $this->_logFilePath = $_logFilePath;
    // extract directory
    $logDirectory = dirname($_logFilePath);
    print $logDirectory;
    // create if not there
    if (!file_exists($logDirectory)) {
      if(!mkdir($logDirectory, 0777, true)) {
        throw new Exception($this->_errorMessages['write']); 
      }
    }
    print "created folder";
    // no write access?
    if (file_exists($this->_logFilePath) && !is_writable($this->_logFilePath)) {
      throw new Exception($this->_errorMessages['write']);
    }
    // try to open it
    $this->_fileHandler = fopen($this->_logFilePath, 'a');
    if (FALSE === $this->_fileHandler) {
      throw new Exception($this->_errorMessages['open']);
    }
  }

  public function __destruct() {
    if ($this->_fileHandler) {
      fclose($this->_fileHandler);
    }
  }
}

?>
