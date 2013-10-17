<?php
/**
 * Created with Vim7.3
 * description :  PDO mysql数据库
 *                如果出现  1. 首先判断数据是否存在； 2. 如果不存在，则插入；3.如果存在，则更新。 
 *                可以使用 REPLACE INTO test(id,NAME) VALUES (10,'dsa')
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月08日 星期六 23时04分29秒
 * @filename : lib/DBmysql.class.php
 * @version :  v1.0
 * @package : peasPHPAPI
 */
 
class DBmysql
{
    private   $dbName = '';
    private   $dsn;
    private   $dbh;
	private static $_instance;
    
    private function __construct(){}

    /**
	 * 使用单例模式，避免多次实例
	 */
    public static function getInstance() {
	   	if(!(self::$_instance instanceof self)) {            
	   	  self::$_instance = new self();       
	   	}
	   	return self::$_instance;
    }

    /**
     * 单例避免被复制，保持类单一职责
     */
    private function __clone(){} 
    
    /**
     * Connect 连接
     */
    public function connect($Dbconfig) {
        try {
            $this->dsn = 'mysql:host='.$Dbconfig['dbHost'].';dbname='.$Dbconfig['dbName'];
            $this->dbh = new PDO($this->dsn, $Dbconfig['dbUser'], $Dbconfig['dbPassword']);
        }  catch (PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }
    
    /**
     * query 查询
     */
    public function query($strSql,$queryMode = 'All') {
        $rs = $this->dbh->query($strSql);
        $rs->setFetchMode(PDO::FETCH_ASSOC);

        if($queryMode == 'All') {
            $result = $rs->fetchAll();
        } 
		elseif($queryMode == 'Row') {
            $result = $rs->fetch();
        }

        return $result;
    }
    
	 /**
      * exec非查询执行
      */
    public function exec($strSql) {
        $result = $this->dbh->exec($strSql);
		return $result;
    }
    
    
    /**
	 *  prepare编译模板  查询需要返回，而更新等不需要的，$isBack=false
     */
	public function prepare($prepareSql,$array,$isBack=false,$queryMode="All") {
		
		$stpl   = $this->dbh->prepare($prepareSql);
        $result = $stpl ->execute($array);

        if($isBack) {
			$stpl->setFetchMode(PDO::FETCH_ASSOC);
			if($queryMode == 'All') {
				$result = $stpl->fetchAll();
			} elseif($queryMode == 'Row') {
				$result = $stpl->fetch();
			}
        }
        
		return $result;
	}
   	
    /**
	 *  手动转义 【预编译情况不需要】
     */
	public function quotesql($string) {
        $string=$this->dbh->quote($string);
	    return $string;
    }	

	/**
     * 输出错误信息
     */
    private function outputError($strErrMsg) {
        exit('MySQL Error: '.$strErrMsg);
    }
}
?>
