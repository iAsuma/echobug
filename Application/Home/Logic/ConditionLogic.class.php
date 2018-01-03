<?php
namespace Home\Logic;
class ConditionLogic {
    public $wheresql = '';
    public $paramData = array();
    public $limitData = '';
    protected $conditionRules = array();
    protected $orderRules = array();
    protected $whereCondition = array();
    protected $page = 1;
    protected $perpage = 20;
    private $condition = array();
    
    /**
     * 伪构造方法，实例化需手动调用
     */
    public function _construct($data, $page = 1, $perpage = 20){
        $this->whereCondition = $data;
        (int)$page && $this->page  = (int)$page;
        (int)$perpage && $this->perpage = (int)$perpage;
        
        $this->loadSearch();
        $this->loadPages();
    }
    
    public function loadSearch() {
        $this->condition = $this->dealField();
        if(empty($this->condition)) {
            return false;
        }
        foreach($this->condition as $k => $value) {
            if(isset($this->whereCondition[$value['htmlFiled']]) && strlen(trim($this->whereCondition[$value['htmlFiled']])) > 0) {
                $value ['operator'] = strtoupper(trim($value ['operator']));
                switch($value ['operator']) {
                    case 'IN':
                        $this->wheresql .= " AND FIND_IN_SET(".$value['field'].", :".$value['param'].") ";
                        break;
                    case 'NOT IN':
                        $this->wheresql .= " AND FIND_IN_SET(".$value['field'].", :".$value['param'].") = 0 ";
                        break;
                    case 'LIKE':
                        $this->wheresql .= " AND Lower(".$value['field'].") LIKE Lower(:".$value['param'].")";
                        break;
                    case 'NO WHERE':
                        break;
                    default:
                        $this->wheresql .= " AND ".$value['field'].' '.$value['operator'].' :'.$value['param'];
                        break;
                }
            }
        }
        $this->getBindParamData();
    }
    
    private function getBindParamData() {
        foreach($this->condition as $k => $value) {
            if(isset($this->whereCondition[$value['htmlFiled']]) && strlen(trim($this->whereCondition[$value['htmlFiled']])) > 0) {
                $this->whereCondition[$value['htmlFiled']] = trim($this->whereCondition[$value['htmlFiled']]);
                $value ['operator'] = strtoupper($value ['operator']);
                if($value ['operator'] == 'LIKE') {
                    $this->paramData[$value['param']] = "'%".$this->whereCondition[$value['htmlFiled']]."%'";
                } else {
                    $this->paramData[$value['param']] = "'".$this->whereCondition[$value['htmlFiled']]."'";
                }
            }
        }
    }
    
    public function loadPages() {
        $pageNum = $this->perpage;
        $startNum = ($this ->page - 1) * $this->perpage;
        $this->limitData = " LIMIT ".$startNum . " , " . $pageNum;
    }
    
    public function setPageCount($sum){
        $this->pageCount = ceil ( $sum / $this->perpage );
    }
    
    public function dealField() {
        if(empty($this->conditionRules)) {
            return array();
        }
    
        $condition = array();
    
        foreach($this->conditionRules as $k => $value) {
            if(isset($value[$this->whereCondition[$k]]) && is_array($value[$this->whereCondition[$k]]) && strlen($this->whereCondition[$value[$this->whereCondition[$k]]['htmlFiled']])) {
                $condition[] = $value[$this->whereCondition[$k]];
            } elseif(strlen(trim($this->whereCondition[$value['htmlFiled']]))>0) {
                $condition[] = $value;
            }
        }
    
        return $condition;
    }
}