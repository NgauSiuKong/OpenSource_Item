<?php
// +----------------------------------------------------------------------
// | Theme:deal with video resource
// +----------------------------------------------------------------------
// | Contents:mail operate video resource
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
class VideoController extends CommonController 
{
    //the mail page to display videolist
    public function index()
    { 
        $list = $this->selVideo();

        $this->assign('list',$list);
        $this->display('video_list');
    }
    //add video resource
    public function videoAdd()
    { 
        if(IS_POST){
            $label_arr = $_POST['lid'];
            $data_v['v_cid'] = I('post.v_cid');
            $data_v['v_title'] = I('post.v_title');
            $data_v['v_describe'] = I('post.v_describe');
            $data_v['v_type'] = I('post.v_type');
            $data_v['v_data1'] = I('post.v_data1');
            $data_v['v_data2'] = I('post.v_data2');
            $data_v['limit'] = I('post.limit');
            $data_v['v_addtime'] = time();
            $data_v['v_publisherid'] = PUBLISHER_ID;
            $path = 'VideoImg';
            //call commen controller function upload deal with file uploads
            $file_info = $this->upload($path);
            $data_v['v_firstimgurl'] = $file_info['file_small']['savepath'].$file_info['file_small']['savename'];
            $data_v['v_secondimgurl'] = $file_info['file_big']['savepath'].$file_info['file_big']['savename'];
            //input video table data
            $row_id = $this->VideoObj
            ->add($data_v);
            if($row_id){ 
                //link the video table and label table
                $data_lv['vid'] = $row_id;
                foreach($label_arr as $key => $val){ 
                    $data_lv['lid'] = $val;
                    $row_res = $this->Vid_LabObj->add($data_lv);
                }
                if($row_res){ 
                    $this->success('添加视频成功','index');
                }else{ 
                    $this->error('视频和标签链接添加失败');
                }
            }else{ 
                $this->error('视频内容添加失败');
            }
        }else{ 
            //select class
            $selclassObj = A('Label');
            $classlist = $this->ClassObj
            ->where('c_status = 1')
            ->order('c_oid desc')
            ->field('c_id,c_fid,c_title')
            ->select();
             $classlist = $this->recursion($classlist,'c_id','c_fid','0');
            //select label for KV display
            $labelKV = $this->KVlabel();
            $this->assign('classlist',$classlist);
            $this->assign('labelKV',$labelKV);
            $this->display('video_add');
        }
    }
    public function status()
    { 
        $video_id = $_POST['id'];
        $row = $this->AdminModelObj->status($this->VideoObj,$video_id);
        echo $row;
    }
    
//--------------select and display video-------------
    //select video detial infomation for index
    public function selVideo()
    { 
        $where['recycle'] = 1;
        $order = "v_id desc";
        $field = "v_id,v_cid,v_title,v_describe,v_type,v_data1,v_data2,v_firstimgurl,v_secondimgurl,limit,from_unixtime(v_addtime,'%Y-%m-%d %H:%i:%s')v_addtime,v_status,v_publisherid,playnum,recycle";
        $videolist = $this->AdminModelObj
        ->searchPage($this->VideoObj,$where,$field,$order,$limit,$join,3);
        $videolist = $this->matcheLable($videolist);
        $videolist = $this->matcheClass($videolist);
        return $videolist;
    }
    //select and matching video-label
    public function matcheLable($select_arr)
    { 
        $selArr = $select_arr['selArr'];
        //select vid_lab table link video and label
        $label_arr = array();
        for($i=0;$i<count($selArr);$i++){ 
            $map['vid'] = $selArr[$i]['v_id'];
            $label_arr[$selArr[$i]['v_id']] = $this->Vid_LabObj
            ->where($map)
            ->field('lid')
            ->select();
        }
        //please two dimension array transform to one dimension
        $label_id = array();
        foreach($label_arr as $key => $val){ 
            foreach($val as $k => $v){ 
                $label_id[$key][] = $v['lid']; 
            }
        }
        //assign a value for label_id arr
        $labelKV =  $this->KVlabel();
        $label_val = array();
        foreach($label_id as $key => $val){ 
            foreach($val as $k => $v){ 
                $label_val[$key][] = $labelKV[$v];
            }
        }
        //label_val array transform string
        $label_str = array();
        foreach($label_val as $key => $val){ 
            $label_str[$key] = implode(',',$val); 
        }
        //amalgamate label_str and selArr
        for($i=0;$i<count($selArr);$i++){ 
            $selArr[$i]['label'] = $label_str[$selArr[$i]['v_id']];
        }
        $select_arr['selArr'] = $selArr;
        return $select_arr;
    }
    //select and matching father-class and video
    public function matcheClass($select_arr)
    { 
        $selArr = $select_arr['selArr'];
        $KVclass = $this->KVclass();
        for($i=0;$i<count($selArr);$i++){ 
            $selArr[$i]['v_cid'] = $KVclass[$selArr[$i]['v_cid']]; 
        }
        $select_arr['selArr'] = $selArr;
        return $select_arr;
    }
    //modify video resource status
    public function modStatus()
    { 
        $id = $_POST['id'];
        $row = $this->AdminModelObj->status($this->VideoObj,$id,'v_');
        $this->ajaxReturn($row);
    }
    //delete video resource to recycle bin
    public function delVideoRC()
    { 
        $id = $_POST['id'];
        $map['v_id'] = $id;
        $data['recycle'] = 0;
        $row = $this->VideoObj->where($map)->save($data);
        $this->ajaxReturn($row);
    }
    //modify video resource infomation
    public function editVideo()
    { 
        if(IS_POST){ 
            //judge upload link label
            if(!$_POST['lid']){ 
                $this->error('没有选择标签,请选择');
                exit();
            }
            $id = I('post.v_id');
            $map_video['v_id'] = $id;
            //do not uploads images
            if($_FILES['file_small']['error'] && $_FILES['file_big']['error']){ 
                //update video table
                $data_save['v_title'] = I('post.v_title');
                $data_save['v_describe'] = I('post.v_describe');
                $data_save['limit'] = I('post.limit');
                $data_save['v_type'] = I('post.v_type');
                $data_save['v_data1'] = I('post.v_data1');
                $data_save['v_data2'] = I('post.v_data2');
                $data_save['playnum'] = I('post.playnum');
                $data_save['v_cid'] = I('post.v_cid');
                $row_par = $this->VideoObj->where($map_video)->save($data_save);
                //updata video link label table
                //first delete old relative
                $map_vid_lab['vid'] = $id;
                $row_del = $this->Vid_LabObj
                ->where($map_vid_lab)
                ->delete();
                //insert new relative
                $label_arr = $_POST['lid'];
                foreach($label_arr as $key => $val){ 
                    $data_vid_lab['vid'] = $id;
                    $data_vid_lab['lid'] = $val;
                    $row_rel = $this->Vid_LabObj
                    ->add($data_vid_lab);
                }
                if($row_rel){ 
                    $this->success('修改成功',$_POST['url']);
                }else{ 
                    $this->error('修改失败');
                }
            //uploads small images
            }elseif(!$_FILES['file_small']['error'] && $_FILES['file_big']['error']){
                //modify small images file path
                $path = 'VideoImg';
                $file_info = $this->upload($path);
                $data_save['v_firstimgurl'] = $file_info['file_small']['savepath'].$file_info['file_small']['savename'];
                $data_save['v_title'] = I('post.v_title');
                $data_save['v_describe'] = I('post.v_describe');
                $data_save['limit'] = I('post.limit');
                $data_save['v_type'] = I('post.v_type');
                $data_save['v_data'] = I('post.v_data');
                $data_save['playnum'] = I('post.playnum');
                $data_save['v_cid'] = I('post.v_cid');
                $row_par = $this->VideoObj->where($map_video)->save($data_save);
                //updata video link label table
                //first delete old relative
                $map_vid_lab['vid'] = $id;
                $row_del = $this->Vid_LabObj
                ->where($map_vid_lab)
                ->delete();
                //insert new relative
                $label_arr = $_POST['lid'];
                foreach($label_arr as $key => $val){ 
                    $data_vid_lab['vid'] = $id;
                    $data_vid_lab['lid'] = $val;
                    $row_rel = $this->Vid_LabObj
                    ->add($data_vid_lab);
                }
                if($row_rel){ 
                    $this->success('修改成功',$_POST['url']);
                }else{ 
                    $this->error('修改失败');
                }
            //uploads big images
            }elseif($_FILES['file_small']['error'] && !$_FILES['file_big']['error']){
                $path = 'VideoImg';
                $file_info = $this->upload($path);
                $data_save['v_secondimgurl'] = $file_info['file_big']['savepath'].$file_info['file_big']['savename'];
                $data_save['v_title'] = I('post.v_title');
                $data_save['v_describe'] = I('post.v_describe');
                $data_save['limit'] = I('post.limit');
                $data_save['v_type'] = I('post.v_type');
                $data_save['v_data'] = I('post.v_data');
                $data_save['playnum'] = I('post.playnum');
                $data_save['v_cid'] = I('post.v_cid');
                $row_par = $this->VideoObj->where($map_video)->save($data_save);
                //updata video link label table
                //first delete old relative
                $map_vid_lab['vid'] = $id;
                $row_del = $this->Vid_LabObj
                ->where($map_vid_lab)
                ->delete();
                //insert new relative
                $label_arr = $_POST['lid'];
                foreach($label_arr as $key => $val){ 
                    $data_vid_lab['vid'] = $id;
                    $data_vid_lab['lid'] = $val;
                    $row_rel = $this->Vid_LabObj
                    ->add($data_vid_lab);
                }
                if($row_rel){ 
                    $this->success('修改成功',$_POST['url']);
                }else{ 
                    $this->error('修改失败');
                }
            //uploads small and bigimages
            }else{ 
                $path = 'VideoImg';
                $file_info = $this->upload($path);
                $data_save['v_firstimgurl'] = $file_info['file_small']['savepath'].$file_info['file_small']['savename'];
                $data_save['v_secondimgurl'] = $file_info['file_big']['savepath'].$file_info['file_big']['savename'];
                $data_save['v_title'] = I('post.v_title');
                $data_save['v_describe'] = I('post.v_describe');
                $data_save['limit'] = I('post.limit');
                $data_save['v_type'] = I('post.v_type');
                $data_save['v_data'] = I('post.v_data');
                $data_save['playnum'] = I('post.playnum');
                $data_save['v_cid'] = I('post.v_cid');
                $row_par = $this->VideoObj->where($map_video)->save($data_save);
                //updata video link label table
                //first delete old relative
                $map_vid_lab['vid'] = $id;
                $row_del = $this->Vid_LabObj
                ->where($map_vid_lab)
                ->delete();
                //insert new relative
                $label_arr = $_POST['lid'];
                foreach($label_arr as $key => $val){ 
                    $data_vid_lab['vid'] = $id;
                    $data_vid_lab['lid'] = $val;
                    $row_rel = $this->Vid_LabObj
                    ->add($data_vid_lab);
                }
                if($row_rel){ 
                    $this->success('修改成功',$_POST['url']);
                }else{ 
                    $this->error('修改失败');
                }
            }
        }else{ 
            //accept get method transmit video id
            $id = I('get.id');
            //select video detial infomition
            $map['v_id'] = $id;
            $video_info = $this->VideoObj
            ->field('v_id,v_cid,v_title,v_describe,v_type,v_data,v_firstimgurl,v_secondimgurl,limit,playnum')
            ->where($map)
            ->find();
            //select format recursion classList
            $classlist = $this->ClassObj
            ->where('c_status = 1')
            ->order('c_oid desc')
            ->field('c_id,c_fid,c_title')
            ->select();
             $classlist = $this->recursion($classlist,'c_id','c_fid','0');
            //select KVlabel
            $labelKV = $this->KVlabel();
            //father page
            $video_info['url'] = $_SERVER['HTTP_REFERER'];
            $this->assign('labelKV',$labelKV);
            $this->assign('classlist',$classlist);
            $this->assign('video_info',$video_info);
            $this->assign('fatherPage',$fatherPage);
            $this->display('video_edit');
        }
    }
    //---------------recycle bin----------------
    //display recycle bin videolist
    public function recycleList()
    { 
        $where['recycle'] = 0;
        $order = "v_addtime desc";
        $field = "v_id,v_cid,v_title,v_describe,v_type,v_data1,v_data2,v_firstimgurl,v_secondimgurl,limit,from_unixtime(v_addtime,'%Y-%m-%d %H:%i:%s')v_addtime,v_status,v_publisherid,playnum,recycle";
        $videoRClist = $this->AdminModelObj
        ->searchPage($this->VideoObj,$where,$field,$order,$limit,$join,10);
        $videoRClist = $this->matcheLable($videoRClist);
        $videoRClist = $this->matcheClass($videoRClist);
        $this->assign('videoRClist',$videoRClist);
        $this->display('video_list_recycle');
    }
    //video resource Remove recycle bin
    public function backRecycle()
    { 
        $id = $_POST['id'];
        $map['v_id'] = $id;
        $data['recycle'] = 1;
        $row = $this->VideoObj->where($map)->save($data);
        $this->ajaxReturn($row);
    }
    //recycle bin in again delete
    public function delRecycle()
    { 
        $id = $_POST['id'];
        //select video detial infomation
        $map['v_id'] = $id;
        $video_info = $this->VideoObj->where($map)->find();
        //delete image file from service
        //image file path
        $path_small = PATH_CARFIG_PREFIX.$video_info['v_firstimgurl'];
        $path_big = PATH_CARFIG_PREFIX.$video_info['v_secondimgurl'];
        unlink($path_small);
        unlink($path_big);
        //delete video link for label table 
        $map_vid_lab['vid'] = $id;
        $row = $this->Vid_LabObj
        ->where($map_vid_lab)
        ->delete();
        //delete video table
        $map_video['v_id'] = $id;
        $row = $this->VideoObj
        ->where($map_video)
        ->delete();
        $this->ajaxReturn($row);
    }
}