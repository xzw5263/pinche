<?php
namespace App\Domain;

use App\Model\Passenger as ModelPassenger;
use App\Model\Publish as ModelPublish;
use App\Model\Choose as ModelChoose;
class Passenger {
    public function publish($uid,$departure,$destination,$address1,$address2,$num,$remark,$price,$lng,$lat) {
        $model = new ModelPassenger();
        $result = $model->publish($uid,$pid,$departure,$destination,$address1,$address2,$num,$remark,$price,$lng,$lat);
        return $result;
    }

    public function recommend($uid,$departure,$destination){
        $model= new ModelPublish();
        $result=$model->passengerlists($uid,$departure,$destination);
        return $result;
    }

    public function mydetail($uid,$pid){
        $model= new ModelPassenger();
        $result=$model->mydetail($uid,$pid);
        return $result;
    }

    public function mylists($uid,$ppid){
        $model = new ModelChoose();
        $lists = $model->myslists($uid,$ppid);

        $models= new ModelPassenger();
        $info=$models->checkmyslists($uid,$pid);
        $data['info']=$info;
        $data['lists']=$lists;
        return $data;
    }

    public function choose($uid,$ppid,$pcid){
        //先判断司机的空位；
        $model= new ModelPublish();
        $num = $model->checknum($pcid);
        if($num < 1){
            $code = 0;
            return $code;
        }
        $models= new ModelPassenger();
        $result=$models->choose($uid,$ppid,$pcid,$num);
        return $result;
    }

    public function cancelorder($uid,$pid,$remark){
        $models= new ModelPassenger();
        $result=$models->cancelorder($uid,$pid,$remark);
        return $result;
    }

     public function canceloffer($uid,$pid){
        $models= new ModelPassenger();
        $result=$models->canceloffer($uid,$pid);
        return $result;
    }
}
