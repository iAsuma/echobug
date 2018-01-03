<?php
namespace Home\Model;
use Think\Model;
use Think\Exception;
/**
 * 公共model，封装增删改查
 * @author li shuaiqiu(asuma)
 * @version 1.2 time 2017-11-14
 */
class PublicModel extends Model{
	protected $autoCheckFields = false;
	/**
	 * @param $table 数据表名
	 * @param $param 数组参数
	 * @return 查询一条数据 
	 */
    public function select_one($table, $param, $field = '*'){
        $where = array();
        $bind = array();
        foreach ($param as $k=>$v){
        	$where[$k] = ':'.$k;
        	$bind[':'.$k] = $v;
        }
        
        return $this->table($table)->field($field)->where($where)->bind($bind)->find();
    }
    
    /**
	 * @param $table 数据表名
	 * @param $param 数组参数
	 * @return 查询多条数据
	 */
    public function select_all($table, $param, $field = '*'){
    	$where = array();
    	$bind = array();
    	foreach ($param as $k=>$v){
    		$where[$k] = ':'.$k;
    		$bind[':'.$k] = $v;
    	}
    	
    	return $this->table($table)->field($field)->where($where)->bind($bind)->select();
    }
    
    /**
     * @param $sql sql语句
     * @param $param 绑定参数
     * @return 查询一条数据
     */
    public function native_select_one($sql, $param){
        try {
            $parseSql = $this->bindParam($sql, $param);
            $this->isSelectSql($parseSql);
            
            $result = $this->query($parseSql);
            return $result[0];
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }
    }
    
    /**
     * @param $sql sql语句
     * @param $param 绑定参数
     * @return 查询多条数据
     */
    public function native_select_all($sql, $param){
        try {
            $parseSql = $this->bindParam($sql, $param);
            $this->isSelectSql($parseSql);
            
            $result = $this->query($parseSql);
            return $result;
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }
    }
    
    /**
     * @param $sql sql语句
     * @param $param 绑定参数
     * @return 执行sql操作，$record 为true时返回影响的行数，否则返回(bool) false或true
     */
    public function native_execute($sql, $param, $record=false){
        try {
            $parseSql = $this->bindParam($sql, $param);
            $this->isExecuteSql($parseSql);
            
            $result = $this->execute($sql);
            return $result !== false ? ($record ? $result : true) : false;
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }
    }
	
	/**
     * @param $sqlArr sql数组
     * @param $paramArr 参数数组
     * @return 执行sql事务操作，成功返回true，失败返回false
     */
	public function native_transaction($sqlArr, $paramArr){
    	try {
            if(!is_array($sqlArr) || empty($sqlArr)){
                return false;
            }

            if(!empty($paramArr) && !is_array($paramArr)){
                return false;
            }

            $this->startTrans();
            
            foreach ($sqlArr as $sk => $sv) {
                $parseSql = $this->bindParam($sv, $paramArr[$sk]);
                $this->isExecuteSql($parseSql);
                $result = $this->execute($parseSql);
            }

            if(!$this->commit()){
                $this->rollback();
                return false;
            }else {
                return true;
            }
        } catch (Exception $e) {
            $this->rollback();
            throw_exception($e->getMessage());
        }
    }
    
    /**
     * @param $table 数据表名
     * @param $param 更新的数据
     * @param $condition 更新的条件
     * @return boolean 成功返回true 失败返回false
     */
    public function think_update($table, $param, $condition){
    	try {
    		$this->startTrans();
    		
    		$where = array();
    		$bind = array();
    		$update = array();
    		
    		foreach ($condition as $k=>$v){
    			$where[$k] = ':w_'.$k;
    			$bind[':w_'.$k] = $v;
    		}
    		
    		foreach ($param as $k=>$v){
    			$update[$k] = ':u_'.$k;
    			$bind[':u_'.$k] = $v;
    		}
    		
    		$this->table($table)->where($where)->bind($bind)->save($update);
    		
    		if(!$this->commit()){
    			$this->rollback();
    			return false;
    		}else {
    			return true;
    		}
    	} catch (Exception $e) {
    		$this->rollback();
    		throw_exception($e->getMessage());
    	}
    }
    
