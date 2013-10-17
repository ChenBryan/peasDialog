<?php
/**
 * Created with Vim7.3
 * @decription 系统启动App类，主要引入相关配置文件，加载基础类，框架资源管理控制
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年05月30日 星期四 22时30分17秒
 * @filename : /usr/local/nginx/html/peasPHPAPI/lib/App.class.php
 * @version : v1.0
 * @package : peasPHPAPI 
 * @example $app = App::getInstance; $app->run();
 */

/**
 * App类 单例模式
 *
 */
class App {
     
     /**
      * 单例私有变量
      * @var obj
      * @access private
      * @static
      */
     private static $_instance;

     /**
      * 私有构造函数
      */
     private function __construct(){}

     /**
      * 单例App 避免多次实例
      * @return obj App实例
      */  
     public static function getInstance() {
          if (!(self::$_instance instanceof self)) {
              self::$_instance = new self();
          }
          return self::$_instance;
      }

     /**
      * 单例避免克隆，保持类单一职责原则
      */    
     private function __clone(){}

     /**
      * 框架启动 run方法
      * 
      * @decription run启动
      * @example $app->run();
      * 
      */
     public function run() {
        $this->handleRequest();
     }

     /**
      * 命令处理
      */
     private function handleRequest() {
        $router  = new Router();	
     }
}

?>
