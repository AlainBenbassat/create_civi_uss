<?php

class Contribution extends Entity {

  public function getSqlSourceSelect() {
    return "
      select
        c.*,
        ft.name financial_type,     
        ov.label contribution_status     
      from
        civicrm_contribution c
      inner join
        civicrm_option_value ov on c.contribution_status_id = ov.value
      inner join      
        civicrm_option_group og on ov.option_group_id = og.id and og.name = 'contribution_status'
      inner join
        civicrm_financial_type ft on ft.id = c.financial_type_id     
      order by
        c.id
    ";
  }

  public function getSqlTargetCreate() {
    return '
      drop table if exists contributions;
      create table contributions (
        _KEY_contributions int(10) primary key,
        contribution_id int(10),
        contact_id int(10),
        receive_date datetime,
        total_amount decimal(20,2),
        campaign_id int(10)  
      );
    ';
  }

  public function getSqlTargetInsert($source) {
    $sql = "
      insert into contributions (
        _KEY_contributions,
        contribution_id,  
        contact_id,
        receive_date,
        total_amount,
        campaign_id
      )
      values (
        ?, ?, ?, ?, ?, ?      
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['id'],
      $source['contact_id'],
      $source['receive_date'],
      $source['total_amount'],
      $source['campaign_id'],
    ];

    return [$sql, $sqlParams];
  }

  public function getSqlBridgeAlter() {
    return '
      alter table _bridge add column _KEY_contributions int(10);
      alter table _bridge add constraint fk_KEY_contributions FOREIGN KEY (_KEY_contributions) REFERENCES contributions(_KEY_contributions);
    ';
  }

  public function getSqlBridgeInsert($source) {
    $sql = "
      insert into _bridge (
        stage,
        _KEY_contributions,
        _KEY_contacts,
        _KEY_campaigns                   
      )
      values (
        'events',
        ?, ?, ?
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['contact_id'],
      $source['campaign_id'],
    ];

    return [$sql, $sqlParams];
  }

}