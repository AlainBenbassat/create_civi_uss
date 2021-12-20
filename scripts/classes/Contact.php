<?php

class Contact extends Entity {

  public function getSqlSourceSelect() {
    return "
      select
        c.*   
      from
        civicrm_contact c
      where
        is_deleted = 0
      order by
        c.id
    ";
  }

  public function getSqlTargetCreate() {
    return '
      drop table if exists contacts;
      create table contacts (
        _KEY_contacts int(11) primary key,
        contact_id int(11),
        contact_type varchar(255),
        first_name varchar(255),
        last_name varchar(255),
        organization_name varchar(255)
      );
    ';
  }

  public function getSqlTargetInsert($source) {
    $sql = "
      insert into contacts (
        _KEY_contacts,
        contact_id,  
        contact_type,
        first_name,
        last_name,
        organization_name 
      )
      values (
        ?, ?, ?, ?, ?, ?      
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['id'],
      $source['contact_type'],
      $source['first_name'],
      $source['last_name'],
      $source['organization_name'],
    ];

    return [$sql, $sqlParams];
  }

  public function getSqlBridgeAlter() {
    return '
      alter table _bridge add column _KEY_contacts int(11);
    ';
  }

  public function getSqlBridgeInsert($source) {
    $sql = "
      insert into _bridge (
        stage,
        _KEY_contacts                   
      )
      values (
        'contacts',
        ?
      )
    ";

    $sqlParams = [
      $source['id'],
    ];

    return [$sql, $sqlParams];
  }

}