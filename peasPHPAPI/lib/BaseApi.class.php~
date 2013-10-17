<?php
/**
 * Created with Vim7.3
 * description : api请求模板类  
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月07日 星期五 23时35分42秒
 * @filename : lib/pdo.class.php
 * @version : 
 * @package : 
 */

class BaseApi {
    
       protected $DB;
       //构造函数实例db类
       final public function __construct() {               
           //声明使用的全局变量
           global $Dbconfig;
           //单例实例数据库
           $this->DB = DBmysql::getInstance();
           $this->DB->connect($Dbconfig);
       }      
}
?>
