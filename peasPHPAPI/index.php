<?php
/**
 * Created with Vim7.3 Sys ubuntu10.04
 * description : 轻便，简单，易懂 peasPHPAPI 框架 
 * 目前该API支持：
 *  
 *   跨域GET请求 JSONP ok 
 *   REST 方式请求  ok
 *   非跨域常规请求  ok
 *   验证请求，可以路由类Router的accessRBAC中接入第三方验证,也可以自己实行基于RBAC的用户机制
 *
 *   提供一个请求验证机制,适合请求一次有效，忽略相同1次以上的请求 :
 *       为第三方提供一个帐号和私钥【私钥也可以变相是用户的密码】  每次请求传递一个参数作为验证 md5（每次请求的url+请求类型【post,get等】+私钥） url理论是唯一,当然也可以多次md5,或者md5(sha1(data))等
 *   peasPHPAPI 做下相应的调整，每次验证，这个值与自己构建的是不是一样的。
 *   当然，这种验证，只能验证请求合法，但别人可以复制重复发请求，而造成麻烦.
 *
 *   只有接入网站的用户权限，验证网站的用户才能使用,这样才能满足需求.
 *
 *   配置文件里，可以配置那些目录需要权限验证，待实现.  
 *   todo : 缓存 
 * 结构：
 *   peasPHPAPI
 *   -index.php
 *   -config.php
 *   +lib
 *   +project  [PHP API 支持目录包文件夹,命名空间]
 *    -按照模块功能自己建立文件夹
 *    -
 *    -
 * 返回数据：
 *    json 与 xml 两种方式 amf还没有完善
 *
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年05月30日 星期四 21时59分36秒
 * @filename : index.php
 * @version : v 1.0 
 * @package : peasPHPAPI 
 */

/*
  测试链接
   http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/getQti&info={"a":1,"b":2,"c":3,"d":4,"e":5}
   http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testDB&info={"name":"cwh"}
   http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testupdatedb&info={"age":20,"id":8}
   http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testpredb&info={"name":"cwh"}
   http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testpreupdatedb&info={"age":21,"id":8}
   http://127.0.0.1/peasPHPAPI/index.php?r=json/rest.site/User/rest&info={"id":8}
 */


 /**
  * 设置运行环境参数
  * 编码，时区，错误报告 设置
  */
header('Content-type:text/html;charset = utf-8');
date_default_timezone_set('Asia/Shanghai');  
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * 常量设置
 * 根据域名自动识别生产与开发环境，确保手动更新的问题
 */
define('production','www.peas.com');
define('development','www.dev.peas.com');
define('LIB_PATH',realpath(dirname(__FILE__)) . '/lib/');
define('PROJECT_PATH',realpath(dirname(__FILE__)) . '/project/');

/**
 * 引入配置文件
 */
require_once 'config.php';

/**
 * 引入异常处理与自动载入类
 */
require_once LIB_PATH . '/FrameException.class.php';
require_once LIB_PATH . '/AutoLoad.class.php';

/**
 *  统一管理框架级和代码级异常处理
 */
try { 

    /**
     * 启动自动加载类
     */
    AutoLoad::registerDir($autoDirConfig);

    /**
     * 启动run
     */
    $app = App::getInstance();
    $app->run();
    //throw new APIException('msg',509);
} catch (FrameException $e) {
    echo "<pre>";
    var_dump($e->errorMsg());
    echo "</pre>";  
} catch (Exception $e) {
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}
?>
