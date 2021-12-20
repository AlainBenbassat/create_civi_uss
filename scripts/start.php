<?php

function main() {
  $settingsFile = 'settings.php';

  try {
    if (existsSettingsFile($settingsFile)) {
      startConversion($settingsFile);
    }
    else {
      createSettingsFile($settingsFile);
      printInstructions($settingsFile);
    }
  }
  catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
    echo "\n\n";
  }
}

function existsSettingsFile($settingsFile) {
  return file_exists($settingsFile);
}

function startConversion($settingsFile) {
  $db = new DB($settingsFile);
  $ussCreator = new UssCreator($db);
  $ussCreator->start();
}

function createSettingsFile($settingsFile) {
  $dummySettings = <<< 'END'
<?php

/**************************************************
* Create CiviCRM Unified Star Schema: settings file
***************************************************/

// specify how to connect to the CiviCRM database
$sourceDbSettings = [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'REPLACE-ME',
  'charset' => 'utf8',
  'user' => 'REPLACE-ME',
  'password' => 'REPLACE-ME',
];

// specify in which database the unified star schema has to be created
$targetDbSettings = [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'REPLACE-ME',
  'charset' => 'utf8',
  'user' => 'REPLACE-ME',
  'password' => 'REPLACE-ME',
];

END;

  file_put_contents($settingsFile, $dummySettings);
}

function printInstructions($settingsFile) {
  echo "============\n";
  echo "Instructions\n";
  echo "============\n";
  echo "Specify the source and target database connection settings in this file:\n";
  echo "    " . realpath($settingsFile) . "\n\n";
  echo "Then start the conversion with:\n";
  echo "    ./create_civi_uss.sh\n\n";
}

spl_autoload_register(function ($class_name) {
  include 'classes/' . $class_name . '.php';
});

main();
