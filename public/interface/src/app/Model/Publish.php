<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
class Publish extends NotORM {

    protected function getTableName($id) {
        return 'tzy_carpublish';
    }

    public function publish($uid,$departure,$destination,$address1,$address2,$distance,$peoplenum,$starttime,$remark,$price,$lng,$lat){
            $data=array(
                'departure'=>$departure,
                'destination'=>$destination,
                'address1'=>$address1,
                'address2'=>$address2,
                'distance'=>$distance,
                'peoplenum'=>$peoplenum,
                'starttime'=>$starttime,
                'remark'=>$remark,
                'price'=>$price,
                'realnum'=>$peoplenum,
                'create_time'=>date('Y-m-d H:i:s',time()),
                'lng'=>$lng,
                'lat'=>$lat
            );
            $re = $this->getORM()->insert($data);
            return true;
    }

    public function mylists($uid){
        $list=$this->getORM()->where('uid',$uid)->select('id pcid,departure,destination,address2,address1,starttime,realnum')->fetchAll();
        return $list;
    }

    public function passengerlists($uid,$departure,$destination){
        $sql="SELECT
                a.id ppid,
                departure,
                destination,
                address1,
                address2,
                peoplenum,
                starttime,
                price,
                realnum,
                nick,
                sex,
                mobile,
                avatar
            FROM
                pc_tzy_carpublish a
            LEFT JOIN pc_tzy_user b ON a.uid = b.id
            LEFT JOIN pc_tzy_authentication c on c.uid = b.id and c.is_default=1
            WHERE
                a.departure = ?
            AND a.destination = ?";
        $list=$this->getORM()->queryAll($sql,array($departure,$destination));
        return $list;  
    }

    public function checknum($pid){
        $num = $this->getORM()->where('id',$pid)->select('realnum')->fetchOne();
        return $num['realnum'];
    }

    public function reducenum($pid,$num){
        //$info = $this->getORM()->where('id',$pid)->select('realnum')->fetchOne();
        //$data['realnum']=$info['realnum']-$num;
        //return $this->getORM()->where('id',$pid)->update($data);
        return $this->getORM()->where('id', $pid)->update(array('realnum' => new \NotORM_Literal("realnum - ".$num)));
    }

    public function resotrenum($pid,$num){
        return $this->getORM()->where('id', $pid)->update(array('realnum' => new \NotORM_Literal("realnum + ".$num)));
    }

    public function checkpublish($uid,$pcid){
        return $this->getORM()->where('id',$pcid)->select('distance,peoplenum,price')->fetchOne();
    }
}
