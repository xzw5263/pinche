<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
class Notice extends NotORM {

    protected function getTableName($id) {
        return 'tzy_notice';
    }

    public function getnotice($uid){
        return $this->getORM()->where('uid',$uid)->fetchAll();
    }
}
