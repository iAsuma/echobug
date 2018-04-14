<?php
require_once 'sdk.class.php';
class OssUpload {
	private static $_oss;
	// 构造函数声明为private,防止直接创建对象
	private function __construct() {
	}
	// 单例方法
	private static function connection() {
		if (! isset ( self::$_oss )) {
			self::$_oss = new ALIOSS ( C ( 'OSS_ACCESS_ID' ), C ( 'OSS_ACCESS_KEY' ), C ( 'OSS_ENDPOINT' ) );
		}
		return self::$_oss;
	}
	public static function upload_file_by_file($file, $extend=array(),$options = array()) {
		self::connection ();
		// 获取文件后缀
		$file ['extension'] = self::getExt ( $file ['name'] );
		//不在允许上传的扩展中，返回失败
		if(!empty($extend)&& !in_array($file ['extension'],$extend)){
			return false;
		}
		// 获取保存文件名
		$file ['savename'] = self::getSaveName ( $file );
		// 最终上传的object
		$object = date ( 'Y/m/d' ) . '/' . $file ['savename'];
		
		$res = self::$_oss->upload_file_by_file ( C ( 'OSS_BUCKET' ), $object, $file ['tmp_name'], $options );
		if (( int ) ($res->status / 100) == 2) {
			return '/'.$object;
		} else {
			return false;
		}
	}
	
	/**
	 * 取得上传文件的后缀
	 *
	 * @access private
	 * @param string $filename
	 *        	文件名
	 * @return boolean
	 */
	private static function getExt($filename) {
		$pathinfo = pathinfo ( $filename );
		return $pathinfo ['extension'];
	}
	
	/**
	 * 根据上传文件命名规则取得保存文件名
	 *
	 * @access private
	 * @param string $filename
	 *        	数据
	 * @return string
	 */
	private function getSaveName($filename) {
		// 使用函数生成一个唯一文件标识号
		$saveName = uniqid () . "." . $filename ['extension'];
		return $saveName;
	}
}