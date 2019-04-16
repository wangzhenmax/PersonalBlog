<?php
namespace models;
// use Spatie\Image\Image;
use Intervention\Image\ImageManagerStatic as Image;
class Blog extends Base
{
    // 设置这个模型对应的表
    protected $table = 'blog';
    // 设置允许接收的字段
    protected $fillable = ['title','type','introduce','content',"user_id","cat_1","cat_2","cat_3","is_show","banner","recommend"];

    // 创建文章
    public function blogInsert($id){
    $stmt= new \PDO("mysql:host=118.89.221.52;dbname=blog",'root','123456');
    //, $_POST['cat_1'],$_POST['cat_2'],$_POST['cat_3'],$_POST['introduce'],$_POST['is_show'],$_POST['banner'],$_POST['recommend'], $_POST['content']
    $values[] = $id;
    $title = $_POST['title'];
    $cat_1 = $_POST['cat_1'];
    $cat_2 = $_POST['cat_2'];
    $cat_3 = $_POST['cat_3'];
    $introduce = $_POST['introduce'];
    $is_show = $_POST['is_show'];
    $banner = $_POST['banner'];
    $recommend = $_POST['recommend'];
    $content = $_POST['content'];
    $sql = "insert INTO {$this->table} (user_id,title,cat_1,cat_2,cat_3,introduce, is_show,banner,recommend,content) values(
        $id,
        '{$title}',
        $cat_1,
        $cat_2,
        $cat_3,
        '{$introduce}',
        $is_show,
        $banner,
        $recommend,
        '{$content}'
        )";
    $data = $stmt->exec($sql);
    echo $sql;
    var_dump($data);die;
    }
    // 插入 / 修改前 
    public function _before_write(){
        $uploader = \libs\Uploader::make();
        if(count($_FILES['covers']['name'])>1){
            $tmp = [];
            // 多张图片的合集
            $paths = [];
                foreach($_FILES['covers']['name'] as $k => $v){
                if($_FILES['covers']['error'][$k]==0){
                    // $this->_delete_img();
                    $tmp['name'] = $v;
                    $tmp['type'] = $_FILES['covers']['type'][$k];
                    $tmp['tmp_name'] = $_FILES['covers']['tmp_name'][$k];
                    $tmp['error'] = $_FILES['covers']['error'][$k];
                    $tmp['size'] = $_FILES['covers']['size'][$k];
                    $_FILES['tmp'] = $tmp;
                    $path = $uploader->upload('tmp','covers/cover_sm');
                    $paths[] = "/uploads/".$path;
                    $path = ROOT.'public/uploads/'.$path;
                    // $this->covers_md($path);
                }
            }
            $data = json_encode($paths);
            $this->data['cover_md'] = $data;
            }
            else{
                    foreach($_FILES['covers']['name'] as $k => $v){
                        if($_FILES['covers']['error'][$k]==0){
                            // $this->_delete_img();
                            $tmp['name'] = $v;
                            $tmp['type'] = $_FILES['covers']['type'][$k];
                            $tmp['tmp_name'] = $_FILES['covers']['tmp_name'][$k];
                            $tmp['error'] = $_FILES['covers']['error'][$k];
                            $tmp['size'] = $_FILES['covers']['size'][$k];
                            $_FILES['tmp'] = $tmp;
                            $path = $uploader->upload('tmp','covers/cover_big');
                            // $this->covers_big(ROOT.'public/uploads/'.$path);
                            $this->data['cover_big'] = "/uploads/".$path;
                        }
                    }
        }
    }


        // 插入 / 修改后
    public function _after_write(){
        $client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '118.89.221.52',
            'port'   => 6379,
        ]);
        if($this->data['cover_big']){
            $data = [
            'cover_big' =>$this->data['cover_big'],
            'id' => $this->data['id'],
            ];
             $client->lpush('jxshop:niqui', serialize($data));
        }
        if($this->data['cover_md']){
             $data = [
            'cover_md' => $this->data['cover_md'],
            'id' => $this->data['id'],
            ];
             $client->lpush('jxshop:niqui', serialize($data));
        }
    }




      // 删除原图片
    public function _delete_img(){
        if(isset($_GET['id'])){
            // 批量删除 
            $id =$_GET['id'];
            $data =  $this->findOne($id);
            if($data['cover_big']){
                @unlink(ROOT."public".$data['cover_big']);
                $stmt = $this->_db->prepare("UPDATE blog set cover_big = '' where id = ?");
                $stmt->execute([$id]);
            }else if($data['cover_md']){
                $data = json_decode($data['cover_md']);
                foreach($data as $v){
                    @unlink(ROOT."public".$v);
                    $stmt = $this->_db->prepare("UPDATE blog set cover_md = '' where id = ?");
                    $stmt->execute([$id]);
                }
            }
            $this->rm_empty_dir();
        }
    }
    // 删除空目录
       public function rm_empty_dir($path=ROOT."public/uploads/covers"){
        if(is_dir($path)&&($houdle = opendir($path))!==false){
                while($file = readdir($houdle)){
                    if($file =="."||$file=="..") continue;
                        if(is_dir($path."/".$file)){
                            $this->rm_empty_dir($path."/".$file);//递归
                            if(count(scandir($path."/".$file))==2){
                                // echo $path."/".$file;die;
                                rmdir($path."/".$file);//删除空目录
                            }
                        }
                }
                closedir($houdle);
        };
    }

    public function _before_delete(){
        $this->_delete_img();
    }
    // covers_md
    public function covers_md($path){
        $img = Image::make($path);
        $img ->resize(265,175);
        $img->save($path);
    }
    // covers_big
    public function covers_big($path){
        $img = Image::make($path);
        $img ->resize(712,415);
        $img->save($path);
    }




    // search
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
        $odby = 'id';
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
        $perpage = 8; // 每页15
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