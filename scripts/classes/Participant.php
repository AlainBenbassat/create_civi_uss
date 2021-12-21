<?php

class Participant extends Entity {

  public function getSqlSourceSelect() {
    return "
      select
        p.*,
        st.label status     
      from
        civicrm_participant p
      inner join
        civicrm_participant_status_type st on p.status_id = st.id
      order by
        p.id
    ";
  }

  public function getSqlTargetCreate() {
    return '
      drop table if exists participants;
      create table participants (
        _KEY_participants int(10) primary key,
        participant_id int(10),
        register_date datetime,
        status varchar(255)
      );
    ';
  }

  public function getSqlTargetInsert($source) {
    $sql = "
      insert into participants (
        _KEY_participants,
        participant_id,  
        register_date,
        status
      )
      values (
        ?, ?, ?, ?      
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['id'],
      $source['register_date'],
      $source['status'],
    ];

    return [$sql, $sqlParams];
  }

  public function getSqlBridgeAlter() {
    return '
      alter table _bridge add column _KEY_participants int(10);
      alter table _bridge add constraint fk_KEY_participants FOREIGN KEY (_KEY_participants) REFERENCES participants(_KEY_participants);
    ';
  }

  public function getSqlBridgeInsert($source) {
    $sql = "
      insert into _bridge (
        stage,
        _KEY_participants,                           
        _KEY_events,
        _KEY_contacts                      
      )
      values (
        'participants',
        ?,
        ?,
        ?
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['event_id'],
      $source['contact_id'],
    ];

    return [$sql, $sqlParams];
  }

}