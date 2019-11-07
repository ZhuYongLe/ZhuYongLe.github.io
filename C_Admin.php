<?php
namespace app\admin\controller;

use app\admin\controller\WAF;
use app\admin\model\Admin as modelAdmin;  //给model下的Admin类 起别名为modelAdmin

class Admin extends WAF
{
    // 展示管理员列表
    public function lst()
    {
        $admin = new modelAdmin();  //创建model下的Admin类的对象

        $data = $admin->getAdmin();

        $this->assign('adminRes',$data);

        return $this->fetch();
    }

    // 增加管理员
    public function add()
    {
        if (request()->isPost())
        {
            $admin = new modelAdmin();

            $data = input('post.');  //获取POST方式提交的变量

            $res = $admin->addAdmin($data);  //添加管理员

            if ($res)
            {
                $this->success('添加管理员成功', url('lst'));  //添加成功，跳转到本控制器下lst页面
            }
            else
            {
                $this->error('添加管理员失败');  //添加失败，会自动跳转上一个页面
            }
        }
    	return $this->fetch();
    }

    // 编辑管理员信息
    public function edit($id)
    {
        $admin = new modelAdmin();

        $oldData = $admin->getOneAdmin($id);  //用get提交的id进行查找 返回一个管理员信息

        $this->assign('adminRes',$oldData);  //把包含$oldData的信息以变量adminMag传送给前台

        if (request()->isPost())  //当数据提交方式为post时
        {
            $newData = input('post.');  //获取post提交的数据

            if (!$newData['name'])  //新姓名为空时
            {
                $this->error('管理员姓名不得为空');
            }

            $res = $admin->editAdmin($oldData,$newData);  //修改管理员信息

            if ($res)
            {
                $this->success('修改成功',url('lst'));
            }
            else 
            {
                if ($res == 0)
                {
                    $this->success('修改成功，密码不变',url('lst'));
                }
                else
                {
                    $this->error('修改失败');
                }
            }
        }
    	return $this->fetch();
    }
    
    // 删除管理员
    public function del($id)
    {
        $admin = new modelAdmin();

        $res = $admin->delAdmin($id);

        if ($res)
        {
            $this->success('删除成功',url('lst'));
        }
        else
        {
            $this->error('删除失败');
        }
    }

    // 管理员退出登录
    public function logout()
    {
        session(null);
        $this->success('退出系统成功',url('Login/index'));
    }
}
