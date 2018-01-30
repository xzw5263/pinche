<?php
namespace App\Model;

use App\Model\Publish as ModelPublish;
use App\Model\Passenger as ModelPassenger;
use PhalApi\Model\NotORMModel as NotORM;
class Choose extends NotORM {

    protected function getTableName($id) {
        return 'tzy_choose';
    }

    public function insertorder($data){
        return $this->getORM()->insert($data);
    }

    public function myslists($uid,$ppid){
    	$sql="SELECT
				a.id,avatar,nick,sex,credit,licence,cartype,color,departure,destination,address1,address2,starttime,peoplenum,realnum,remark,price
			FROM
				pc_tzy_choose a
			LEFT JOIN pc_tzy_carpublish b ON a.pcid = b.id
			LEFT JOIN pc_tzy_user c ON c.id = b.uid
			LEFT JOIN pc_tzy_authentication d on d.uid = c.id
			where a.ppid= ?";
			$param=array($ppid);
			return $this->getORM()->queryAll($sql,$param);
    }

    public function mydetail($uid,$pid){
    	$sql="SELECT
				nick,sex,mobile,credit,avatar,departure,destination,address1,address2,starttime,num,remark,price
			FROM
				pc_tzy_choose a
			LEFT JOIN pc_tzy_passengerpub b ON a.ppid = b.id
			LEFT JOIN pc_tzy_user c on c.id = b.uid
			WHERE
				a.pcid = ?";
		$param=array($pid);
		return $this->getORM()->queryAll($sql,$param);
    }

    public function agree($uid,$ppid,$pcid){
        return $this->getORM()->where('ppid',$pid)->and('pcid',$pcid)->update(array('state'=>2));
    }

    public function reject($uid,$ppid,$pcid){
        $data=array(
            'state'=>3
        );
        //司机座位数量还原，
        $models=new ModelPassenger();
        //$info=$this->getORM()->where('id',$pid)->select('pid,num')->fetchOne();
        $num = $models->getcarnum($ppid);
        
        $re = $this->getORM()->where('ppid = ? and pcid = ?',$ppid,$pcid)->update($data);
        if($re){
        	$model = new ModelPublish();
        	$model->resotrenum($pcid,$num);
        }
        return $re;
    }

    public function confirmserver($uid,$ppid,$pcid){
        return $this->getORM()->where('ppid = ? and pcid = ?',$ppid,$pcid)->update(array('state'=>4));
    }

    public function serverfinish($uid,$ppid,$pcid){
        return $this->getORM()->where('ppid = ? and pcid = ?',$ppid,$pcid)->update(array('state'=>5));
    }
}
