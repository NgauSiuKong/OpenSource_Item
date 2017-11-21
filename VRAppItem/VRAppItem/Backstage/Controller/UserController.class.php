<?php
// +----------------------------------------------------------------------
// | Theme: control client user class
// +----------------------------------------------------------------------
// | Contents：control client user function
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class UserController extends CommonController 
{
    //主页展示
    public function index()
    { 
        //查询条件
        $order = "id desc";
        $field = 'id,username,password,addtime,facility,status,grade,ordernumber,profileimg,tel,hostid,birthday,emotionalstatus,email,sex';
        $join = 'left join vr_userdetail on vr_user.id = vr_userdetail.hostid';
        //查询
        $list = $this->AdminModelObj
        ->searchPage($this->UserObj,$where,$field,$order,$limit,$join,10);
        $this->assign('list',$list);
        $this->display('user_list');
    }
    //详细信息展示
    public function userShow()
    { 
        $id = $_GET['id'];
        $map['id'] = $id;
        $user_info = $this->UserObj
        ->where($map)
        ->field('id,username,password,addtime,facility,status,grade,ordernumber,profileimg,tel,hostid,birthday,emotionalstatus,email,sex')
        ->join('left join vr_userdetail on vr_user.id = vr_userdetail.hostid')
        ->find();
        $this->assign('user_info',$user_info);
        $this->display('user_show');
    }
    //禁用的用户展示
    public function userStopList()
    { 
        //查询条件
        $where['status'] = 0;
        $order = "id desc";
        $field = 'id,username,password,addtime,facility,status,grade,ordernumber,profileimg,tel,hostid,birthday,emotionalstatus,email,sex';
        $join = 'left join vr_userdetail on vr_user.id = vr_userdetail.hostid';
        //查询
        $list_stop = $this->AdminModelObj
        ->searchPage($this->UserObj,$where,$field,$order,$limit,$join,10);
        $this->assign('list_stop',$list_stop);
        $this->display('user_stop_list');
        
    }
    //禁用用户
    public function status()
    { 
        dump($_POST);
        $id = $_POST['id'];
        $row = $this->AdminModelObj->status($this->UserObj,$id);
        $this->ajaxreturn($row);
    }

}