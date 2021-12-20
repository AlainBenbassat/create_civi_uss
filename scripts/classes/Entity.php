<?php

abstract class Entity {
  protected $db;

  abstract public function getSqlSourceSelect();
  abstract public function getSqlTargetCreate();
  abstract public function getSqlTargetInsert($source);
  abstract public function getSqlBridgeAlter();
  abstract public function getSqlBridgeInsert($source);

  public function __construct($db) {
    $this->db = $db;
  }

  public function convert() {
    $this->createTargetTable();
    $this->alterBridge();
    $this->copyFromSourceToTarget();
  }

  public function createTargetTable() {
    $createStatement = $this->getSqlTargetCreate();
    $this->db->targetPDO->exec($createStatement);
  }

  public function alterBridge() {
    $alterStatement = $this->getSqlBridgeAlter();
    if ($alterStatement) {
      $this->db->targetPDO->exec($alterStatement);
    }
  }

  private function copyFromSourceToTarget() {
    $sqlSelect = $this->getSqlSourceSelect();
    $dao = $this->db->sourcePDO->query($sqlSelect);
    while ($sourceRecord = $dao->fetch()) {
      $this->insertIntoTarget($sourceRecord);
      $this->insertIntoBridge($sourceRecord);
    }
  }

  private function insertIntoTarget($sourceRecord) {
    [$sqlInsert, $sqlParams] = $this->getSqlTargetInsert($sourceRecord);
    $stmt = $this->db->targetPDO->prepare($sqlInsert);
    $stmt->execute($sqlParams);
  }

  private function insertIntoBridge($sourceRecord) {
    [$sqlInsert, $sqlParams] = $this->getSqlBridgeInsert($sourceRecord);
    $stmt = $this->db->targetPDO->prepare($sqlInsert);
    $stmt->execute($sqlParams);
  }

}