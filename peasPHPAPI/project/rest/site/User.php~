<?php
/**
 * Created with Vim7.3
 * description : 
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月20日 星期四 21时35分27秒
 * @filename : user.php
 * @version : 
 * @package : 
 */
namespace rest\site;

class User extends \BaseApi {
      

     public function rest($arr) {

           $result = array();

           switch ($_SERVER['REQUEST_METHOD']) { 
               case 'GET' :
                   $result['type'] = 'GET --> read';
                   $result['success'] = true;
                   $result['data'] = $arr;
               break;
               case 'POST':
                   $result['type'] = 'POST --> save';
                   $result['success'] = true;
                   $result['data'] = $arr;
               break;
               case 'PUT' :
                   $result['type'] = 'PUT --> update';
                   $result['success'] = true;
                   $result['data'] = $arr;
               break;
               case 'DELETE':
                   $result['type'] = 'DELETE --> delete';
                   $result['success'] = true;
                   $result['data'] = $arr;
               break;
           }
           return $result;
     }
}












?>
