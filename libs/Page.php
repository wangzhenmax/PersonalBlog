<?php
namespace libs;
class Page{
    private static  $obj =null;
    private function __clone(){}
    private function __construct(){
        if(self::$ojb==null){
            return self::$obj = new self;
        }
        return self::$pdo;
    }

    private $_p;
    private $_pages;
    private $_limit;
    public static function make($count,$pageSize){
        $this->_p = isset($_GET['p']) && $_GET['p']>0 ? $_GET['p'] : 1 ;
        $this->_limit = $limtStr = " limit ".($this->_p-1)*$pageSize." , ".$pageSize." ";
        $this->_pages = $count;
        $this->_pageSize = $pageSize;
        $this->pageStr($this->_pages,$this->_p);
        $arr=[];
        $arr = [
            'limit'=>$this->_limit,
            'btns'=>$this->pageStr
        ];
        return $arr;
    }
    private function pageStr($pages,$p){
        $pageStr = "<div class='pagelist'>";
			if($pages>0){
				$old = "";
				$inputStr = "";
				unset($_GET['p']);
				if(count($_GET)){
  					foreach ($_GET as $key => $value) {
						$old .= $key."=".$value."&";
						$inputStr .="<input type='hidden' name='{$key}' value='{$value}'>";
					}
				}
				if($p!=1){
					$pageStr .="<a href='?{$old}p=1'>首页</a>";
					$pageStr .="<a href='?{$old}p=".($p-1)."'>上一页</a>";
				}
				$start = 1;
				$end = $pages;
				if($p<=5){
					$start = 1;
					$end = min(7,$pages);
				}else if ($p>=6 && $p<$pages-3) {   //47
					$start = $p-3;
                    $end = $p+2;
                    $this->str($old);
				}else {
					$start = $p-3;
					$end = $pages;
					$this->str($old);
				}
				$this->strs($start,$end,$old,$this->_p);
				if($end < $pages){
					$pageStr .="<a >...</a>";
				}
				if($p<$pages){
					$pageStr .="<a href='?{$old}p=".($p+1)."'>下一页</a>";
					$pageStr .="<a href='?{$old}p=".$pages."'>末页</a>";
				}
				$pageStr .= "<span>共<b>{$pages}</b>页到第</span>";
				$pageStr .= "<form action='' style='display:inline-block;'><input type='text' class='txt' name='p' value='{$p}'>";
				$pageStr .= "<span>页</span>";
				$pageStr .= $inputStr;
				$pageStr .= "<input type='submit' value='确定' class='btn'></form>";
			}else {
				$pageStr .= "暂无数据...";
			}
            $pageStr .= "</div>";
            return $pageStr;
    }

    private function str($old){
                    $pageStr .="<a href='?{$old}p=1'>1</a>";
					$pageStr .="<a href='?{$old}p=1'>2</a>";
                    $pageStr .="<a >...</a>";
                    return $pageStr;
    }

    private function strs($start,$end,$old){
        for($i=$start;$i<=$end;$i++){
					$str = "";
					if($i==$p){
						$str = "class = 'active'";
					}
					$pageStr .="<a href='?{$old}p={$i}' {$str} >{$i}</a>";
            }
            return $pageStr;
    }
}
    

