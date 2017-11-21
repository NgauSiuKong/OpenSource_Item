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
class LabelController extends CommonController 
{
    //标签展示
    public function index()
    { 
        $label_list = $this->labelDisplay();
        $this->assign('label_list',$label_list);
        $this->display('label_list');
    }
    //标签添加
    public function labelAdd()
    { 
        if(IS_POST){ 
            $data['l_title'] = I('post.title');
            $data['l_addtime'] = time();
            $row_id = $this->LabelObj->add($data);
            if($row_id){ 
                $map['l_id'] = $row_id;
                $data_lb['l_oid'] = $row_id;
                $row_oid = $this->LabelObj->where($map)->save($data_lb);
                if($row_oid){ 
                    //将分类和标签进行链接
                    $data_lab_cla['lid'] = $row_id;
                    $cla_arr = $_POST['cid'];
                    foreach($cla_arr as $val){ 
                        $data_lab_cla['cid'] = $val;
                        $row_res = $this->Lab_ClaObj->add($data_lab_cla);
                    }
                    if($row_res){ 
                        $this->success('添加标签成功','index');
                    }else{ 
                        $this->error('标签分类链接失败');
                    }
                }else{ 
                    $this->error('oid添加失败');
                }
            }else{ 
                $this->error('标签添加失败');
            }
        }else{ 
            //查询分类,添加标签需要分类限制
            $where['c_status'] = 1;
            $class_list = $this->ClassObj
            ->field('c_id,c_fid,c_title')
            ->where($where)
            ->order('c_oid desc')
            ->select();
            $class_list = $this->recursion($class_list,'c_id','c_fid','0');
            $this->assign('class_list',$class_list);
            $this->display('label_add');
        }
    }
    //标签排序
    public function labelSort()
    { 
        $handle = I('get.handle');
        $id = I('get.id');
        $prefix = 'l_';
        $this->sort($this->LabelObj,$handle,$id,$prefix);
    }
    //处理标签的显示问题
    public function labelDisplay()
    { 
        //查询标签
        $label_list = $this->LabelObj
        ->order('l_oid desc')
        ->field('l_id,l_title,l_oid,l_status,from_unixtime(l_addtime,\'%Y-%m-%d %H:%i:%s\')l_addtime')
        ->select();
        $class_id = array();
        $class_fm_id = array();
        foreach($label_list as $key => $val){ 
            $map['lid'] = $val['l_id'];
            $class_id[$val['l_id']] = $this->Lab_ClaObj->field('cid')->where($map)->select();
        }
        $class_fm_id = array();
        foreach($class_id as $key => $val){ 
            foreach($val as $v){ 
                $class_fm_id[$key][] = $v['cid'];
            }
        }
        $KVclass = $this->KVclass();
        $class_name = array();
        foreach($class_fm_id as $key => $val){ 
            foreach($val as $k => $v){ 
                $class_name[$key][$v] = $KVclass[$v];
            }
        }
        $class_name_str = array();
        foreach($class_name as $key => $val){ 
            $class_name_str[$key] = implode(',',$val);
        }
        for($i=0;$i<count($label_list);$i++){ 
            $label_list[$i]['classname'] = $class_name_str[$label_list[$i]['l_id']];
        }
        return $label_list;
    }
    //modify label status
    public function statusLab()
    { 
        $id = $_POST['id'];
        $profix = 'l_';
        $row = $this->AdminModelObj
        ->status($this->LabelObj,$id,$profix);
        $this->ajaxReturn($row);
    }
    //delete label
    public function labelDel()
    { 
        $id = $_POST['id'];
        //delete label before delete lab_cla
        //delete vid_lab
        $map_lab_cla['lid'] = $id;
        $row_lab_cla = $this->Lab_ClaObj
        ->where($map_lab_cla)
        ->delete();

        $map_vid['l_id'] = $id;
        $row_vid = $this->LabelObj
        ->where($map_vid)
        ->delete();
        $this->ajaxReturn($row_vid);
    }
    //edit label
    public function labelEdit()
    { 

    }
}