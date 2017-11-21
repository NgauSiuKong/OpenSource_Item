<?php
// +----------------------------------------------------------------------
// | Theme:轮播图管理操作类
// +----------------------------------------------------------------------
// | Contents：后台对轮播图的所有操作
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
namespace Backstage\Controller;

use Think\Controller;

class CarouselFigureController extends CommonController
{
    
    /**
     * 轮播图的查询展示
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function index()
    { 
        $order = "oid desc";
        $list = $this->AdminModelObj
        ->searchPage($this->CarouselFigureObj,0,0,0,0,0,8);
        //格式化时间
        $list = $this->AdminModelObj
        ->transTime($list);
        //格式化类型
        $list = $this->AdminModelObj
        ->transType($list);
        $this->assign('list',$list);
        $this->display('carousel_figure_list');
    }
    /**
     * 轮播图添加
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function add()
    { 
        if(IS_POST){ 
            //过滤输入字符
            if(!get_magic_quotes_gpc()){ 
                foreach($_POST as $key => $val){ 
                    $data[$key] = addslashes($val);
                    $data[$key] = htmlspecialchars($data[$key]);
                }
            }
            //处理图片路径
            $path = "CarouselFigure";
            $file_info = $this->upload($path);
            $data['path'] = $file_info['file']['savepath'].$file_info['file']['savename'];
            //抓取上传时间
            $data['addtime'] = time();
            //上传轮播图,获取id
            $id = $this->CarouselFigureObj->add($data);
            if($id){//轮播图上传成功，插入oid
                $data['oid'] = $id;
                $map['id'] = $id;
                $row = $this->CarouselFigureObj
                ->where($map)
                ->save($data);
                if($row){ 
                    $this->success('上传成功','index');
                }else{ 
                    $this->error('排序id上传失败');
                }
            }else{ 
                $this->error('轮播图上传失败');
            }
        }else{ 
            //没有post数组,加载输入页面
            $this->display('carousel_figure_add');
        }
    }
    /**
     * 轮播图添加
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function edit()
    { 
        if(IS_POST){
            if($_FILES['file']['error'] == 0){ 
                //上传了文件,新文件上传,旧文件删除
                dump($_POST);
                $path_old = PATH_CARFIG_PREFIX.$_POST['path'];
                $bool = unlink($path_old);
                //调用上传方法
                $path = "CarouselFigure";
                $file_info = $this->upload($path);
                $data['path'] = $file_info['file']['savepath'].$file_info['file']['savename'];
                $map['id'] = I('post.id');
                $data['title'] = I('post.title');
                $data['describe'] = I('post.describe');
                $data['type'] = I('post.type');
                $data['data'] = I('post.data');
                $row = $this->CarouselFigureObj
                ->where($map)
                ->save($data);
                if($row){ 
                    $this->success('修改成功','index');
                }else{ 
                    $this->error('修改失败');
                }
            }else{ 
                //没有上传文件,直接修改其他参数
                $map['id'] = I('post.id');
                $data['title'] = I('post.title');
                $data['describe'] = I('post.describe');
                $data['type'] = I('post.type');
                $data['data'] = I('post.data');
                $row = $this->CarouselFigureObj
                ->where($map)
                ->save($data);
                if($row){ 
                    $this->success('修改成功',$_POST['url']);
                }else{ 
                    $this->error('修改失败');
                }   
            }
        }else{ 
            //查询需要修改的轮播图原信息
            $map['id'] = $_GET['id']; 
            $list_old = $this->CarouselFigureObj
            ->field('id,title,describe,type,data,path')
            ->where($map)
            ->find();
            $list_old['url'] = $_SERVER['HTTP_REFERER'];
            $this->assign("list_old",$list_old);
            $this->display('carousel_figure_edit');
        }
    }
    /**
     * 轮播图排序
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function carfigSort()
    { 
        $this->sort($this->CarouselFigureObj);
    }
    /**
     * 修改状态
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function status()
    { 
        $id = $_POST['id'];
        $row = $this->AdminModelObj
        ->status($this->CarouselFigureObj,$id);
        echo $row;
    }
    /**
     * 删除
     * @author 牛少刚  <NgauSiuKong@gmail.com>
     */
    public function delete()
    { 
        $map['id'] = I('post.id');
        //查询轮播图路径,删除轮播图
        $path = $this->CarouselFigureObj
        ->where($map)
        ->field('path')
        ->find();
        $path = PATH_CARFIG_PREFIX.$path['path'];
        $bool = unlink($path);
        //--------end---图片已经删除--------
        $row = $this->AdminModelObj->delete($this->CarouselFigureObj,$map);
        $this->ajaxReturn($row);
    } 

}