    /**
     * @param $table 数据表名
     * @param $param 要插入的数据
     * @param $is_auto_increment 数据表主键是否自增
     * @param string $is_return_id 是否返回当前的主键ID
     * @return boolean 成功返回true或主键ID 失败返回false
     */
    public function think_insert($table, $param, $is_auto_increment=false, $is_return_id=false){
        try {
            $this->startTrans();
            
            $insert = array();
            $bind = array();
            
            if(!$is_auto_increment){
                $key = isset($param['table_key']) ? $param['table_key'] : 'id'; //如果自增主键不是id在 $param里传入table_key的值即可 
                $param[$key] = $this->last_sequence_id($table);
            }
            
            foreach ($param as $k => $v){
                $insert[$k] = ':i_'.$k;
                $bind[':i_'.$k] = $v;
            }
            
            $result = $this->table($table)->bind($bind)->add($insert);
            
            if($is_auto_increment){
                $this->native_select_one("SELECT SETVAL('" . $table . "',".$result.")");
            }
            
            if(!$this->commit()){
                $this->rollback();
                return false;
            }else {
                return $is_return_id === true ? ($is_auto_increment ? $result : $param[$key]) : true;
            }
        } catch (Exception $e) {
            $this->rollback();
            throw_exception($e->getMessage());
        }
    }
    
    /**
     * @param $table 数据表名
     * @param $condition 删除sql的条件
     * @return boolean 成功返回true，失败返回false
     */
    public function think_delete($table, $condition){
    	try {
    	    $this->startTrans();
    	    
    	    $where = array();
    	    $bind = array();
    	    
    	    foreach ($condition as $k=>$v){
    	        $where[$k] = ':w_'.$k;
    	        $bind[':w_'.$k] = $v;
    	    }
    	    
    	    $this->table($table)->where($where)->bind($bind)->delete();
    	    
    	    if(!$this->commit()){
    	        $this->rollback();
    	        return false;
    	    }else {
    	        return true;
    	    }
    	} catch (Exception $e) {
    	    $this->rollback();
    	    throw_exception($e->getMessage());
    	}
    }
    
    /**
     * 获取表统计数量
     * @param $table 数据表名
     * @param $param 数组参数
     * @param string $field 统计的字段
     */
    public function think_count($table, $param, $field='*'){
        $where = array();
        $bind = array();
        foreach ($param as $k=>$v){
            $where[$k] = ':'.$k;
            $bind[':'.$k] = $v;
        }
        
        return $this->table($table)->where($where)->bind($bind)->count($field);
    }
    
    /**
     * 获取表最大ID+1
     * @param $table 表名
     */
    public function last_sequence_id($table){
        $sql = "SELECT NEXTVAL('" . $table . "') AS NUM";
        $result = $this->native_select_one($sql);
        
        if(!$result['num']) {
            throw_exception('获取主键失败：' . $table, '');
        }
        return $result['num'];
    }
    
    /**
     * 判断是否为SELECT语句
     */
    private function isSelectSql($sql){
        if(0 !== strpos(strtoupper($sql), 'SELECT')){
            throw_exception('SQL查询语句不合法：' . $sql, '');
        }
        
        return true;
    }
    
    /**
     * 判断是否为执行类的sql语句
     */
    private function isExecuteSql($sql){
        $extArr = array('UPDATE', 'DELETE', 'INSERT INTO');
        $i = 0;
        foreach ($extArr as $v){
            if(0 === strpos(strtoupper($sql), $v)){
                $i = 1;
                break;
            }    
        }
        
        $i != 1 && throw_exception('SQL语句不合法：' . $sql, '');
        
        return true;
    }
    
    /**
     * 原生sql绑定参数
     */
    private function bindParam($sql, $param){
    	if(!empty($param) && !is_array($param)){
            return false;
        }
        
        foreach ($param as $k => $v){
        	$bind[] = ":".$k;
        	
        	if(strpos($v, "'") === 0 && substr($v, -1) == "'"){
        	    $value[] = $v;
        	}else{
        	    $value[] = "'".$v."'"; //将参数值强制转为带引号的字符串
        	}
        }
        
        return str_replace($bind, $value, $sql);
    }
}