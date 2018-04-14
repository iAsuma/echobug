<?php
namespace Home\Controller;
class PanelController extends EntranceController{
    public function index(){
        $model = D('Panel');
        $project = $model -> getNowDoingProject($this->department);
        
        $this->assign('pjt', $project);
        $this->display();
    }
    
    public function indexReport(){
        $model = D('Panel');
        
        $undone = $model -> getUndoneBugCount($this->uid);
        $today_undone = $model -> getTodayUndoneBugCount($this->uid);
        $last_done = $model -> getLastDoneBugCount($this->uid);
        $today_done = $model -> getTodayDoneBugCount($this->uid);
        $last_mysend = $model -> getMysendBugCount($this->uid);
        
        $graph_data = $this->getGraphData($model);
        
        $data = array(
            'undone' => $undone,
            'today_undone' => $today_undone,
            'last_done' => $last_done,
            'today_done' => $today_done,
            'last_mysend' => $last_mysend,
            'graph' => $graph_data
        );
        
        exit(json_encode($data));
    }
    
    private function getGraphData($model){
        $mybug_report = $model -> getGraphReportMyBugData($this->uid);
        $done_report = $model -> getGraphReportDoneData($this->uid);
        $mysend_report = $model -> getGraphReportMysendData($this->uid);
        
        foreach ($mybug_report as $v){
            $newList[0][(int)$v['date']] = $v['num'];
        }
        
        foreach ($done_report as $v){
            $newList[1][(int)$v['date']] = $v['num'];
        }
        
        foreach ($mysend_report as $v){
            $newList[2][(int)$v['date']] = $v['num'];
        }
        
        $start_time = strtotime(date('Ymd0000', strtotime('-7 day')));
        $end_time = strtotime(date('Ymd2359', strtotime('-1 day')));
        $dayList = createNewArr($start_time, 24*3600, $end_time);
        
        foreach($dayList as $v) {
            $graph['date'][] = date('Y/m/d', $v);
            $report[0][] = (int)$newList[0][(int)date('Ymd', $v)];
            $report[1][] = (int)$newList[1][(int)date('Ymd', $v)];
            $report[2][] = (int)$newList[2][(int)date('Ymd', $v)];
        }
        
        $graph['title'] = '7天内的数据';
        $graph['subtitle'] = $graph['date'][0].'-'.$graph['date'][6];
        $graph['legend'] = array('我收到的', '已处理的', '我反馈的');
        $graph['report'] = $report;
        
        return $graph;
    }
}