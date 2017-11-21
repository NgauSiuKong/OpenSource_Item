<?php
// +----------------------------------------------------------------------
// | Theme:公共类
// +----------------------------------------------------------------------
// | Contents：用于其他类继承,验证登录，权限，和公共方法的书写
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class CommentController extends CommonController 
{
    //display comment list table
    public function index()
    { 

        if(IS_POST){ 
            switch($_POST['searchtype'])
            { 
                case 1:
                    
                break;
                case 2:
                   
                break;
                case 3:
                 
                break;
            }
            echo "<pre>";
                print_r($where);
        }

        $field = 'vr_comment.id as commentid,vv.v_title as videotitle,vu.username ,from_unixtime(time,\'%Y-%m-%d %H:%i:%s\')time,content,comstatus,`like`';
        $join = 'left join vr_video as vv on vr_comment.resourceid = vv.v_id left join vr_user as vu on vr_comment.userid = vu.id';
        $order = 'commentid desc';
        $list = $this->AdminModelObj
        ->searchPage($this->CommentObj,$where,$field,$order,$limit,$join,10);
        $this->assign('list',$list);
        $this->display('comment_list');
        
    }
    //modify comment condition
    public function status()
    { 
        $id = $_POST['id'];
        $map['id'] = $id;
        $status = $this->CommentObj
        ->where($map)
        ->field('comstatus')
        ->find();
        $status = $status['comstatus'];
        if($status){ 
            //if start condition,modify forbidden
            $data['comstatus'] = 0;
            $row = $this->CommentObj
            ->where($map)
            ->save($data);
            $this->ajaxReturn($row);
        }else{ 
            $data['comstatus'] = 1;
            $row = $this->CommentObj
            ->where($map)
            ->save($data);
            $this->ajaxReturn($row);
        }
    }
    //delete comment
    public function delete()
    { 
        $id = $_POST['id'];
        $map['id'] = $id;
        $row = $this->CommentObj
        ->where($map)
        ->delete();
        $this->ajaxReturn($row);
    }
}