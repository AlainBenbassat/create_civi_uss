<?php

function main() {
  $settingsFile = 'settings.php';

  if (existsSettingsFile($settingsFile)) {
    startConversion($settingsFile);
  }
  else {
    createSettingsFile($settingsFile);
    printInstructions($settingsFile);
  }
}

function existsSettingsFile($settingsFile) {
  return file_exists($settingsFile);
}

function startConversion($settingsFile) {
  echo "Converting...\n";
}

function createSettingsFile($settingsFile) {
  $dummySettings = <<< 'END'
<?php
/**************************************************
* Create CiviCRM Unified Star Schema: settings file
***************************************************/

// specify how to connect to the CiviCRM database
$source_db_host='localhost';
$source_db_port=3306;
$source_db_name='REPLACE-ME';
$source_db_charset='utf8';
$source_db_user='REPLACE-ME';
$source_db_password='REPLACE-ME';

// specify in which database the unified star schema has to be created
$target_db_host='localhost';
$target_db_port=3306;
$target_db_name='REPLACE-ME';
$target_db_charset='utf8';
$target_db_user='REPLACE-ME';
$target_db_password='REPLACE-ME';

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

main();
