<?php
/**
 * Created with Vim7.3
 * description : qit相关的数据 
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年06月05日 星期三 23时09分55秒
 * @filename : project/icom/qti/qti.php
 * @version : v1.0
 * @package : peasPHPAPI
 */

/*
 * id   age   name
 *  8    27   cwh
 *  9    27   cwh1
 *  
 *  http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/getQti&info={"a":1,"b":2,"c":3,"d":4,"e":5}
 *  http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/testDB&info={"name":"cwh"}
 *  http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/testupdatedb&info={"age":20,"id":8}
 *  http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/testpredb&info={"name":"cwh"}
 *  http://127.0.0.1/peasPHPAPI/index.php?r=json/icom.qti/qti/testpreupdatedb&info={"age":21,"id":8}
 *
 */
namespace icom\qti;

class Qti extends \BaseApi{

      public function getQti($arr) {
         return $arr;
      }

      public function testDB($arr) {
         //sql一般查询
	     $name = $arr['name'];
	     //手工字符串转义 1：防止sql注入安全，2：转义后不需要加引号，3：非常规符号能自动转义处理
	     $name = $this->DB->quotesql($name);
	     $sql="SELECT * FROM test WHERE name=".$name;
         $result = $this->DB->query($sql,'All');
         return $result;
      }
      
      public function testupdatedb($arr) {
         //常规的插入，更新，删除
         $age = $arr['age'];
         $id   = $arr['id'];
         //转义
         $name = $this->DB->quotesql($name);
         $id =intval($id);		
         $sql="UPDATE test SET age= $age WHERE id = $id ";
         $result = $this->DB->exec($sql);
         return array('update'=>$result);		
	  }

      public function testpredb($arr) {
	     //sql预编译查询
		 $presql="SELECT * FROM test WHERE name=:name";
		 $sqlArr=array(':name'=>$arr['name']);
         $result = $this->DB->prepare($presql,$sqlArr,true,'All');
		 return $result;		
      }

	  public function testpreupdatedb($arr) {
	     //sql预编译更新
		 $presql="UPDATE test SET age=:age WHERE id=:id";
		 $sqlArr=array(':age'=>$arr['age'],':id'=>$arr['id']);
         $result = $this->DB->prepare($presql,$sqlArr);
         var_dump($result);//无论是否执行都返回真
         return array('update'=>$result);		
	  }
}
?>
