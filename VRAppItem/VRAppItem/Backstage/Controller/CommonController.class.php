<?php

namespace Backstage\Controller;
use Think\Controller;
class CommonController extends Controller 
{
    public $CarouselFigureObj;
    public $ClassObj;
    public $AdminModelObj;
    public $LabelObj;
    public $Lab_ClaObj;
    public $Vid_LabObj;
    public $VideoObj;
    public $UserObj;
    public $UserDetailObj;
    public $FacilityObj;
    public $CommentObj;
    public $PanoramaObj;
    /**
     * 初始化
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function _initialize()
    { 
        $this->CarouselFigureObj = M('carouselfigure');
        $this->ClassObj = M('class');
        $this->LabelObj = M('label');
        $this->Lab_ClaObj = M('lab_cla');
        $this->AdminModelObj = D('admin');
        $this->VideoObj = M('video');
        $this->Vid_LabObj = M('vid_lab');
        $this->UserObj = M('user');
        $this->UserDetailObj = M('userdetail');
        $this->FacilityObj = M('facility');
        $this->CommentObj = M('comment');
        $this->PanoramaObj = M('Panorama');
    }
    /**
     * 上传图片文件方法
     * @param $path 上传文件的分路径
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function upload($path)
    { 
        $uploadObj = new \Think\Upload();
        $uploadObj->exts = array('jpg','jpeg','png','gif','mp4');//文件类型
        $uploadObj->rootPath = "./Public/";//文件上传根目录
        $uploadObj->savePath = "Uploads/".$path."/";//文件上传目录
        $info = $uploadObj->upload();//上传文件
        if($info){//上传成功,输出详细信息
            return $info;
        }else{    //上传失败,提示错误信息
            $this->error($uploadObj->getError());
        }
    }
    /**
     * 排序
     * @param $obj  操作排序的obj
     * @param $where 主要为了分类提供，同一个分类必须会有同一个查询条件
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    
    public function sort($obj,$handle,$id,$prefix)
    { 
        //根据传递的id查询出当前的oid(排序id);
        $map_sel_oid[$prefix.'id'] = $id;
        $oid = $obj
        ->field($prefix.'oid')
        ->where($map_sel_oid)
        ->find();
        $oid = $oid[$prefix.'oid'];
        //----------------------------------------------
        //这里的排序不仅仅是一般的排序,还有无限分类的排序,为了后续的顺利,给无限分类一个查询条件
        $arr = $obj->where($map_sel_oid)->find();
        //判断是否存在某键值
        $bool = array_key_exists($prefix.'fid',$arr);
        if($bool){ 
            $where[$prefix.'fid'] = $arr[$prefix.'fid'];
        }else{ 
            $where = "";
        }
        //----------------------------------------------
        //确定当前的id和oid
        $cur_id = $id;
        $cur_oid = $oid;
        $obj = $obj;
        //根据handle指针判断上移下移
        switch ($handle) {
            //指针为pre,表示上移
            case 'pre':
                    //查询最大的oid,如果oid最大,则不能往上顶
                    $max_oid = $obj
                    ->where($where)
                    ->order($prefix.'oid desc')
                    ->field($prefix.'oid')
                    ->find();
                    $max_oid = $max_oid[$prefix.'oid'];
                    
                    //如果最大,那么不能往上顶了,后面的程序也不执行
                    if($cur_oid == $max_oid){ 
                        $this->error('亲,这个已经是最顶部了');
                    }else{
                        $preData = $this->preData($obj,$cur_oid,$prefix,$where);
                        dump($preData);
                        
                        $pre_id = $preData[$prefix.'id'];
                        $pre_oid = $preData[$prefix.'oid'];
                        //查询了上一个的id和oid最后交换即可
                        
                        $bools = $this->transOid($obj,$cur_id,$cur_oid,$pre_id,$pre_oid,$prefix);
                        if($bools){ 
                            $this->redirect('index');
                        }else{ 
                            $this->error('排序失败');
                        }
                    }
                break;
            //指针为next,下移
            case 'next':
                    //查询最小的oid,如果oid最小,则不能忘下移
                    $min_oid = $obj
                    ->where($where)
                    ->order($prefix.'oid asc')
                    ->field($prefix.'oid')
                    ->find();
                    $min_oid = $min_oid[$prefix.'oid'];
                    if($cur_oid == $min_oid){ 
                        $this->error('亲,这已经是最底部了');
                    }else{ 
                        $nextData = $this->nextData($obj,$cur_oid,$prefix,$where);
                        $next_id = $nextData[$prefix.'id'];
                        $next_oid = $nextData[$prefix.'oid'];

                        $bools = $this->transOid($obj,$cur_id,$cur_oid,$next_id,$next_oid,$prefix);
                        if($bools){ 
                            $this->redirect('index');
                        }else{ 
                            $this->error('排序失败');
                        }
                    }
                break;
        }
    }
    //排序算法,查找上一个的id和oid
    public function preData($obj,$oid,$prefix,$where)
    { 
        $oid = ++$oid;
        //$map[$prefix.'oid'] = $oid;
        $where[$prefix.'oid'] = $oid;
        $res = $obj
        ->where($where)
        ->field($prefix.'id'.','.$prefix.'oid')
        ->find();
        if($res){ 
            return $res;
        }else{ 
            return $this->preData($obj,$oid,$prefix);
        }

    }
    //排序算法,查找下一个的id和oid
    public function nextData($obj,$oid,$prefix,$where)
    { 
        $oid = --$oid;
        //$map[$prefix.'oid'] = $oid;
        $where[$prefix.'oid'] = $oid;
        $res = $obj
        ->where($where)
        ->field($prefix.'id'.','.$prefix.'oid')
        ->find();
        if($res){ 
            return $res;
        }else{ 
            return $this->nextData($obj,$oid,$prefix);
        }
    }
    //交换id和oid
    public function transOid($obj,$cur_id,$cur_oid,$else_id,$else_oid,$prefix)
    { 
        $map[$prefix.'id'] = $cur_id;
        $data[$prefix.'oid'] = $else_oid;
        $row = $obj->where($map)->save($data);
        if($row){ 
            $map[$prefix.'id'] = $else_id;
            $data[$prefix.'oid'] = $cur_oid;
            $row1 = $obj->where($map)->save($data);
            if($row1){ 
                return true;
            }else{ 
                return false;
            }
        }else{ 
            return false;
        }
    }
    //递归函数递归无限分类,分支树的形式显示
    public function recursion($arr,$key,$fkey,$num)
    { 
        $list = array();
        foreach($arr as $val){ 
            if($val[$fkey] == $num){ 
                foreach($arr as $v){ 
                    if($v[$fkey] == $val[$key]){ 
                        $val['son'] = $this->recursion($arr,$key,$fkey,$val[$key]);
                    }
                }
                $list[] = $val;
            } 
        }
        return $list;
    }
    //kv形式显示分类,k分类id,v分类名
    public function KVclass()
    { 
        $classList = $this
        ->ClassObj
        ->field('c_id,c_title')
        ->where('c_fid != 0')
        ->select();
        $KVclass = array(); 
        foreach($classList as $k => $v){ 
            $KVclass[$v['c_id']] = $v['c_title'];
        }
        return $KVclass;
    }
    //kv形式显示标签
    public function KVlabel()
    { 
        $labelList = $this
        ->LabelObj
        ->field('l_id,l_title')
        ->select();
        $KVlabel = array(); 
        foreach($labelList as $k => $v){ 
            $KVlabel[$v['l_id']] = $v['l_title'];
        }
        return $KVlabel;
    }
}