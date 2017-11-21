<?php
namespace Backstage\Model;
use Think\Model;
class AdminModel extends Model
{
    //查询分页搜索
    public function searchPage($obj,$where,$field,$order,$limit,$join,$page)
    {   
        $count = $obj->where($where)->count();
        $page = new \Think\Page($count,$page);
        $show = $page->show();
        $selArr = $obj
        ->where($where)
        ->field($field)
        ->order($order)
        ->join($join)
        ->limit($page->firstRow.",".$page->listRows)
        ->select();
        $listArr['count'] = $count;
        $listArr['show'] = $show;
        $listArr['selArr'] = $selArr;

        return $listArr;
    }
    //格式化后台时间
    //数组格式按照本类的searchPage查出的格式输入
    public function transTime($arr)
    { 
        for($i=0;$i<count($arr['selArr']);$i++){ 
            $arr['selArr'][$i]['addtime'] = date('Y/m/d H:i:s',$arr['selArr'][$i]['addtime']);
        }
        return $arr;
    }
    //格式化分类,输入显示数组
    public function transClass($arr){ 
        for($i=0;$i<count($arr['selArr']);$i++){ 
            if($arr['selArr'][$i]['fid'] == 0){ 
                $arr['selArr'][$i]['fid'] = '顶级分类';
            }
        }
        return $arr;
    }

    //链接类型,0链接网址,1链接视频
    //数组格式按照本类的searchPage查出的格式输入
    public function transType($arr)
    { 
        for($i=0;$i<count($arr['selArr']);$i++){ 
            if($arr['selArr'][$i]['type'] == 0){ 
                $arr['selArr'][$i]['type'] = '链接网址';
            }else{ 
                $arr['selArr'][$i]['type'] = '链接视频';
            }
        }
        return $arr;
    }
    //修改信息的状态,发布或者禁用
    public function status($obj,$id,$profix){ 
        $map[$profix.'id'] = $id;
        $status = $obj
        ->where($map)
        ->field($profix.'status')
        ->find();
        $status = $status[$profix.'status'];
        if($status){ 
            //如果是启用状态,禁用
            $data[$profix.'status'] = 0;
            $row = $obj 
            ->where($map)
            ->save($data);
            return $row;
        }else{ 
            //如果是禁用状态,则启用
            $data[$profix.'status'] = 1;
            $row = $obj
            ->where($map)
            ->save($data);
            return $row;
        }
    }
}