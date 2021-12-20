<?php

class DB {
  public $sourcePDO = null;
  public $targetPDO = null;

  public function __construct($settingsFile) {
    global $sourceDbSettings, $targetDbSettings;
    include $settingsFile;

    $this->sourcePDO = $this->createPDO($sourceDbSettings);
    $this->targetPDO = $this->createPDO($targetDbSettings);
  }

  private function createPDO($settings) {
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => FALSE,
    ];

    $dsn = 'mysql:host=' . $settings['host'] . ':' . $settings['port'] . ';dbname=' . $settings['dbname'] . ';charset=' . $settings['charset'];
    return new PDO($dsn, $settings['user'], $settings['password'], $options);
  }
}
