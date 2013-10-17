<?php
/**
 * Created with Vim7.3
 * description : 路由 工厂模型，命令模式
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月3日 星期一 22时03分14秒
 * @filename : /usr/local/nginx/html/peasPHPAPI/lib/Router.class.php
 * @version : v1.0
 * @package : 路由
 */

/**
 * Router 路由解析类
 * 
 * eg: http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/getQti&info={}
 *     返回格式/目录包[第一级代表是常规还是rest方式,.代表目录层级关系]/页面名称[页面名称与类名一致]/函数名
       上面的url命名空间默认为icom\qit[目录虽然很长，但从命名空间看的出类路径],如果没有写,直接查找全局类
       不同的目录，引入的php可能名称相同，类名相同，但唯一不同的就是目录的路径，所有命名空间必须是唯一也就是路径,而且必须要写命名空间

       http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/getQti&info={"a":1,"b":2,"c":3,"d":4,"e":5}
       http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testDB&info={"name":"cwh"}
       http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testupdatedb&info={"age":20,"id":8}
       http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testpredb&info={"name":"cwh"}
       http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/Qti/testpreupdatedb&info={"age":21,"id":8} 
       http://127.0.0.1/index.php?r=json/icom.qti/qti/getQti&info={}&callback=? [GET jsonp]

       rest方式 
       http://127.0.0.1/peasPHPAPI/index.php?r=json/rest.site/User/rest&info={"id":8}  [GET PUT DELETE]
       http://127.0.0.1/peasPHPAPI/index.php?r=json/rest.site/User/rest&
       post:info={"id":8}                                                                   [POST]
 */

class Router {
    
    /**
     * 请求
     */
    private $requestArr = array();  
    
    /**
     * 参数
     */
    private $paramArr = array();    

    /**
     * 构造函数 默认启动
     */
    public function __construct() {
        $this->getRequest();
        //访问权限
        if ($this->accessRBAC()) {
             $this->getParam();
             $this->dispatcher();
        }
        //var_dump($this->requestArr); 
        //var_dump($this->paramArr);
        //exit();       
    } 
    
    /**
     * 获取请求分发数据[apache 才支持$_SERVER['REQUEST_URI']]
     */
    private function getRequest() {		
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {     
             //获取POST url    
             if (isset($_SERVER['REQUEST_URI'])) {                 
                  $uri = $_SERVER['REQUEST_URI'];
             } else {
                  if (isset($_SERVER['argv'])) {
                     $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
                  } else {
                     $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
                  }
             }
             //分解url参数 
             if (false === strpos($uri,'?')) {                              
                 throw new FrameException('the request format is error',400);exit();
             } else {
                 $uriArr = explode('?',$uri);
                 if ( count($uriArr) == 2 ) {      
                      //$this->requestArr = explode('/',array_pop($uriArr));
                      $this->requestArr = explode('/',$_GET['r']);
                 } else {
                      throw new FrameException('the request format is error',400);exit();
                 }
            }
       } else if ($_SERVER['REQUEST_METHOD']== 'GET' || $_SERVER['REQUEST_METHOD']== 'PUT' ||  $_SERVER['REQUEST_METHOD']== 'DELETE') {
           if (isset($_GET['r'])) {       
		         $this->requestArr = explode('/',$_GET['r']);
	       } else {                  
                 throw new FrameException('the request format is error',400);exit();
	       }
       }  
    }
    
    /**
     * 获取请求参数 info
     */
    private function getParam() {
       switch ($_SERVER['REQUEST_METHOD']) { 
           case 'GET' :
               $this->paramArr = json_decode($_GET['info'],true);
           break;
           case 'POST':
               $this->paramArr = json_decode($_POST['info'],true);
           break;
           //如果是rest,应该是具体函数里面去区分rest来执行
           case 'PUT' :
               $this->paramArr = json_decode($_GET['info'],true);
           break;
           case 'DELETE':
               $this->paramArr = json_decode($_GET['info'],true);
           break;
           default:
               throw new FrameException(" Don't support request type ",500);
               exit();
       }
    }
     
    /**
     *  系统访问权限控制 基于RBAC, 具体验证，调用自己的主网站提供的接口验证。
     */    
    private function accessRBAC() {
        return true;    
    } 
     
    /**
     * 分发请求
     * 如果类使用了命名空间，则命名空间 文件名称 类名一致
     *
     * 目录包,类命名空间，类常规，函数常规，函数命名空间均可调用
     */
    private function dispatcher() {
        //固定四级结构格式，主要是约束程序员API分类
        if (count($this->requestArr) == 4) {
               //引入调用文件
               $nameSpace = $this->parseFileRequire();
               //返回执行结果
               $arr = $this->executeFunc($nameSpace);
               //调用返回类型
               if (is_array($arr)) {
                   //类 函数名 参数
                   call_user_func(array(new Facade(), $this->requestArr[0]),$arr);
               } else {
                   throw new FrameException('the return is not array',400);exit();
               }    
               exit();
        } else {
               throw new FrameException('the request format is error',400);exit();
        }
    }

    /**
     * 解析结构，引入文件  
     */
    private function parseFileRequire () {
        $dirArr = explode('.',$this->requestArr[1]);
        $fileName = $this->requestArr[2];
        $nameSpace = '';
        $dirPath = PROJECT_PATH;
        for($i = 0,$len = count($dirArr); $i < $len ; $i++) {
              if(is_dir($dirPath.$dirArr[$i])) {
                  $dirPath = $dirPath.$dirArr[$i].'/';
                  $nameSpace = $nameSpace.'\\'.$dirArr[$i];
              } else {
                 throw new FrameException('the dir not find',400);exit();
              }
        } 
        //echo $dirPath;
        if(is_file($dirPath.$this->requestArr[2].'.php')) { 
              require_once $dirPath.$this->requestArr[2].'.php'; 
              return $nameSpace;
        } else {
              throw new FrameException('the file not find',400);exit();
        }
    }

    /**
     * 执行引入类方法
     */ 
     private function executeFunc($nameSpace) {
         $class =  $nameSpace.'\\'.$this->requestArr[2];
         //类名没有区别大小写的,但建议使用驼峰命名法，类的首字母也要大写
         if (class_exists($class)) {
             //$class = new $nameSpace.'\\'.$this->requestArr[2];
             $classObj = new ReflectionClass($class);              
             if($classObj->hasMethod($this->requestArr[3])) {                 
                $reflectionMethod = new ReflectionMethod($class,$this->requestArr[3]);        
                $arr = $reflectionMethod->invokeArgs($classObj->newInstance(),array($this->paramArr));
                return $arr;
             } else {
                throw new FrameException('the function not found',400);exit();
             }
         } else {
             throw new FrameException('the class not found',400);exit();
         }
     }
}
?>
