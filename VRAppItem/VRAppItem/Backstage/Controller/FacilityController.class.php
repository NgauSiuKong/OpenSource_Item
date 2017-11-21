<?php
// +----------------------------------------------------------------------
// | Theme:公共类
// +----------------------------------------------------------------------
// | Contents：用于其他类继承,验证登录，权限，和公共方法的书写
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class FacilityController extends CommonController 
{
    //设备的展示
    public function index()
    { 
        $order = 'oid desc';
        $where['fid'] = 0;
        $list = $this->AdminModelObj->searchPage($this->FacilityObj,$where,$field,$order,$limit,$join,$page);
        //格式化时间戳
        $list = $this->AdminModelObj->transTime($list);
        $this->assign('list',$list);
        $this->display('facility_list');
    }
    //设备品牌的添加
    public function add()
    { 
        if(IS_POST){
            $data['fid'] = I('post.fid');
            $data['facilityname'] = I('post.facilityname');
            $data['remark'] = I('post.remark');
            $data['addtime'] = time();
            $row_id = $this->FacilityObj
            ->add($data);
            if($row_id){ 
                //数据添加成功
                $map['id'] = $row_id;
                $data['oid'] = $row_id;
                //添加排序id
                $row = $this->FacilityObj
                ->where($map)
                ->save($data);
                if($row){ 
                    $this->success('添加成功','index');
                }else{ 
                    $this->error('添加排序id失败');
                }
            }else{ 
                //数据添加失败
                $this->error('添加数据失败');
            }
        }else{
            $id = $_GET['id']?$_GET['id']:0;
            $this->assign('id',$id);
            $this->display('facility_add');
        }
    }
    //设备型号的展示
    public function facilityModelList()
    { 
        //------------查询产品的信息-----------
        $where_name['id'] = $_GET['fid'];
        $father_info = $this->FacilityObj
        ->where($where_name)
        ->field('id,facilityname')
        ->find();
        //----------------end---------------
        //------------查询子类的信息--------
        $where['fid'] = $_GET['fid'];
        $order = "oid desc";
        $list_model = $this->AdminModelObj->searchPage($this->FacilityObj,$where,$field,$order,$limit,$join,$page);
        $list_model = $this->AdminModelObj->transTime($list_model);
        //---------------end-----------------
        $this->assign('father_info',$father_info);
        $this->assign('list_model',$list_model);
        $this->display('facility_model_list');
    }
    //设备型号的展示栏添加设备型号
    public function addType()
    { 
        if(IS_POST){ 
            $data['fid'] = I('post.fid');
            $data['facilityname'] = I('post.facilityname');
            $data['remark'] = I('post.remark');
            $data['addtime'] = time();
            //添加数据
            $row_id = $this->FacilityObj
            ->add($data);
            if($row_id){ 
                $map_faci['id'] = $row_id;
                $data_faci['oid'] = $row_id;
                $row_res = $this->FacilityObj
                ->where($map_faci)
                ->save($data_faci);
                if($row_res){ 
                    $this->success('成功','facilityModelList?fid='.$_POST['fid']);
                }else{ 
                    $this->error('排序id添加失败');
                }
            }else{ 
                $this->error('数据添加失败');
            }
        }else{ 
            $father_id = $_GET['id'];
            $this->assign('father_id',$father_id);
            $this->display('facility_model_add');

        }
    }
    //设备的排序
    public function facilitySort()
    { 
        //$fid = $_GET['fid']?$_GET['fid']:0;
        $where['fid'] = 0;
        $this->sort($this->FacilityObj,$where);
    }
    public function facilityModelSort()
    { 
        $fid = $_GET['fid'];
        $where['fid'] = $fid;
        $handle = $_GET['handle'];
        $id_cur = $_GET['id'];
        $oid_cur = $_GET['oid'];
        switch($handle)
        { 
            //向前顶
            case 'pre':
                //由于是oid倒序，找出oid的最大值
                $oid_max = $this->FacilityObj
                ->where($where)
                ->field('oid')
                ->order('oid desc')
                ->find();
                $oid_max = $oid_max['oid'];
                //判断，如果oid是最大的,已经是第一个不可再操作,如果不是最大的，和前面更换
                if($oid_cur == $oid_max){ 
                    $this->error('这已经是第一个');
                }else{ 
                    //往上顶,交换oid,先查出上一个的id和oid
                    $data = $this->AdminModelObj
                    ->nextData($this->FacilityObj,$oid_cur);
                    $id_pre = $data['id'];
                    $oid_pre = $data['oid'];
                    //交换oid,改变顺序
                    $bools = $this->AdminModelObj
                    ->transOid($this->FacilityObj,$id_cur,$oid_cur,$id_pre,$oid_pre);
                    if($bools){ 
                        $this->redirect('facilityModelList?fid='.$fid);
                    }else{ 
                        $this->error('排序失败');
                    }
                }
            break;
            //向后拉
            case 'next':
                //查出最小的oid
                $oid_min = $this->FacilityObj
                ->where($where)
                ->field('oid')
                ->order('oid')
                ->find();
                $oid_min = $oid_min['oid'];
                //判断，如果oid最小,不在往下拉
                if($oid_cur == $oid_min){ 
                    $this->error('这已经是最后一个');
                }else{ 
                    $data = $this->AdminModelObj
                    ->preData($this->FacilityObj,$oid_cur);
                    $id_next = $data['id'];
                    $oid_next = $data['oid'];
                    //交换oid,改变顺序
                    $bools = $this->AdminModelObj
                    ->transOid($this->FacilityObj,$id_cur,$oid_cur,$id_next,$oid_next);
                    if($bools){ 
                        $this->redirect('facilityModelList?fid='.$fid);
                    }else{ 
                        $this->error('排序失败');
                    }
                }
            break;
        }
    }
    //设备的禁用启用
    //注意禁用的过程中，他的子类也要被禁用
    public function status()
    { 
        $id = $_POST['id'];
        //查询子类id
        $where['fid'] = $id;
        $son_id = $this->FacilityObj
        ->where($where)
        ->field('id')
        ->select();
        //如果有子类,全部禁用
        if($son_id){ 
            $status_id[] = $id;
            foreach($son_id as $val){ 
                $status_id[] = $val['id'];
            }
            foreach($status_id as $val){ 
                $row[] = $this->AdminModelObj
                ->status($this->FacilityObj,$val);
            }
            var_dump($row);
        }else{ 
        //如果没有子类，只需要禁用他自己
            $row = $this->AdminModelObj->status($this->FacilityObj,$id);
        }
    }
    //设备的删除
    public function delete()
    { 
        $id = $_POST['id'];
        //查询子类id
        $where['fid'] = $id;
        $son_id = $this->FacilityObj
        ->where($where)
        ->field('id')
        ->select();
        if($son_id){ 
            //如果有子类,全部删除
            $map1['id'] = $id;
            $row1 = $this->FacilityObj
            ->where($map1)
            ->delete();
            //删除子类
            $map2['fid'] = $id;
            $row2 = $this->FacilityObj
            ->where($map2)
            ->delete();
        }else{ 
            //如果没有子类,只删除自己
            $map_delete['id'] = $id;
            $row = $this->FacilityObj
            ->where($map_delete)
            ->delete();
        }
    }
    //设备的修改
    public function edit()
    { 
        if(IS_POST){
            $map['id'] = I('post.id');
            $data['facilityname'] = I('post.facilityname');
            $data['remark'] = I('post.remark');
            $row = $this->FacilityObj
            ->where($map)
            ->save($data);
            if($row){ 
                $this->success('修改成功','index');
            }else{ 
                $this->error('修改失败');
            }
        }else{ 
            $id = $_GET['id'];
            //查询设备的原信息
            $map['id'] = $id;
            $faci_info = $this->FacilityObj
            ->where($map)
            ->field('id,facilityname,remark')
            ->find();
            $this->assign('faci_info',$faci_info);
            $this->display('facility_edit');
        }
    }
    //设备型号的修改
    public function editModel()
    { 
        if(IS_POST){
            $fid = I('post.fid');
            $map['id'] = I('post.id');
            $data['facilityname'] = I('post.facilityname');
            $data['remark'] = I('post.remark');
            $row = $this->FacilityObj
            ->where($map)
            ->save($data);
            if($row){ 
                $this->success('修改成功','facilityModelList?fid='.$fid);
            }else{ 
                $this->error('修改失败');
            }
            
        }else{ 
            $id = $_GET['id'];
            $fid = $_GET['fid'];
            //查询设备的原信息
            $map['id'] = $id;
            $faci_info = $this->FacilityObj
            ->where($map)
            ->field('id,facilityname,remark')
            ->find();
            $this->assign('fid',$fid);
            $this->assign('faci_info',$faci_info);
            $this->display('facility_model_edit');
        }
    }
}