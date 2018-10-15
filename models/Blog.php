<?php
namespace models;

class Blog extends Base
{
    // 设置这个模型对应的表
    protected $table = 'blog';
    // 设置允许接收的字段
    protected $fillable = ['title','type','introduce','content',"user_id","cat_1","cat_2","cat_3","is_show"];
    public function getType(){
        $stmt  = $this->_db->prepare("SELECT * from type where parent_id = 1 ");
        $stmt->execute();
        $type  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $type;
    }

    public function updates(){
        $id = $_GET['id'];
        $data = [
           $_POST["title"],
           $_POST['introduce'],
           $_POST['content'],
           $_POST['cat_1'],
            $_POST['cat_2'],
           $_POST['cat_3'],
           $_POST['is_show'],
           $id   
        ];
        $sql = "UPDATE blog SET title =? ,introduce = ?,content = ?,cat_1 = ?,cat_2 = ?,cat_3 = ?,is_show = ? WHERE  id = ?";
        $stmt = $this->_db->prepare($sql);
        $arr = $stmt->execute($data);
        echo $sql;
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
        $odway = 'desc';

        if(isset($_GET['odby']) && $_GET['odby'] == 'look')
        {
            $odby = 'look';
        }

        if(isset($_GET['odway']) && $_GET['odway'] == 'asc')
        {
            $odway = 'asc';
        }

        /****************** 翻页 ****************/
        $perpage = 3; // 每页15
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page-1)*$perpage;
        $sql = "SELECT COUNT(*) FROM blog WHERE $where";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($value);
        $count = $stmt->fetch(\PDO::FETCH_COLUMN );
        $pageCount = ceil( $count / $perpage );
        $btns = '';
        for($i=1; $i<=$pageCount; $i++)
        {
            $params = getUrlParams(['page']);
            $class = $page==$i ? 'active' : '';
            $btns .= "<a class='$class' href='?{$params}page=$i'> $i </a>";
        }
         $leftJoin = "  blog inner join type as type1 inner join type as type2 inner join type as type3 on type1.id = blog.cat_1 and type2.id = blog.cat_2 and type3.id = blog.cat_3 ";
        $sql = "SELECT blog.* , type1.name as cat1, type2.name as cat2 , type3.name as cat3  FROM $leftJoin   WHERE $where group by blog.id  ORDER BY $odby $odway LIMIT $offset,$perpage";
        $stmt = $this->_db->prepare($sql);
        // 执行 SQL
        $stmt->execute($value);
        // 取数据
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return [
            'btns' => $btns,
            'data' => $data,
        ];
    }
}