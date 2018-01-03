<?php
namespace Home\Model;
class PanelModel extends PublicModel{
    protected $autoCheckFields = false;
    
    /**
     * 正则进行的项目
     */
    public function getNowDoingProject($department_id){
        $sql = "SELECT id,name FROM project_list WHERE status IN(1,2) AND department_id = :department_id AND start_time <= :nowtime AND end_time >= :nowtime ORDER BY id ASC LIMIT 0,3";
        
        return $this->native_select_all($sql, array('department_id' => $department_id, 'nowtime' => date('Ymd')));
    }
    
    /**
     * 未处理总数
     */
    public function getUndoneBugCount($uid){
        $sql = "SELECT COUNT(*) AS num FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status IN(0,2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id";        
        $result = $this->native_select_one($sql, array('toer_id' => $uid));
        
        return $result['num'];
    }
    
    /**
     * 今日未处理总数
     */
    public function getTodayUndoneBugCount($uid){
        $start_time = strtotime(date('Ymd0000'));
        $end_time = strtotime(date('Ymd2359'));
        
        $sql = "SELECT COUNT(*) AS num FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status IN(0,2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id AND bg.create_time >= :start_time AND bg.create_time <= :end_time";
        $result = $this->native_select_one($sql, array('toer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
    
        return $result['num'];
    }
    
    /**
     * 昨日已处理总数
     */
    public function getLastDoneBugCount($uid){
        $start_time = strtotime(date('Ymd0000', strtotime('-1 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
        
        $sql = "SELECT COUNT(*) AS num FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status IN(1,-2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id AND bg.finall_time >= :start_time AND bg.finall_time <= :end_time";
        $result = $this->native_select_one($sql, array('toer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
        
        return $result['num'];
    }
    
    /**
     * 今日已处理总数
     */
    public function getTodayDoneBugCount($uid){
        $start_time = strtotime(date('Ymd0000'));
        $end_time = strtotime(date('Ymd2359'));
        
        $sql = "SELECT COUNT(*) AS num FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status IN(1,-2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id AND bg.finall_time >= :start_time AND bg.finall_time <= :end_time";
        $result = $this->native_select_one($sql, array('toer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
        
        return $result['num'];
    }
    
    /**
     * 昨日我反馈的总数
     */
    public function getMysendBugCount($uid){
        $start_time = strtotime(date('Ymd0000', strtotime('-1 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
        
        $sql = "SELECT COUNT(*) AS num FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.fromer_id = :fromer_id AND bg.create_time >= :start_time AND bg.create_time <= :end_time";
        $result = $this->native_select_one($sql, array('fromer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
        
        return $result['num'];
    }
    
    /**
     * 图表 --我收到的Bug
     */
    public function getGraphReportMyBugData($uid){
        $start_time = strtotime(date('Ymd0000', strtotime('-7 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
        
        $sql = "SELECT COUNT(bg.id) num,FROM_UNIXTIME(bg.create_time, '%Y%m%d') date FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id AND bg.create_time >= :start_time AND bg.create_time <= :end_time 
            GROUP BY FROM_UNIXTIME(bg.create_time, '%Y%m%d')";
        
        return $this->native_select_all($sql, array('toer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
    }
    
    /**
     * 图表 --已处理的Bug
     */
    public function getGraphReportDoneData($uid){
        $start_time = strtotime(date('Ymd0000', strtotime('-7 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
    
        $sql = "SELECT COUNT(bg.id) num,FROM_UNIXTIME(bg.finall_time, '%Y%m%d') date FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status IN(1,-2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id AND bg.finall_time >= :start_time AND bg.finall_time <= :end_time 
            GROUP BY FROM_UNIXTIME(bg.finall_time, '%Y%m%d')";
    
        return $this->native_select_all($sql, array('toer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
    }
    
    /**
     * 图表 --我反馈的Bug
     */
    public function getGraphReportMysendData($uid){
        $start_time = strtotime(date('Ymd0000', strtotime('-7 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
    
        $sql = "SELECT COUNT(bg.id) num,FROM_UNIXTIME(bg.create_time, '%Y%m%d') date FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.fromer_id = :fromer_id AND bg.create_time >= :start_time AND bg.create_time <= :end_time
            GROUP BY FROM_UNIXTIME(bg.create_time, '%Y%m%d')";
    
        return $this->native_select_all($sql, array('fromer_id' => $uid, 'start_time' => $start_time, 'end_time' => $end_time));
    }
}