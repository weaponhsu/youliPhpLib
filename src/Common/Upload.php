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
 * Time: 2:49 PM
 */

namespace youliPhpLib\Common;

use youliPhpLib\ErrMsg\Common\UploadErrMsg;
use Exception;

class Upload
{
    public $tmp_file;
    public $max_width;
    public $max_height;
    public $max_size;
    public $allowed_types;
    public $error;
    public $destination;
    public $file_path;

    /**
     * @param $max_width
     * @return $this
     */
    public function setMaxWidth($max_width)
    {
        $this->max_width = $max_width;
        return $this;
    }

    /**
     * @param $max_height
     * @return $this
     */
    public function setMaxHeight($max_height)
    {
        $this->max_height = $max_height;
        return $this;
    }

    /**
     * @param $max_size
     * @return $this
     */
    public function setMaxSize($max_size)
    {
        $this->max_size = $max_size;
        return $this;
    }

    /**
     * @param $allowed_types
     * @return $this
     */
    public function setAllowedTypes($allowed_types)
    {
        $this->allowed_types = $allowed_types;
        return $this;
    }

    /**
     * @param $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function __construct($upload_file = '')
    {
        $this->tmp_file = !empty($upload_file) ? $upload_file : '';
        if($this->tmp_file)
            $this->error = 0;
        else
            throw new Exception(str_replace('%s', $this->tmp_file['name'], UploadErrMsg::UPLOAD_INVALID_FILE_TYPE),
                UploadErrMsg::UPLOAD_INVALID_FILE_TYPE_NO);
    }

    public function upload(){
        if($this->_isAllowedFileType() === false){
            return $this;
        }

        if($this->_isAllowedFileSize() === false){
            return $this;
        }

        if(empty($this->destination) || !is_dir($this->destination))
            throw new Exception(str_replace('%s', $this->destination, UploadErrMsg::UPLOAD_INVALID_DESTINATION_DIR),
                UploadErrMsg::UPLOAD_INVALID_DESTINATION_DIR_NO);

        $new_file_path = pathinfo(basename($this->tmp_file['name']), PATHINFO_EXTENSION) == 'xls' ?
            $this->destination . $this->tmp_file['name']:
            $this->destination . uniqid() . '.' . pathinfo(basename($this->tmp_file['name']), PATHINFO_EXTENSION);

        if(!move_uploaded_file($this->tmp_file['tmp_name'], $new_file_path))
            throw new Exception(str_replace('%s', $this->destination, UploadErrMsg::UPLOAD_FILE_MOVE_FAILED),
                UploadErrMsg::UPLOAD_FILE_MOVE_FAILED_NO);

        $this->file_path = $new_file_path;

        return $new_file_path;
    }

    protected function _isAllowedFileType(){
        if($this->allowed_types == '*'){
            return true;
        }

        if(is_string($this->allowed_types) &&
            strpos($this->allowed_types, pathinfo(basename($this->tmp_file['name']), PATHINFO_EXTENSION)) !== false){
            return true;
        }

        throw new Exception(str_replace('%s', $this->tmp_file['name'], UploadErrMsg::UPLOAD_INVALID_FILE_TYPE),
            UploadErrMsg::UPLOAD_INVALID_FILE_TYPE_NO);
    }

    protected function _isAllowedFileSize(){
        if($this->max_size <= $this->tmp_file['size'])
            throw new Exception(
                str_replace(['%s', '%max_size'], [$this->tmp_file['name'], $this->max_size], UploadErrMsg::UPLOAD_INVALID_FILE_MAX_SIZE),
                UploadErrMsg::UPLOAD_INVALID_FILE_MAZ_SIZE_NO);

        list($width, $height) = getimagesize($this->tmp_file['tmp_name']);

        if($width > $this->max_width)
            throw new Exception(
                str_replace(['%s', '%w'], [$this->tmp_file['name'], $this->max_width], UploadErrMsg::UPLOAD_INVALID_FILE_MAX_WIDTH),
                UploadErrMsg::UPLOAD_INVALID_FILE_MAX_WIDTH_NO);

        if($height > $this->max_height)
            throw new Exception(
                str_replace(['%s', '%h'], [$this->tmp_file['name'], $this->max_height], UploadErrMsg::UPLOAD_INVALID_FILE_MAX_HEIGHT),
                UploadErrMsg::UPLOAD_INVALID_FILE_MAX_WIDTH_NO);

        return true;
    }
}