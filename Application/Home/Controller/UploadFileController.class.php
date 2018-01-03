<?php
namespace Home\Controller;
use Think\Controller;
class UploadFileController extends Controller{
    public function textEditImg(){
        $verifyToken = md5(C('UNIQUE_SALT_UPLOAD_KEY') . $_GET['timestamp']);
        if (!empty($_FILES) && $_GET['token'] == $verifyToken && $_GET['uid']) {
            $imgInfo = getimagesize($_FILES['file']["tmp_name"]);
            $filesize=abs(filesize($_FILES['file']["tmp_name"]));
            
            empty($imgInfo) && $this->exitData('-3', '图片获取失败');
            $filesize > 614400 && $this->exitData('-4', '图片不能大于600k');
            
            if(C('UPLOAD_TYPE')==2){
                import("@.Oss.OssUpload");
                $targetFile = \OssUpload::upload_file_by_file($_FILES ['file'], explode(',', 'jpg,png,jpeg,gif'));
                !$targetFile && $this->exitData('-1');
            }else{
                $uploadFile= $this->_upload($_FILES['file'], explode(',', 'jpg,png,jpeg,gif'), C('UPLOAD_FILE_PATH'));
                !$uploadFile && $this->exitData('-1');
                $targetFile =  '/'.str_replace(C('UPLOAD_FILE_PATH'),  '', $uploadFile[0]['savepath']).$uploadFile[0]['savename'];
            }
            
            $targetFile = C('UPLOAD_FILE_URL').$targetFile;
            $this->exitData(0, array('src' => $targetFile, 'title' => date('Ymd')));
        }
        
        $this->exitData('-2');
    }
    
    private function _upload($file, $allowExt, $path = '') {
        import("ORG.Util.UploadFile");
        $upload = new \UploadFile();
        //设置上传文件大小
        $upload->maxSize = 1024*1024*1024;
        $upload->allowExts = $allowExt;
    
        if (empty($path)) {
            $upload->savePath = C('UPLOAD_FILE_PATH').date("Ym/d/");
        } else {
            $upload->savePath = $path.date("Ym/d/");
        }
        
        if (!$upload->uploadOne($file)) {
            //捕获上传异常
            return false;
        }
    
        return $upload->getUploadFileInfo();
    }
    
    public function exitData($code, $data){
        $returnArr = array(
            'code' => $code
        );
        
        if(is_array($data)){
            $returnArr['data'] = $data;
        }else{
            $returnArr['msg'] = $data;
        }
        
        exit(json_encode($returnArr));
    }
    
    private function echoMsg($msg){
        $content = json_encode($msg, JSON_UNESCAPED_SLASHES);
        !is_dir('/asuma/') && mkdir('/asuma/' , 0777 , true);
        file_put_contents('/asuma/toolimg.log' , $content."\r\n" ,FILE_APPEND);
    }
}