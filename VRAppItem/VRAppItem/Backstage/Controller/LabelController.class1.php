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
    /**
     * 标签的展示
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function index()
    { 
        $list = $this->listLabel();
        echo "<pre>";
            print_r($list);
        $this->display('label_list');
    }
    /**
     * 标签的添加
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function add()
    { 
        if(IS_POST){ 
            $data['title'] = I('post.title');
            $cid_arr = $_POST['cid'];
            $data['addtime'] = time();
            //插入基本信息
            $row_id = $this->LabelObj->add($data);
            if($row_id){ 
                //添加oid
                $map['id'] = $row_id;
                $data['oid'] = $row_id;
                $row_oid = $this->LabelObj->where($map)->save($data);
                if($row_oid){ 
                    //添加oid成功,添加多对多表格
                    $data_cl['lid'] = $row_id;
                    for($i=0;$i<count($cid_arr);$i++){ 
                        $data_cl['cid'] = $cid_arr[$i];
                        $row = $this->Lab_ClaObj->add($data_cl);
                    }
                    if($row){ 
                        $this->success('添加成功','index');
                    }else{ 
                        $this->error('没有选择分类');
                    }
                }else{ 
                    $this->error('添加oid失败');
                }
            }else{ 
                $this->error('添加基本信息失败');
            }
        }else{ 
            //查询分类,链接标签
            $class = $this->ClassObj->field('id,title')->select();
            $this->assign("class",$class);
            $this->display('label_add');
        }
    }
    //-------------------标签的展示处理------------------------
   
    /**
     * 将查询出的数组处理成维
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function listLabel()
    { 
        $order = "oid desc";
        $listLabel = $this->AdminModelObj->searchPage($this->LabelObj,0,0,$order,0,0,100);
        //格式化时间戳
        $listLabel = $this->AdminModelObj->transTime($listLabel);
        //查询链接表
        $selArr = $listLabel['selArr'];
        $selArr = $this->selArr($selArr);
        $listLabel['selArr'] = $selArr; 
        return $listLabel;
    }
    /**
     * 拼接成型的selArr
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function selArr($arr)
    { 
        $classname = $this->dealClass();
        for($i=0;$i<count($arr);$i++){ 
            $arr[$i]['class'] =  $arr[$i]['id'];
        }
        return $arr;
    }
    /**
     * 将查询出的数组处理成维
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function proLid()
    { 
        $list = $this->listLabel();
        $selArr = $list['selArr'];
        $lid_arr = array();
        foreach($selArr as $key => $val){ 
            $lid_arr[] = $val['id'];
        }
        return $lid_arr;
    }
    /**
     * 处理类名
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function dealClass()
    { 
        $cid_arr = $this->forsel();
        $arr_tran_class = $this->selClass();
        $classname = array();
        foreach($cid_arr as $key => $val){ 
            foreach($val as $ke => $va){ 
                $classname[$key][] = $arr_tran_class[$va];
            }
        }
        foreach($classname as $key => $val){ 
            $classname[$key] = implode(',',$val);
        }
        dump($classname);
    }
    /**
     * 处理多对多的循环遍历查询
     * @param $obj 多对多表对象
     * @param $arr 提供查询条件的数组
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function forsel()
    { 
        $lid_arr = $this->proLid();
        $cid_arr = array();
        foreach($lid_arr as $key => $val){ 
            $map['lid'] = $val;
            $cid_arr[$val] = $this->Lab_ClaObj
            ->where($map)
            ->field('cid')
            ->select();
        }
        $cid_arr = $this->dealArr($cid_arr);
        return $cid_arr;
    }
    /**
     * 二维数组变一维
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function dealArr($arr)
    { 
        foreach($arr as $key =>$val){ 
            foreach($val as $k => $v){ 
                $arr[$key][$k] = $v['cid'];
            }
        }
        return $arr;
    }
    /**
     * 查询分类
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function selClass()
    { 
        $arrClass = $this->ClassObj
        ->field('id,title')
        ->select();
        $arr_tran_class = array();
        foreach($arrClass as $key => $val){ 
            $arr_tran_class[$val['id']] = $val['title'];
        }
        return $arr_tran_class;
    }
    //------------------------end------------------------------

}