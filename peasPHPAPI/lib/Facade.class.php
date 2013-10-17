<?php
/**
 * Created with Vim7.3
 * description : 简化外观模式  不同输出应该写成类的形式，便于扩展返回不同的数据结构 
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月04日 星期二 23时04分01秒
 * @filename : peasPHPAPI/lib/Facade.class.php
 * @version : v 1.0 
 * @package : peasPHPAPI
 */

class Facade {
    
    public function __construct() {}
    
    public function xml($arr) {
        $xml = Array2XML::createXML('api', $arr);
        if (isset($_GET["callback"])) {
           $callback = isset($_GET["callback"]) ? $_GET['callback'] : "callback"; 
           echo $callback . "(". $xml->saveXML . ")";
        } else {
           echo $xml->saveXML();
        }
        exit();    
    }

    public function json($arr) {
        $output = json_encode($arr);
        if (isset($_GET["callback"])) {
           $callback = isset($_GET["callback"]) ? $_GET['callback'] : "callback"; 
           echo $callback . "(". $output . ")";
        } else {
           echo $output;
        }    
        exit();
    }

    public function amf($arr) {
        echo 'amf'; 
    }

    public function jsonJsonp($arr) {
        echo $callback . "(". json_encode($arr). ")";
        exit();
    }

    public function xmlJsonp($arr) {
        $xml = Array2XML::createXML('api', $arr);
        echo $callback . "(". $xml->saveXML . ")";
        exit();
    }

    public function amfJsonp($arr) {
        echo 'amfJsonp'; 
    }

}
?>
