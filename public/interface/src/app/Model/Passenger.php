<?php
namespace App\Model;

use App\Model\Publish as ModelPublish;
use App\Model\Choose as ModelChoose;
use PhalApi\Model\NotORMModel as NotORM;
class Passenger extends NotORM {

    protected function getTableName($id) {
        return 'tzy_passengerpub';
    }

    public function mydetail($uid,$pid){
        //return $this->getORM()->where('pid',$pid)->select('id,departure,destination,address1,address2,num,remark,price')->fetchAll();
        $sql="SELECT
                a.id ppid,
                departure,
                destination,
                address1,
                address2,
                num,
                remark,
                price,
                nick,
                mobile,
                avatar,
                sex,
                credit
            FROM
                pc_tzy_passengerpub a
            LEFT JOIN pc_tzy_user b ON a.uid = b.id
            WHERE
                a.pid = :pid";
            $rows = $this->getORM()->queryAll($sql, array(':pid'=>$pid));
            return $rows;
    }

    public function publish($uid,$departure,$destination,$address1,$address2,$num,$remark,$price,$lng,$lat){
        $data=array(
            'uid'=>$uid,
            'departure'=>$departure,
            'destination'=>$destination,
            'address1'=>$address1,
            'address2'=>$address2,
            'num'=>$num,
            'remark'=>$remark,
            'price'=>$price,
            'lng'=>$lng,
            'lat'=>$lat,
            'create_time'=>date('Y-m-d H:i:s',time())
        );
        return $this->getORM()->insert($data);
    }

    public function checkmyslists($uid,$pid){
        return $this->getORM()->where('id',$pid)->select('distance,num,remark')->fetchOne();
    }

    public function agree($uid,$pid){
        $data=array(
            'state'=>2
        );
        return $this->getORM()->where('id',$pid)->update($data);
    }
    
    public function reject($uid,$pid){
        $data=array(
            'state'=>3
        );
        //司机座位数量还原，
        $info=$this->getORM()->where('id',$pid)->select('pid,num')->fetchOne();
        $model = new ModelPublish();
        $model->resotrenum($info['pid'],$info['num']);
        return $this->getORM()->where('id',$pid)->update($data);
    }

    public function confirmserver($uid,$pid){
        $data=array(
            'state'=>4
        );
        return $this->getORM()->where('id',$pid)->update($data);
    }

    public function serverfinish($uid,$pid){
        $data=array(
            'state'=>5
        );
        return $this->getORM()->where('id',$pid)->update($data);
    }

    public function choose($uid,$pid,$cpid,$num){
        //判断座位是否够
        $info=$this->getORM()->where('id',$pid)->fetchOne();
        if($info['num'] > $num){
            $code = 0;
            return $code;
        }else{
            $data['pcid']=$cpid;
            $data['ppid']=$pid;
            $data['state'] = '1';
            $data['create_time']=date('Y-m-d H:i:s',time());
            $models=new ModelChoose();
            $re = $models->insertorder($data);
            //$re = $this->getORM()->where('id',$pid)->update($data);
            if($re){
                //减少司机座位数量；
                $model = new ModelPublish();
                $model->reducenum($cpid,$info['num']);
                $code = 1;
            }else{
                $code = 2;
            }
        }
        return $code;
    }

    public function cancelorder($uid,$pid,$remark){
        $data=array(
            'pid'=>0,
            'state'=>'0'
        );
        //新增一条数据（取消订单原因）;
        $sql="insert into pc_tzy_cancelorder(`uid`,`create_time`,`remark`) VALUE(?,?,?)";
        $time=date('Y-m-d H:i:s',time());
        $param=array($uid,$time,$remark);
        $this->getORM()->queryAll($sql,$param);
        return $this->getORM()->where('id',$pid)->update($data);
    }

    public function canceloffer($uid,$pid){
        return $this->getORM()->where('id',$pid)->delete();
    }

    public function getcarnum($pid){
        $num=$this->getORM()->where('id',$pid)->select('num')->fetchOne();
        return $num['num'];
    }
}
