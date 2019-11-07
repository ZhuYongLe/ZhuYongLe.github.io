<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    // 增加管理员
    public function addAdmin($data)
    {
        if (empty($data) || !is_array($data))  //$data为空或者$data不为数组时，返回false
        {
            return false;
        }

        if ($data['password'])  //密码不为空时，进行md5加密
        {
            $data['password'] = md5($data['password']);
        }

        $res = $this->save($data);  //执行 插入SQL操作，返回值为影响行数

        if ($res)  // 执行成功时，返回true
        {
            return true;
        }
        return false;
    }

    // 获取所有管理员信息
    public function getAdmin()
    {
        return $this->paginate(10);  //表示每页10条数据
    }

    // 获取一个管理员信息
    public function getOneAdmin($id)
    {        
        $res = $this->find($id);  //查找id为$id的一条数据
        
        return $res;
    }

    // 修改管理员信息
    public function editAdmin($oldData,$newData)
    {
        if (!$newData['password'])  //新密码为空时
        {
            $newData['password'] = $oldData['password'];  //即，不修改密码，保持原密码
        }
        else  //否则，对新密码进行md5加密
        {
            $newData['password'] = md5($newData['password']);
        }

        $res = $this->save(['name'=>$newData['name'],'password'=>$newData['password']],['id'=>$oldData['id']]);  //执行 更新SQL操作，返回值为影响条数(成功时)或者false(失败时)

        //操作执行时，返回值：0表示未修改，>0表示成功修改数据条数，false表示修改失败
        return $res;
    }

    // 删除一个管理员
    public function delAdmin($id)
    {
        $res = $this->destroy($id);

        if ($res)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    // 登录管理员
    public function loginAdmin($data)
    {
        $msg = $this->getByName($data['name']);  //获取 传进来的管理员姓名与数据库中一致的 一条管理员信息

        if ($msg)  //管理员姓名存在时
        {
            $password = $msg['password'];  //获取该条管理员信息内的密码

            if (md5($data['password']) == $password)  //管理员姓名存在的情况下，密码匹配
            {
                // 给session赋值
                session('id',$msg['id']);
                session('name',$msg['name']);
                return 1;  //登录成功
            }
            else
            {
                return 2;  //登录失败，密码错误
            }
        }
        else
        {
            return 3;  //登录失败，账号不存在
        }
    }

}
