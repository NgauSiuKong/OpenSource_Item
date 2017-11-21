<?php
namespace Api\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | theme:vr_app home page api
// +----------------------------------------------------------------------
// | Describe:provide home page api
// +----------------------------------------------------------------------
class VideodetailController extends CommonController 
{
    protected $videoId;//the transmit video id,used to select video all infomation
    /**
     * provide the video all infomation to video detail page
     * @return $result json data
     */
    public function videoDetailPage()
    { 
        try{
        //accept the video id and assignment to $videoId
            $this->videoId = $_GET['id'];
            //video table infomation data
            $videoInfo = $this->selectVideo($this->videoId);
            //video publisher data
            $publisherInfo = $this->selectPublisher($this->videoId);
            //video first 20 comments
            $comments = $this->firComment($this->videoId);
            $videoDetail['result'] = $videoInfo;
            $videoDetail['result']['publisher'] = $publisherInfo;
            $videoDetail['comments'] = $comments;
        }catch(exception $e){ 
            //echo $e->getMessage();

        }
        echo "<pre>";
        print_r($videoDetail);
        //echo json_encode($videoDetail,256);
    }
    /**
     * select the video table and return video all infomation
     * @param $id the videoid
     * @return $result json data
     **/
    public function selectVideo($videoId)
    { 
        //select condition
        $where['v_id'] = $videoId;
        $field = 'v_id as id,v_cid as fid,v_title as title,v_subname as subname,v_describe as \'describe\',v_type as type,v_data1 as video1,v_data2 as video2,limit,from_unixtime(v_addtime,\'%Y-%m-%d\')addtime,playnum,v_firstimgurl as firstimgurl,v_secondimgurl as secondimgurl,duration,count(*)comments';
        $join = 'left join vr_comment vmm on vr_video.v_id = vmm.resourceid';
        $videoInfo = $this->VideoObj
        ->where($where)
        ->field($field)
        ->join($join)
        ->find();
        //select put in label infomation
        $videoInfo['labels'] = $this->selectLabel($this->videoId);
        return $videoInfo;
    }
    /**
     * select labels according to the video id;
     * @param $id the videoid
     * @return $result json data
     **/
    public function selectLabel($id)
    { 
        //select condition
        $where['vid'] = $id;
        $join = 'left join vr_label vl on vr_vid_lab.lid = vl.l_id';
        $field = 'vl.l_title as title,vl.l_id as id';
        $labelInfo = $this->Vid_LabObj
        ->field($field)
        ->where($where)
        ->join($join)
        ->select();
        return $labelInfo;
    }
    /**
     * select publisher infomation;
     * @param $id the videoid
     * @return $result json data
     **/
    public function selectPublisher($id)
    { 
        //select this video publisher
        $where_pub['v_id'] = $id;
        $field_pub = 'v_publisherid as publisherid'; 
        $publisherId = $this->VideoObj
        ->where($where_pub)
        ->field($field_pub)
        ->find();
        $publisherId = $publisherId['publisherid'];
        //select user search user infomation
        $where_user['id'] = $publisherId;
        $field_user = 'id,username,concat(\''.PATH_IMG.'\',vud.profileimg)photo,count(vuf.fans)fansnum';
        $join1 = 'left join vr_userdetail vud on vr_user.id = vud.hostid';
        $join2 = 'left join vr_userfans vuf on vr_user.id = vuf.user';
        $publisherInfo = $this->UserObj
        ->where($where_user)
        ->field($field_user)
        ->join($join1)
        ->join($join2)
        ->find();
        //put in pubvideos infomation
        $publisherInfo['pubvideos'] = $this->publisherVideo($publisherId);
        return $publisherInfo;
    }
    /**
     * select publisher video according to the video id;
     * @param $id the publisherid
     * @return $result json data
     **/
    public function publisherVideo($id)
    { 
        $publisherid = $id;
        $where['v_publisherid'] = $id;
        $where['status'] = 1;
        $field = 'concat(\''.PATH_IMG.'\',v_firstimgurl)firstImgUrl,concat(\''.PATH_IMG.'\',v_secondimgurl)secondImgUrl,v_title as title,v_describe as subname,v_id as id,playnum,from_unixtime(v_addtime,\'%Y-%m-%d\')addTime';
        $pubvideo = $this->VideoObj
        ->where($where)
        ->field($field)
        ->select();
        return $pubvideo;
    }
    /**
     * the video resource first page comments
     * @param $id the video resource id
     * @return $comments the comemnts list
     */
    public function firComment($id)
    { 
        $where['resourceid'] = $id;
        $limit = 20;
        $order = 'time desc';
        $field = 'id,userid,from_unixtime(time,\'%Y-%m-%d\')time,content,like';
        $commentlist = $this->CommentObj
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->field($field)
        ->select();
        for($i=0;$i<count($commentlist);$i++){ 
            $tmp = $this->selUserComment($commentlist[$i]['userid']);
            $commentlist[$i]['user'] = $tmp;
        }
        return $commentlist;
    }
    /**
     * according to comment select user infomation
     * @param $userid the comment userid
     * @return $comments the comemnts list
     */
    public function selUserComment($userid)
    { 
        $where['id'] = $userid;
        $field = 'id,username,vud.profileimg as photo';
        $join = 'left join vr_userdetail vud on vr_user.id = vud.hostid ';
        $selUserComment = $this->UserObj
        ->where($where)
        ->field($field)
        ->join($join)
        ->find();
        return $selUserComment;

        //select id,username,vud.profileimg as photo from vr_user vu left join vr_userdetail vud on vu.id=vud.hostid where id = 5;
    }
}


