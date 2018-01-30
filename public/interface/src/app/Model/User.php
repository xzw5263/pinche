<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
class User extends NotORM {

    protected function getTableName($id) {
        return 'tzy_user';
    }

    public function getListItems($state, $page, $perpage) {
        return $this->getORM()
            ->select('*')
            ->where('state', $state)
            ->order('post_date DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }

    public function register($mobile,$password){
        $res=array();
        $re=$this->getORM()->where('mobile',$mobile)->fetchOne();
        if(!$re){
            $data['mobile']=$mobile;
            $data['password']=md5(md5($password));
            $data['create_time']=date('Y:m:d H:i:s',time());
            $data['token']=md5(time());
            $data['state']=1;
            $res = $this->getORM()->insert($data);
            if($res){
                unset($res['password']);
                $res['code']=1;
            }else{
                $res['code']=0;
            }
        }else{
            $res['code'] = 2;
        }
        return $res;
    }

    public function login($mobile,$capthca) {
        //$total = $this->getORM()  ->where('state', $state) ->count('id');
        $re=$this->getORM()->where('mobile',$mobile)->fetchOne();
        if($re){
            session_start();
            $rand=$_SESSION['mobile'];
            if($capthca != $rand){
                $data['code']=1;
                $data['info']=array();
            }else{
                $data['code']=0;
                $data['info']=$re;
            }
        }else{
            //未注册
            $data['code']=2;
            $data['info']=array();
        } 
        return $data;       
    }

    public function logins($mobile,$pass) {
        //$total = $this->getORM()  ->where('state', $state) ->count('id');
        $info=$this->getORM()->where('mobile',$mobile)->fetchOne();
        if($info){
            $password=md5(md5($pass));
            if($password != $info['password']){
                $data['code']=1;
                $data['info']=array();
            }else{
                unset($info['password']);
                $data['code']=0;
                $data['info']=$info;
            }
        }else{
            //未注册
            $data['code']=2;
            $data['info']=array();
        } 
        return $data;       
    }

    public function checkAuth($uid){
        $info=$this->getORM()->where('id',$uid)->select('state')->fetchOne();
        return $info['state'];
    }

    public function editavatar($uid,$avatar){
        $data['avatar']=$avatar;
        return $this->getORM()->where('id',$uid)->update($data);
    }

    public function editnick($uid,$nick){
        $data['nick']=$nick;
        return $this->getORM()->where('id',$uid)->update($data);
    }

    public function editsex($uid,$sex){
        $data['sex']=$sex;
        return $this->getORM()->where('id',$uid)->update($data);
    }

    public function editpass($uid,$oldpass,$newpass){
        $old=md5(md5($oldpass));
        $new=md5(md5($newpass));
        $info=$this->getORM()->where('id',$uid)->select('password')->fetchOne();
        if($info['password'] != $old){
            return $code = 2;
        }else{
            return $this->getORM()->where('id',$uid)->update(array('password'=>$new));
        }
    }

    public function changemobile($uid,$mobile,$capthca){
        session_start();
        $rand=$_SESSION['change'];
        if($rand != $capthca){
            return $code=2;
        }else{
            $re= $this->getORM()->where('mobile',$mobile)->fetchOne();
            if($re){
                return $code = 3;
            }else{
                $data['mobile']=$mobile;
                return $this->getORM()->where('id',$uid)->update($data);
            }
        }
    }
}
