<?php
namespace App\Domain;

use App\Model\Publish as ModelPublish;
use App\Model\Passenger as ModelPassenger;
use App\Model\Choose as ModelChoose;
class Owner {
    public function publish($uid,$departure,$destination,$address1,$address2,$distance,$peoplenum,$starttime,$remark,$price,$lng,$lat) {
        $model = new ModelPublish();
        $result = $model->publish($uid,$departure,$destination,$address1,$address2,$distance,$peoplenum,$starttime,$remark,$price,$lng,$lat);
        return $result;
    }

    public function mylists($uid){
        $model= new ModelPublish();
        $result=$model->mylists($uid);
        return $result;
    }

    public function mydetail($uid,$pcid){
        // $model= new ModelPassenger();
        // $result=$model->mydetail($uid,$pid);
        // return $result;
        $models = new ModelPublish();
        $info = $models->checkpublish($uid,$pcid);
        $model = new ModelChoose();
        $list = $model->mydetail($uid,$pcid);
        $data['info']=$info;
        $data['list']=$list;
        return $data;
    }

    public function agree($uid,$ppid,$pcid){
        // $model= new ModelPassenger();
        $model = new ModelChoose();
        $result=$model->agree($uid,$ppid,$pcid);
        return $result;
    }

    public function reject($uid,$ppid,$pcid){
        // $model= new ModelPassenger();
        $model = new ModelChoose();
        $result=$model->reject($uid,$ppid,$pcid);
        return $result;
    }

    public function confirmserver($uid,$ppid,$pcid){
        // $model= new ModelPassenger();
        $model = new ModelChoose();
        $result=$model->confirmserver($uid,$ppid,$pcid);
        return $result;
    }

    public function serverfinish($uid,$ppid,$pcid){
        // $model= new ModelPassenger();
        $model = new ModelChoose();
        $result=$model->serverfinish($uid,$ppid,$pcid);
        return $result;
    }
}
