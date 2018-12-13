<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2018/12/4
 * Time: 10:24
 */

class CsvReader
{
    private $csv_file;
    private $spl_object = null;
    private $error;

    /**
     * 初始化
     * CsvReader constructor.
     * @param string $csv_file
     */
    public function __construct($csv_file = '') {
        if($csv_file && file_exists($csv_file)) {
            $this->csv_file = $csv_file;
        }
    }

    /**
     * 设置文件路径
     * @param $csv_file
     * @return bool
     */
    public function set_csv_file($csv_file) {
        if(!$csv_file || !file_exists($csv_file)) {
            $this->error = 'File invalid';
            return false;
        }
        $this->csv_file = $csv_file;
        $this->spl_object = null;
    }

    /**
     * 获取文件路径
     * @return string
     */
    public function get_csv_file() {
        return $this->csv_file;
    }

    /**
     * 检查文件
     * @param string $file
     * @return bool
     */
    private function _file_valid($file = '') {
        $file = $file ? $file : $this->csv_file;
        if(!$file || !file_exists($file)) {
            return false;
        }
        if(!is_readable($file)) {
            return false;
        }
        return true;
    }

    /**
     * 打开文件
     * @return bool
     */
    private function _open_file() {
        if(!$this->_file_valid()) {
            $this->error = 'File invalid';
            return false;
        }
        if($this->spl_object == null) {
            $this->spl_object = new SplFileObject($this->csv_file, 'rb');
        }
        return true;
    }

    /**
     * 获取文件内容, 返回二维数组
     * @param int $length
     * @param int $start
     * @return array|bool
     */
    public function get_data($length = 0, $start = 0) {
        if(!$this->_open_file()) {
            return false;
        }
        $length = $length ? $length : $this->get_lines();
        $start = $start - 1;
        $start = ($start < 0) ? 0 : $start;
        $data = array();
        $this->spl_object->seek($start);
        while ($length-- && !$this->spl_object->eof()) {
            // fgetcsv() 获取当前行的内容到数组中
            $data[] = $this->spl_object->fgetcsv();
            $this->spl_object->next();
        }
        return $data;
    }

    /**
     * 获取文件内容, 返回一维数组
     * @param int $length
     * @param int $start
     * @return array|bool
     */
    public function get_data_simple($length = 0, $start = 0) {
        if(!$this->_open_file()) {
            return false;
        }
        $length = $length ? $length : $this->get_lines();
        $start = $start - 1;
        $start = ($start < 0) ? 0 : $start;
        $data = array();
        $this->spl_object->seek($start);
        while ($length-- && !$this->spl_object->eof()) {
            // 这里使用fgetcsv(), 而不是使用fgets(),或current(), 因为返回的字符串要过滤\r\n这些字符
            $data = array_merge($data, $this->spl_object->fgetcsv());
            $this->spl_object->next();
        }
        return $data;
    }

    /**
     * 获取文件总行数
     * @return bool
     */
    public function get_lines() {
        /*
         * 会存在一个bug，因为一般文件最后一行内容结尾不存在换行符的话，
         * 结果就会小于1
         */
        if(!$this->_open_file()) {
            return false;
        }
        $this->spl_object->seek(filesize($this->csv_file));
        // 临时解决方案
        return $this->spl_object->key() + 1;
    }

    /**
     * 高效获取文件总行数
     * @param float|int $size 每次读取大小，单位为字节，测试发现每次读0.5M耗时最低
     * @param int $sum 计数初始值
     * @return bool|int
     */
    public function lines($size = 1024*1024*0.5, $sum = 0)
    {
        /*
         * 会存在一个bug，因为一般文件最后一行内容结尾不存在换行符的话，
         * 结果就会小于1
         */
        if(!$this->_open_file()) {
            return false;
        }

        while($this->spl_object->valid()) {
            $data = $this->spl_object->fread($size);
            $num = substr_count($data,"\n"); //计算换行符出现的次数
            $sum += $num;
        }

        return $sum + 1;
    }

    /**
     * 关闭文件
     * @return bool
     */
    public function close_file() {
        if (!is_null($this->spl_object)) {
            $this->spl_object = null;
        }

        return true;
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function get_error() {
        return $this->error;
    }

    /** 返回文件从X行到Y行的内容(支持php5、php4)
     * @param string $filename 文件名
     * @param int $startLine 开始的行数
     * @param int $endLine 结束的行数
     * @param string $method
     * @return array|string
     */
    public function getFileLines($filename, $startLine = 1, $endLine = 50, $method = 'rb') {
        $content = array();
        $count = $endLine - $startLine;
        // 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
        if(version_compare(PHP_VERSION, '5.1.0', '>=')) {
            $fp = new SplFileObject($filename, $method);
            $fp->seek($startLine-1);// 转到第N行, seek方法参数从0开始计数
            for($i = 0; $i <= $count; ++$i) {
                $content[] = $fp->current();// current()获取当前行内容
                $fp->next();// 下一行
            }
            $fp = null;
        } else {//PHP<5.1
            $fp = fopen($filename, $method);
            if(!$fp) return 'error:can not read file';
            for ($i=1; $i < $startLine; ++$i) {// 跳过前$startLine行
                fgets($fp);
            }

            for($i = $startLine;$i <= $endLine; ++$i){
                $content[]=fgets($fp);// 读取文件行内容
            }
            fclose($fp);
        }
        return array_filter($content); // array_filter过滤：false,null,''

        /*Ps: 上面都没加”读取到末尾的判断”：!$fp->eof() 或者 !feof($fp)，加上这个判断影响效率，自己加上测试很多很多很多行
        的运行时间就晓得了，而且这里加上也完全没必要。从上面的函数就可以看出来使用SplFileObject比下面的fgets要快多了，
        特别是文件行数非常多、并且要取后面的内容的时候。fgets要两个循环才可以，并且要循环$endLine次。*/
    }
}
