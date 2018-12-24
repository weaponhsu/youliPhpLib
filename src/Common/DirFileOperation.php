<?php
/**
 *
 *            ┏┓　　  ┏┓+ +
 *           ┏┛┻━━━━━┛┻┓ + +
 *           ┃         ┃ 　
 *           ┃   ━     ┃ ++ + + +
 *          ████━████  ┃+
 *           ┃　　　　　 ┃ +
 *           ┃　　　┻　　┃
 *           ┃　　　　　 ┃ + +
 *           ┗━┓　　　┏━┛
 *             ┃　　　┃　　　　
 *             ┃　　　┃ + + + +
 *             ┃　　　┃    Code is far away from bug with the alpaca protecting
 *             ┃　　　┃ + 　　　　        神兽保佑,代码无bug
 *             ┃　　　┃
 *             ┃　　　┃　　+
 *             ┃     ┗━━━┓ + +
 *             ┃         ┣┓
 *             ┃ 　　　　　┏┛
 *             ┗┓┓┏━━┳┓┏━┛ + + + +
 *              ┃┫┫  ┃┫┫
 *              ┗┻┛  ┗┻┛+ + + +
 * Created by PhpStorm.
 * User: weaponhsu
 * Date: 2018/12/24
 * Time: 10:37 AM
 */

namespace youliPhpLib\Common;


class DirFileOperation
{
    private $__file_path = '';

    private $__error;

    public function testFunc() {
        return __METHOD__;
    }

    /**
     * @param $_file_path
     * @return $this
     */
    public function setFilePath($_file_path)
    {
        $this->__file_path = $_file_path;
        return $this;
    }

    public function __construct($file_path = '')
    {
        $this->__error = 0;
        $this->__file_path = !empty($file_path) && is_string($file_path) ? trim(str_replace(['\\', '/'], '/', $file_path)) : '';
        if(empty($this->__file_path)){
            $this->__error = 1;
        }
    }

    public function chkFileDir(){
        if($this->__error === 0){
            $this->_chkDirExists();
        }

        return  $this->__getResult();
    }

    public function genFile(){
        if($this->__error === 0){
            $dir_file_arr = pathinfo($this->__file_path);
            if(!isset($dir_file_arr['extension'])){
                $this->__error = 2;
            }else{
                //生成文件路径
                $this->_chkDirExists();
                if(!file_exists($this->__file_path)){
                    $db_log_file_handle = fopen($this->__file_path, "w");
                    fclose($db_log_file_handle);
                }
            }
        }

        return $this->__getResult();
    }

    public function listDir($file_path = '', $recursion = false){
        $return = [];
        $file_path = !empty($file_path) ? $file_path : $this->__file_path;
        if(is_dir($file_path)){
            if($dir_handle = opendir($file_path)){
                while(($file = readdir($dir_handle)) !== false){
                    if((is_dir($file_path . "/" . $file)) && $file != "." && $file != ".." && $file != ".svn"){
                        if($recursion === true){
                            $this->listDir($file_path . '/' . $file, true);
                        }else{
                            $return['dir'][] = $file;
                        }
                    }else{
                        if($file != "." && $file != ".."/*strpos($file, '.') !== false*/){
                            $return['file'][] = $file;
                        }
                    }
                }
            }
        }
        return $return;
    }

    protected function _getRealDir(){
        if($this->__error === 0){
            $dir_file_arr = pathinfo($this->__file_path);
            if(isset($dir_file_arr['extension'])){
                $dir_file_arr = substr($this->__file_path, 0, strrpos($this->__file_path, '/'));
            }
        }
        return $dir_file_arr;
    }

    protected function _chkDirExists(){
        if($this->__error === 0){
            $real_dir = $this->_getRealDir();

            $file_dir_arr = explode('/', $real_dir);

            $real_file_dir = $file_dir_arr[0];

            for($i = 1; $i <= count($file_dir_arr); $i++){
                if(isset($file_dir_arr[$i])){
                    $real_file_dir .= '/' . $file_dir_arr[$i];
                    if(!is_dir($real_file_dir)){
                        mkdir($real_file_dir);
                    }
                }
            }
        }
        return $this;
    }

    private function __getResult(){
        $msg = true;
        if($this->__error !== 0){
            switch ($this->__error){
                case '1':
                    $msg = '要检测路径是否完整的文件不能为空';
                    break;
            }
        }
        return $msg;
    }
}
