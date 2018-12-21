<?php
/**
 * User: chinwe
 * Date: 14-8-6
 * Time: 上午10:23
 */

namespace App\Model;

use App\Model\Database\Config;
use \PDO;

class BaseModel
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var PDO
     */
    public $connection;

    protected $dbnamePrefix;
    protected $tablePrefix;

    /**
     * @var string 分库后对应的库
     */
    protected $dbname;

    /**
     * @var string 分库分表后对应的表
     */
    protected $table;

    public function __construct($id)
    {
        $this->config = new Config($this->dbnamePrefix, $this->tablePrefix, $id);
        $this->connection = new Pdo($this->config->dsn, $this->config->user, $this->config->password);
        $this->connection->exec("set names utf8");
        $this->dbname = $this->config->dbname;
        $this->table = $this->config->table;
    }

    public function update(array $data, array $where = array())
    {

    }

    public function select(array $where)
    {
        $sqlWhere='';
        if(!empty($condition)){
            foreach ($condition as $field => $value) {
                $where[] = '`'.$field.'`='."'".addslashes($value)."'";
            }
            $sqlWhere .= ' '.implode(' and ', $where);
        }
        $sql="select * from {$this->dbname}.{$this->table}";
        if($sqlWhere){
            $sql.=" where $sqlWhere";
        }
        $res = $this->query($sql);
        $data['data'] = $res->fetchAll(PDO::FETCH_ASSOC);
        $data['info'] = array("dsn"=>$this->config->dsn,"dbname"=>$this->dbname,"table"=>$this->table,"sql"=>$sql);
        return $data;
    }

    public function insert(array $arrData)
    {
        $name = $values = '';
        $flag = $flagV = 1;
        $true = is_array( current($arrData) );//判断是否一次插入多条数据
        if($true) {
            //构建插入多条数据的sql语句
            foreach($arrData as $arr) {
                $values .= $flag ? '(' : ',(';
                foreach($arr as $key => $value) {
                    if($flagV) {
                        if($flag) $name .= "$key";
                        $values .= "'$value'";
                        $flagV = 0;
                    } else {
                        if($flag) $name .= ",$key";
                        $values .= ",'$value'";
                    }
                }
                $values .= ') ';
                $flag = 0;
                $flagV = 1;
            }
        } else {
            //构建插入单条数据的sql语句
            foreach($arrData as $key => $value) {
                if($flagV) {
                    $name = "$key";
                    $values = "('$value'";
                    $flagV = 0;
                } else {
                    $name .= ",$key";
                    $values .= ",'$value'";
                }
            }

            $values .= ") ";
        }

        $sql = "insert into ".$this->dbname.'.'.$this->table." {$name} values {$values}";
        if( ($rs = $this->connection->exec($sql) ) > 0 ) {
            return array("dsn"=>$this->config->dsn,"dbname"=>$this->dbname,"table"=>$this->table,"sql"=>$sql);
        }
        return false;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }
}
