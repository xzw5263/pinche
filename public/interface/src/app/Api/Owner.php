<?php
namespace App\Api;

use App\Domain\Owner as DomainOwner;
use App\Domain\Index as DomainIndex;
use App\Common\CheckAuth as CheckAuth;
use PhalApi\Api;

/**
 * 车主 接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Owner extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username', 'default' => 'PhalApi'),
            ),
            'publish'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'departure' =>array('name' => 'departure','type'=>'string','require'=>true,'desc'=>'出发地城市'),
            	'destination'=>array('name'=>'destination','type'=>'string','require'=>true,'desc'=>'目的地城市'),
            	'address1'=>array('name'=>'address1','type'=>'string','require'=>true,'desc'=>'出发地地址'),
            	'address2'=>array('name'=>'address2','type'=>'string','require'=>true,'desc'=>'目的地地址'),
            	'distance'=>array('name'=>'distance','type'=>'string','require'=>true,'desc'=>'距离'),
            	'lng'=>array('name'=>'lng','type'=>'string','require'=>true,'desc'=>'经度'),
            	'lat'=>array('name'=>'lat','type'=>'string','require'=>true,'desc'=>'纬度'),
            	'peoplenum'=>array('name'=>'peoplenum','type'=>'int','max'=>8,'require'=>true,'desc'=>'可载人数'),
            	'starttime'=>array('name'=>'starttime','type'=>'string','require'=>true,'desc'=>'出发时间'),
            	'remark'=>array('name'=>'remark','type'=>'string','desc'=>'备注'),
            	'price'=>array('name'=>'price','type'=>'float','require'=>false,'desc'=>'拼车价格')
            ),
            'mylists'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            ),
            'mydetail'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'车主发布id')
            ),
            'agree'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'乘客发布id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'司机发布id')
            ),
            'reject'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'乘客发布id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'司机发布id')
            ),
            'confirmserver'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'乘客发布id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'司机发布id'),
            ),
            'serverfinish'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'乘客发布id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'司机发布id'),
            ),
            'suggest'=>array(
            	'lng1'=>array('name'=>'lng1','type'=>'float','require'=>true,'desc'=>'出发地经度'),
            	'lat1'=>array('name'=>'lat1','type'=>'float','require'=>true,'desc'=>'出发地纬度'),
            	'lng2'=>array('name'=>'lng2','type'=>'float','require'=>true,'desc'=>'目的地经度'),
            	'lat2'=>array('name'=>'lat2','type'=>'float','require'=>true,'desc'=>'目的地纬度'),
            )

        );
	}
	
	/**
	 * 测试接口
     * @desc 测试接口是否正常返回内容
	 * @return string title 标题
	 * @return string content 内容
	 * @return string version 版本，格式：X.X.X
	 * @return int time 当前时间戳
	 */
	public function index() {
        return array(
            'title' => 'Hello ' . $this->username,
            'version' => PHALAPI_VERSION,
            'time' => $_SERVER['REQUEST_TIME'],
        );
	}

	/**
	 * 车主 发布行程
	 * @desc 车主 发布行程接口
	 * @return string state 1、成功，0、失败
	 */
	public function publish(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->publish($this->uid,$this->departure,$this->destination,$this->address1,$this->address2,$this->distance,$this->peoplenum,$this->starttime,$this->remark,$this->price,$this->lng,$this->lat);
	        if (!empty($result)) {
	            unset($result['re']);
	            $rs['code']=1;
	            $rs['msg']='发布成功！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='发布失败！';
	            $rs['info']= array();
	            return $rs;
	        }
        }
        
	}

	
	/**
	 *车主发布行程接口
	 * @desc 车主发布行程列表接口
	 * @return int pcid 发布行程id
	 * @return string state 返回状态（未接单/已接单/待服务……）
	 * @return string departure 出发地
	 * @return string address1 出发地地址
	 * @return string destination 目的地
	 * @return string address2 目的地地址
	 * @return string starttime 出发时间
	 * @return string num 剩余车位数
	 */
	public function mylists(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1 || $state==3){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->mylists($this->uid);
	        if (!empty($result)) {
	            $rs['code']=1;
	            $rs['msg']='获取成功！';
	            $rs['info']= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='暂无数据~';
	            $rs['info']= array();
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主发布行程详细接口
	 * @desc 车主发布行程详细接口
	 * @return int ppid 乘客发布行程id
	 * @return string state 返回状态（未接单/已接单/待服务……）
	 * @return string departure 出发地
	 * @return string address1 出发地地址
	 * @return string destination 目的地
	 * @return string address2 目的地地址
	 * @return string starttime 出发时间
	 * @return string num 剩余车位数
	 */
	public function mydetail(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1 || $state==3){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->mydetail($this->uid,$this->pcid);
	        if (!empty($result['list'])) {
	            $rs['code']=1;
	            $rs['msg']='获取成功！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='暂无报价';
	            $rs['info']= array();
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主 立即接单 接口
	 * @desc 车主 立即接单
	 * @return 返回状态  （接单成功:1/失败:0）
	 * @return 
	 */
	public function agree(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->agree($this->uid,$this->ppid,$this->pcid);
	        if (!empty($result)) {
	            unset($result['re']);
	            $rs['code']=1;
	            $rs['msg']='接单成功！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='接单失败！';
	            $rs['info']= array(0);
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主 拒绝接单 接口
	 * @desc 车主 拒绝接单
	 * @return 返回状态  （拒绝成功:1/失败:0）
	 * @return 
	 */
	public function reject(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->reject($this->uid,$this->ppid,$this->pcid);
	        if (!empty($result)) {
	            unset($result['re']);
	            $rs['code']=1;
	            $rs['msg']='您已拒绝！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='拒绝失败！';
	            $rs['info']= array(0);
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主 确认服务 接口
	 * @desc 车主 确认服务
	 * @return 返回状态  （确认服务成功:1/失败:0）
	 * @return 
	 */
	public function confirmserver(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->confirmserver($this->uid,$this->ppid,$this->pcid);
	        if (!empty($result)) {
	            unset($result['re']);
	            $rs['code']=1;
	            $rs['msg']='确认成功！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='确认失败！';
	            $rs['info']= array(0);
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主 服务完成 接口
	 * @desc 车主 服务完成
	 * @return 返回状态  （成功:1/失败:0）
	 * @return 
	 */
	public function serverfinish(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $model=new CheckAuth();
        $state=$model->check($this->uid);
        if($state==1){
        	//未认证
        	$rs['code']=0;
            $rs['msg']='您需要进行车主认证！';
            $rs['info']= array();
            return $rs;
        }else{
        	$domain = new DomainOwner();
	        $result = $domain->serverfinish($this->uid,$this->ppid,$this->pcid);
	        if (!empty($result)) {
	            unset($result['re']);
	            $rs['code']=1;
	            $rs['msg']='接单成功！';
	            $rs['info'][]= $result;
	            return $rs;
	        }else{
	            $rs['code']=0;
	            $rs['msg']='接单失败！';
	            $rs['info']= array(0);
	            return $rs;
	        }
        }
        
	}

	/**
	 * 车主 建议价格 接口
	 * @desc 车主 建议价格（车主与乘客通用）
	 * @return 返回内容
	 * @return string price 建议价格
	 */
	public function suggest(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
    	$domain = new DomainIndex();
        $result = $domain->suggest($this->lng1,$this->lat1,$this->lng2,$this->lat2);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='获取信息成功';
            $rs['info']= $result->result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='获取失败';
            $rs['info']= array(0);
            return $rs;
        }
	}
}
