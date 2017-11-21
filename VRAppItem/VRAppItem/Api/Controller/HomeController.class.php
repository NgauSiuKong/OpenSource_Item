<?php
namespace Api\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | theme:vr_app home page api
// +----------------------------------------------------------------------
// | Describe:provide home page api
// +----------------------------------------------------------------------
class HomeController extends CommonController {
    /**
     * provide item home page api
     * @return $result json data
     */
    public function home()
    {   
        $result['banners'] = $this->carouselFigure();
        $result['categories'] = $this->categories();
        echo "<pre>";
        print_r($result);
        echo json_encode($result,256);
    }
    /**
     * provide carousel figure to home page
     * @return $carousel_figure carousel Figure array 
     */
    public function carouselFigure()
    { 
        //select condition
        $where = 'status = 1';
        $field = "id,title,data,concat('".PATH_IMG."',path)path,from_unixtime(addtime,'%Y-%m-%d')addtime,describe,type";
        $order = 'oid desc';
        //select the mysql
        $carsousel_figure = $this->CarouselFigureObj
        ->where($where)
        ->field($field)
        ->order($order)
        ->select();
        return $carsousel_figure;
    }
    /**
     * provide home page class and video infomation
     * @return $categories
     */
    public function categories()
    { 
        //-------------select class sorted--------------- 
        $class_video = $this->classVideo();
        for($i=0;$i<count($class_video);$i++){ 
            //select top video resource
            $map_top['v_cid'] = $class_video[$i]['classid'];
            $map_top['top'] = 1;
            $field_top = 'concat(\''.PATH_IMG.'\',v_firstimgurl)firstImgUrl,concat(\''.PATH_IMG.'\',v_secondimgurl)secondImgUrl,v_title as title,v_describe as subname,v_id as id,playnum,from_unixtime(v_addtime,\'%Y-%m-%d\')addTime,count(*)comments';
            $join = 'left join vr_comment vmm on vr_video.v_id = vmm.resourceid';
            $class_video[$i]['topVideo'] = $this->VideoObj
            ->field($field_top)
            ->where($map_top)
            ->join($join)
            ->find();
            //select top video resource end
            //select list video resource
            $map_list['v_cid'] = $class_video[$i]['classid'];
            $map_list['top'] = 0;
            $field = 'concat(\''.PATH_IMG.'\',v_firstimgurl)firstImgUrl,concat(\''.PATH_IMG.'\',v_secondimgurl)secondImgUrl,v_title as title,v_describe as subname,v_id as id,playnum,from_unixtime(v_addtime,\'%Y-%m-%d\')addTime,count(*)comments';
            $join = "left join vr_comment vmm on vr_video.v_id = vmm.resourceid";
            $class_video[$i]['videos'] = $this->VideoObj
            ->where($map_list)
            ->field($field_top)
            ->join($join)
            ->select();
            //select top video resource end
        }
        $categories = $class_video;
        return $categories;
    }
    
}