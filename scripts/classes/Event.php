<?php

class Event extends Entity {

  public function getSqlSourceSelect() {
    return "
      select
        e.*,
        ov.label event_type     
      from
        civicrm_event e
      inner join
        civicrm_option_value ov on e.event_type_id = ov.value
      inner join      
        civicrm_option_group og on ov.option_group_id = og.id and og.name = 'event_type'
      order by
        e.id
    ";
  }

  public function getSqlTargetCreate() {
    return '
      drop table if exists events;
      create table events (
        _KEY_events int(10) primary key,
        event_id int(10),
        event_type varchar(255),
        title varchar(255),
        start_date datetime,
        end_date datetime  
      );
    ';
  }

  public function getSqlTargetInsert($source) {
    $sql = "
      insert into events (
        _KEY_events,
        event_id,  
        event_type,
        title,
        start_date,
        end_date 
      )
      values (
        ?, ?, ?, ?, ?, ?      
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['id'],
      $source['event_type'],
      $source['title'],
      $source['start_date'],
      $source['end_date'],
    ];

    return [$sql, $sqlParams];
  }

  public function getSqlBridgeAlter() {
    return '
      alter table _bridge add column _KEY_events int(10);
      alter table _bridge add constraint fk_KEY_events FOREIGN KEY (_KEY_events) REFERENCES events(_KEY_events);
    ';
  }

  public function getSqlBridgeInsert($source) {
    $sql = "
      insert into _bridge (
        stage,
        _KEY_events                   
      )
      values (
        'events',
        ?
      )
    ";

    $sqlParams = [
      $source['id'],
    ];

    return [$sql, $sqlParams];
  }

}