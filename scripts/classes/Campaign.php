<?php

class Campaign extends Entity {

  public function getSqlSourceSelect() {
    return "
      select
        c.*,
        ov.label campaign_type,
        ov2.label campaign_status
      from
        civicrm_campaign c
      left outer join
        civicrm_option_value ov on c.campaign_type_id = ov.value
      left outer join      
        civicrm_option_group og on ov.option_group_id = og.id and og.name = 'campaign_type'
      left outer join
        civicrm_option_value ov2 on c.status_id = ov2.value
      left outer join      
        civicrm_option_group og2 on ov2.option_group_id = og2.id and og2.name = 'campaign_status'      
      order by
        c.id
    ";
  }

  public function getSqlTargetCreate() {
    return '
      drop table if exists campaigns;
      create table campaigns (
        _KEY_campaigns int(10) primary key,
        campaign_id int(10),
        campaign_type varchar(255),
        title varchar(255),
        start_date datetime,
        end_date datetime,
        campaign_status varchar(255),
        goal_revenue decimal(20,2)
      );
    ';
  }

  public function getSqlTargetInsert($source) {
    $sql = "
      insert into campaigns (
        _KEY_campaigns,
        campaign_id,  
        campaign_type,
        title,
        start_date,
        end_date,
        campaign_status,
        goal_revenue                     
      )
      values (
        ?, ?, ?, ?, ?, ?, ?, ?      
      )
    ";

    $sqlParams = [
      $source['id'],
      $source['id'],
      $source['campaign_type'],
      $source['title'],
      $source['start_date'],
      $source['end_date'],
      $source['campaign_status'],
      $source['goal_revenue'],
    ];

    return [$sql, $sqlParams];
  }

  public function getSqlBridgeAlter() {
    return '
      alter table _bridge add column _KEY_campaigns int(10);
      alter table _bridge add constraint fk_KEY_campaigns FOREIGN KEY (_KEY_campaigns) REFERENCES campaigns(_KEY_campaigns);
    ';
  }

  public function getSqlBridgeInsert($source) {
    $sql = "
      insert into _bridge (
        stage,
        _KEY_campaigns                   
      )
      values (
        'campaigns',
        ?
      )
    ";

    $sqlParams = [
      $source['id'],
    ];

    return [$sql, $sqlParams];
  }

}