<?php
namespace Api\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | theme:vr_app video resource list
// +----------------------------------------------------------------------
// | Describe:provide list page for every class
// +----------------------------------------------------------------------
class ListController extends CommonController 
{
    /**
     * provide item home page api
     * @return  result for json
     */
    public function classHomePage()
    { 
        //classid,pagenum,labelid,order  最新长传,最多评论,最多播放
        $id = $_GET['classID'];
        $p = $_GET['p']?$_GET['p']:1;
        $claLabInfo = $this->claLabInfo($id);
        $videoInfo = $this->claVidInfo($id,$p);
        $result = $claLabInfo;
        $result['videos'] = $videoInfo;
        dump($result);
        echo json_encode($result,256);
    }
    /**
     * select class infomation and link label
     * @param $id,the class id
     * @return  result for json
     */
    public function claLabInfo($id)
    { 
        //select the class basic infomation
        $map_class['c_id'] = $id;
        $field = "c_id as id,c_title as title,c_describe as 'describe'";
        $classInfo = $this->ClassObj
        ->where($map_class)
        ->field($field)
        ->find();
        //select class and label link
        $map_cla_lab['cid'] = $id;
        $field = "vlb.l_id as id,vlb.l_title as title";
        $join = "left join vr_label vlb on vr_lab_cla.lid = vlb.l_id";
        $cla_labInfo = $this->Lab_ClaObj
        ->where($map_cla_lab)
        ->field($field)
        ->join($join)
        ->select();
        $claLabInfo = $classInfo;
        $claLabInfo['labels'] = $cla_labInfo;
        return $claLabInfo;
    }
    /**
     * select the video resorce belong to this class
     * @param $id,the class id
     * @param $p,the transmit page code
     * @return  result for json
     */
    public function claVidInfo($id,$p)
    { 
        $where['v_cid'] = $id;
        $field = "v_id as id,v_title as title,concat('".PATH_IMG."',v_secondimgurl)imgurl,v_subname as subname,playnum";
        $order = "playnum desc";
        $limit = $this->pagination($p);
        $videoInfo = $this->VideoObj
        ->where($where)
        ->order($order)
        ->field($field)
        ->select();
        return $videoInfo;
    }
    
}