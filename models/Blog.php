<?php
namespace models;

class Blog extends Base
{
    // 设置这个模型对应的表
    protected $table = 'blog';
    // 设置允许接收的字段
    protected $fillable = ['title','type','introduce','content',"user_id"];
    public function add(){
        $id= $_SESSION['id'];
        $data = [
            ":title"=>$_POST["title"],
            ":type"=>$_POST["type"],
            ":introduce"=>$_POST["introduce"],
            ":content"=>$_POST["content"],
            ":user_id"=>$id   
        ];
        $sql = "INSERT INTO blog SET title = :title,type = :type,introduce = :introduce,content = :content,user_id = :user_id";
        $stmt = $this->_db->prepare($sql);
        $data = $stmt->execute($data);
        if($data){
            return true;
        }else{
            return false;
        }
    }
    public function search()
    {
        // 取出当前用户的日志
        $where = 'user_id='.$_SESSION['id'];
        // 放预处理对应的值
        $value = [];
        
        // 如果有keword 并值不为空时
        if(isset($_GET['keyword']) && $_GET['keyword'])
        {
            $where .= " AND (title LIKE ? OR content LIKE ?)";
            $value[] = "%".$_GET['keyword']."%";
            $value[] = "%".$_GET['keyword']."%";
        }

        if(isset($_GET['start_date']) && $_GET['start_date'])
        {
            $where .= " AND created_at >= ?";
            $value[] = $_GET['start_date'];
        }

        if(isset($_GET['end_date']) && $_GET['end_date'])
        {
            $where .= " AND created_at <= ?";
            $value[] = $_GET['end_date'];
        }

        if(isset($_GET['is_show']) && ($_GET['is_show']==1 || $_GET['is_show']==='0'))
        {
            $where .= " AND is_show = ?";
            $value[] = $_GET['is_show'];
        }
        
        /***************** 排序 ********************/
        // // 默认排序
        $odby = 'created_at';
        $odway = 'asc';

        if(isset($_GET['odby']) && $_GET['odby'] == 'look')
        {
            $odby = 'look';
        }

        if(isset($_GET['odway']) && $_GET['odway'] == 'desc')
        {
            $odway = 'desc';
        }

        /****************** 翻页 ****************/
        $perpage = 3; // 每页15
        // 接收当前页码（大于等于1的整数）， max：最参数中大的值
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        // 计算开始的下标
        // 页码  下标
        // 1 --> 0
        // 2 --> 15
        // 3 --> 30
        // 4 --> 45
        $offset = ($page-1)*$perpage;
        // 制作按钮
        // 取出总的记录数
        $sql = "SELECT COUNT(*) FROM blog WHERE $where";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($value);
        $count = $stmt->fetch(\PDO::FETCH_COLUMN );
        // 计算总的页数（ceil：向上取整（天花板）， floor：向下取整（地板））
        $pageCount = ceil( $count / $perpage );
        $btns = '';
        for($i=1; $i<=$pageCount; $i++)
        {
            // 先获取之前的参数
            $params = getUrlParams(['page']);
            $class = $page==$i ? 'active' : '';
            $btns .= "<a class='$class' href='?{$params}page=$i'> $i </a>";
        }

        /*************** 执行 sqL */
        // 预处理 SQL
        $sql = "SELECT * FROM blog WHERE $where ORDER BY $odby $odway LIMIT $offset,$perpage";
        $stmt = $this->_db->prepare($sql);
        // 执行 SQL
        $stmt->execute($value);

        // 取数据
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($data);
        return [
            'btns' => $btns,
            'data' => $data,
        ];
    }
}