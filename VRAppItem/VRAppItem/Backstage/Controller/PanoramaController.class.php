<?php
// +----------------------------------------------------------------------
// | Theme:The panorama control class
// +----------------------------------------------------------------------
// | Contents：The panorama control function
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class PanoramaController extends CommonController 
{
    public function index()
    { 
        $field = "vr_panorama.id as vpid,vr_panorama.status as vpstatus,from_unixtime(vr_panorama.addtime,'%Y/%m/%d %H:%i:%s') as vpaddtime,vu.username as publisher,if(`limit`=0,'普通视频','会员视频')`limit`,name,subname,`describe`,click,imageurl";
        $order = 'vpid desc';
        $join = 'left join vr_user vu on vr_panorama.publisherid=vu.id';
        $panoramaList = $this->AdminModelObj
        ->searchPage($this->PanoramaObj,$where,$field,$order,$limit,$join,10);
        $this->assign('panoramaList',$panoramaList);
        $this->display('panorama_list');
    }
    //panorama add function
    public function panoramaAdd()
    { 
        if(IS_POST){ 
            $path = 'panorama';
            $info = $this->upload($path);
            $data['name'] = I('post.name');
            $data['subname'] = I('post.subname');
            $data['describe'] = I('post.describe');
            $data['limit'] = I('post.limit');
            $data['imageurl'] = $info['panorama']['savepath'].$info['panorama']['savename'];
            $data['addtime'] = time();
            $data['publisherid'] = PUBLISHER_ID;
            //add the data to mysql database
            $row = $this->PanoramaObj->add($data);
            if($row){ 
                $this->success('添加成功','index');
            }else{ 
                $this->error('添加失败');
            }
        }else{ 
            $this->display('panorama_add');
        }
    }
    //panorama status
    public function panoramaStatus()
    { 
        $id = $_POST['id'];
        $row = $this->AdminModelObj
        ->status($this->PanoramaObj,$id);
        echo $row;
    }
    //panorama delete
    public function panoramaDel()
    { 
        $id = $_POST['id'];
        //first delete image file
            //select image path
        $path = $this->PanoramaObj
        ->field("concat('".PATH_CARFIG_PREFIX."',imageurl)imageurl")
        ->where('id='.$id)
        ->find();
        $imageurl = $path['imageurl'];
        unlink($imageurl);
        //second delete panorama data
        $row = $this->PanoramaObj
        ->where('id='.$id)
        ->delete();
        $this->ajaxReturn($row);
    }
    //panorama edit
    public function panoramaEdit()
    { 
        if(IS_POST){ 
            if($_FILES['panorama']['error'] == 0){ 
                //uploads image file and success
                $path = 'panorama';
                $info = $this->upload($path);
                $data['imageurl'] = $info['panorama']['savepath'].$info['panorama']['savename'];
                //delete old image file
                $path = PATH_CARFIG_PREFIX.$_POST['imageurl'];
                unlink($path);
            }
                $map['id'] = I('post.id');
                $data['name'] = I('post.name');
                $data['subname'] = I('post.subname');
                $data['describe'] = I('post.describe');
                $data['click'] = I('post.click');
                $data['limit'] = I('limit');
                $row = $this->PanoramaObj
                ->where($map)
                ->save($data);
                if($row){ 
                    $this->success('修改成功','index');
                }else{ 
                    $this->error('修改失败');
                }
        }else{ 
            $id = $_GET['id'];
            $where['id'] = $id;
            $panoramaInfo = $this->PanoramaObj
            ->where($where)
            ->field('id,name,subname,describe,imageurl,click,limit')
            ->find();
            dump($panoramaInfo);
            $this->assign('panoramaInfo',$panoramaInfo);
            $this->display('panorama_edit');
        }
    }
}