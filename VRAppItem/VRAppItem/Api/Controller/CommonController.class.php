<?php
namespace Api\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | theme:vr_app Api Common Class
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
class CommonController extends Controller {
    public $CarouselFigureObj;
    public $ClassObj;
    public $LabelObj;
    public $Lab_ClaObj;
    public $Vid_LabObj;
    public $VideoObj;
    public $UserObj;
    public $UserDetailObj;
    public $FacilityObj;
    public $CommentObj;
    /**
     * 初始化
     */
    public function _initialize()
    { 
        $this->CarouselFigureObj = M('carouselfigure');
        $this->ClassObj = M('class');
        $this->LabelObj = M('label');
        $this->Lab_ClaObj = M('lab_cla');
        $this->VideoObj = M('video');
        $this->Vid_LabObj = M('vid_lab');
        $this->UserObj = M('user');
        $this->UserDetailObj = M('userdetail');
        $this->FacilityObj = M('facility');
        $this->CommentObj = M('comment');
    }
    //select class 
    public function classVideo()
    { 
        $where['c_status'] = 1;
        $where['c_fid'] = 1;
        $order = 'c_oid desc';
        $field = 'c_id as classId,c_title as classTitle,c_describe as classDsc';
        $classVideo = $this->ClassObj
        ->field($field)
        ->where($where)
        ->order($order)
        ->select();
        return $classVideo;
    }
    /**
     * the pagination
     * @param $id,the class id
     * @return  result for json
     **/
    public function pagination($p)
    { 
        $num1 = ($p-1)*20;
        $num2 = $p*20;
        $limit = $num1.','.$num2;
        return $limit;
    }
}