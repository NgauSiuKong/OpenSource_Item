<?php
// +----------------------------------------------------------------------
// | Theme:分类管理控制器
// +----------------------------------------------------------------------
// | Contents：用于管理项目中所有的分类
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class ClassController extends CommonController 
{
    //展示分类
    public function index()
    { 
        $list = $this->gradeClass();
        $this->assign('list',$list);
        $this->display('class_list');
    }
    //添加分类
    public function classAdd()
    { 
        if(IS_POST){ 
            $data['c_title'] = I('post.title');
            $data['c_describe'] = I('post.describe');
            $data['c_fid'] = 0;
            $data['c_path'] = 0;
            $data['c_addtime'] = time();
            //插入数据,获取插入数据的id
            $row_id = $this->ClassObj
            ->add($data);
            if($row_id){ 
                //插入成功,插入oid
                $data_oid['c_oid'] = $row_id;
                $map_oid['c_id'] = $row_id;
                $row = $this->ClassObj
                ->where($map_oid)
                ->save($data_oid);
                if($row){ 
                    $this->success('插入成功','index');
                }else{ 
                    $this->error('插入排序失败');
                }
            }else{ 
                $this->error('插入数据失败');
            }
        }else{ 
            $this->display('class_add');
        }
    }
    //添加子分类
    public function classSonAdd()
    { 
        if(IS_POST){ 
            $data['c_title'] = I('post.title');
            $data['c_describe'] = I('post.describe');
            $data['c_fid'] = I('post.fid');
            $data['c_path'] = I('post.path').','.I('post.fid');
            $data['c_addtime'] = time();
            $row_id = $this->ClassObj
            ->add($data);
            if($row_id){ 
                //插入成功,插入oid
                $data_oid['c_oid'] = $row_id;
                $map_oid['c_id'] = $row_id;
                $row = $this->ClassObj
                ->where($map_oid)
                ->save($data_oid);
                if($row){ 
                    $this->success('插入成功','index');
                }else{ 
                    $this->error('插入排序失败');
                }
            }else{ 
                $this->error('插入数据失败');
            }
        }else{ 
            $c_id = I('get.c_id');
            $class_info = $this->ClassObj
            ->where('c_id ='.$c_id)
            ->field('c_path,c_id,c_title')
            ->find();
            $this->assign('class_info',$class_info);
            $this->display('class_son_add');
        }
    }
    //修改分类
    public function classEdit()
    { 
        if(IS_POST){ 
            $map['c_id'] = I('post.id');
            $data['c_title'] = I('post.title');
            $data['c_describe'] = I('post.describe');
            $row = $this->ClassObj
            ->where($map)
            ->save($data);
            if($row){ 
                $this->success('修改成功','index');
            }else{ 
                $this->error('修改失败');
            }
        }else{ 
            $id = $_GET['id'];
            $map['c_id'] = $id;
            $class_info = $this->ClassObj
            ->where($map)
            ->field('c_id,c_title,c_describe')
            ->find();
            $this->assign('class_info',$class_info);
            $this->display('class_edit');
        }
    }
    //delete class
    public function classDel()
    { 
        //the supper class can't delete
        $id = $_POST['id'];
        $map['c_id'] = $id;
            //select this class fid
        $fid_this = $this->ClassObj
        ->where($map)
        ->field('c_fid')
        ->find();
        $fid_this = $fid_this['c_fid'];
        dump($fid_this);
        switch($fid_this)
        { 
            //if this fid_this is zero,they can't delete
            case 0 :
                    $this->ajaxReturn('400');
                break;
            //this class is not super class，may delete
            default:
                //when delete class,before delete lab_cla link table datas
                $map_lab_cla['cid'] = $id;
                $row_lab_cla = $this->Lab_ClaObj
                ->where($map_lab_cla)
                ->delete();
                //delete class
                $row_cla = $this->ClassObj
                ->where($map)
                ->delete();
                if($row_cla){ 
                    $this->ajaxReturn('200');
                }else{ 
                    $this->ajaxReturn('501');
                }

                break;
        }
        /*
        //if delete super class，will delete they all son class 
        $id = $_POST['id'];
        $map['c_id'] = $id;
        $fid = $this->ClassObj
        ->where($map)
        ->field('c_fid')
        ->find();
        $fid = $fid['c_fid'];
        if(!$fid){ 
            //if have son class,first delete son class
            $map_son['c_fid'] = $id;
            $son = $this->ClassObj
            ->where($map_son)
            ->field('c_id')
            ->select();
            foreach($son as $key => $val){ 
                $map_del['c_id'] = $val['c_id'];
                $row[] = $this->ClassObj
                ->where($map_del)
                ->delete();
            }
        }
        $row = $this->ClassObj
        ->where($map)
        ->delete();
        $this->ajaxReturn($row);
        */
    }
    //modify status 
    public function statusEdit()
    { 
        $id = $_POST['id'];
        $profix = 'c_';
        $row = $this->AdminModelObj
        ->status($this->ClassObj,$id,$profix);
        $this->ajaxReturn($row);
    }
    //class list sort
    public function classSort()
    { 
        $handle = I('get.handle');
        $id = I('get.c_id');
        $prefix = 'c_';
        $this->sort($this->ClassObj,$handle,$id,$prefix);
    }
    //-------------------辅助方法---------------------
    //递归查询无限分类
    public function rcsSelClass($fid,&$list=array())
    { 
        $where['c_fid'] = $fid;
        $field = 'c_id,c_fid,c_oid as oid,c_title,c_describe,c_status,c_path,date_format(from_unixtime(c_addtime),\'%Y/%m/%d %H:%i:%s\')c_addtime';
        $order = 'oid desc';
        $arr = $this->ClassObj
        ->where($where)
        ->field($field)
        ->order($order)
        ->select();
        foreach($arr as $val){ 
            $list[] = $val;
            $this->rcsSelClass($val['c_id'],$list);
        }
        return $list;
    }
    //处理分类的级别和分类头显示
    public function gradeClass()
    { 
        $list = $this->rcsSelClass(0,$list);
        for($i=0;$i<count($list);$i++){                                
            $num = substr_count($list[$i]['c_path'],',');
            $list[$i]['classgrade'] = str_repeat('I',$num+1);
            $str = str_repeat('|--',$num);
            $list[$i]['c_title'] = $str.$list[$i]['c_title'];
        }
        return $list;
    }
}