<?PHP
/**
 * 系统配置文件
 *
 * @decription 系统各模块，组件，数据访问等配置
 * @author Chen weihan <csq-3@163.com>
 * @copyright v1.0
 * @version v1.0
 * @package config
 */

/**
 * 非单一入口页面，可以判断该常量，禁止非法直接调用
 */
define('PEASPHPAPI',true);

/**
 * 映射目录路径
 */
set_include_path(implode(PATH_SEPARATOR,array(
    LIB_PATH
  )
));

/**
 * 配置允许类自动加载目录
 */
$autoDirConfig = array (
    LIB_PATH,
);

/**********************************development**************************/
/**
 * 异常捕获开启
 */
define('DEBUG',true);

/**
 * 是否开启日志记录
 */
define('LOG',true);

/**
 * 日志记录路径 注意该目录需要读写权限
 */
define('LOGDIR',LIB_PATH.'log/');

/*数据库配置*/
$Dbconfig = array(
     "dbHost"=>"127.0.0.1",
     "dbUser"=>"root",
     "dbPassword"=>"123",
     "dbName"=>"test"
);
/**********************************production***************************/
?>
