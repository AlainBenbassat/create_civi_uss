<?php

class UssCreator {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function start() {
    $this->createBridge();

    $this->convertEntities([
      /*'contacts' => 'Contact',
      'events' => 'Event',*/
      'participants' => 'Participant',
    ]);
  }

  private function createBridge() {
    echo "Creating bridge...\n";

    $b = new Bridge($this->db);
    $b->create();
  }

  private function convertEntities($entities) {
    foreach ($entities as $entity => $className) {
      echo "Converting $entity...\n";

      $e = new $className($this->db);
      $e->convert();
    }
  }
}