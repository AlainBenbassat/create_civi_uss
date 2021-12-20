<?php

class Bridge {
  public function __construct($db) {
    $this->db = $db;
  }

  public function create() {
    $createStatement = '
      drop table if exists _bridge;
      create table _bridge (
        stage varchar(255)
      );
    ';

    $this->db->targetPDO->exec($createStatement);
  }
}