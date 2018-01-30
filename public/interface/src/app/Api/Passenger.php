<?php
namespace App\Api;

use App\Domain\Passenger as DomainPassenger;
use PhalApi\Api;

/**
 * 乘客 接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Passenger extends Api {

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
            	'lng'=>array('name'=>'lng','type'=>'string','require'=>true,'desc'=>'经度'),
            	'lat'=>array('name'=>'lat','type'=>'string','require'=>true,'desc'=>'纬度'),
            	'num'=>array('name'=>'num','type'=>'int','max'=>8,'require'=>true,'desc'=>'人数'),
            	'remark'=>array('name'=>'remark','type'=>'string','desc'=>'备注'),
            	'price'=>array('name'=>'price','type'=>'float','require'=>false,'desc'=>'拼车价格')
            ),
            'recommend'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'departure'=>array('name' => 'departure','type'=>'string','require'=>true,'desc'=>'出发地城市'),
            	'destination'=>array('name'=>'destination','type'=>'string','require'=>true,'desc'=>'目的地城市')
            ),
            'mylists'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'用户发布id'),
            ),
            'choose'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'ppid'=>array('name'=>'ppid','type'=>'int','require'=>true,'desc'=>'用户发布id'),
            	'pcid'=>array('name'=>'pcid','type'=>'int','require'=>true,'desc'=>'车主发布id')
            ),
            'cancelorder'=>array(
            	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
            	'pid'=>array('name'=>'pid','type'=>'int','require'=>true,'desc'=>'用户发布id'),
            	'remark'=>array('name'=>'remark','type'=>'string','desc'=>'取消行程原因~')
            ),
            'canceloffer'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'pid'=>array('name'=>'pid','type'=>'int','require'=>true,'desc'=>'用户发布id'),
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
	 * 乘客 我的发布
	 * @desc 乘客 发布需求
	 * @return 返回成功或者失败
	 * @return 
	 */
	public function publish(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
    	$domain = new DomainPassenger();
        $result = $domain->publish($this->uid,$this->departure,$this->destination,$this->address1,$this->address2,$this->num,$this->remark,$this->price,$this->lng,$this->lat);
        if (!empty($result)) {
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

	/**
	 * 乘客 选择车主 接口
     * @desc 乘客 发布行程后 进入到的列表界面（推荐车主）
	 * @return int ppid  车主发布id
	 * @return string nick 昵称
	 * @return int sex 性别
	 * @return double credit 信用
	 * @return string licence 车牌号
	 * @return string cartype 车类型
	 * @return string avatar 头像
	 * @return string depature 出发城市
	 * @return string destination 目的城市
	 * @return string address1 出发城市地址
	 * @return string address2 目的城市地址
	 * @return string starttime 出发时间
	 * @return string peoplenum 座位数量
	 * @return string realnum 剩余数量
	 * @return string price 价格
	 */
	public function recommend() {
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
    	$domain = new DomainPassenger();
        $result = $domain->recommend($this->uid,$this->departure,$this->destination);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='获取成功！';
            $rs['info']= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='暂时没有该行程车辆信息！';
            $rs['info']= array();
            return $rs;
        }
	}

    /**
     * 乘客 我的行程
     * @desc 乘客 查看我选择的司机
     * @return int pid  车主发布id
     * @return string nick 昵称
     * @return int sex 性别
     * @return double credit 信用
     * @return string licence 车牌号
     * @return string cartype 车类型
     * @return string avatar 头像
     * @return string depature 出发城市
     * @return string destination 目的城市
     * @return string address1 出发城市地址
     * @return string address2 目的城市地址
     * @return string starttime 出发时间
     * @return string peoplenum 座位数量
     * @return string realnum 剩余数量
     * @return string price 价格
     */
    public function mylists() {
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
        $domain = new DomainPassenger();
        $result = $domain->mylists($this->uid,$this->ppid);
        if (!empty($result['lists'])) {
            $rs['code']=1;
            $rs['msg']='获取成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='暂时没有该行程车辆信息！';
            $rs['info']= array();
            return $rs;
        }
    }

	/**
	 * 乘客 选他
	 * @desc 乘客 选他
	 * @return 返回成功或者失败
	 */
	public function choose(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
    	$domain = new DomainPassenger();
        $result = $domain->choose($this->uid,$this->ppid,$this->pcid);
        if ($result == 1) {
            $rs['code']=1;
            $rs['msg']='申请成功，请等待司机回应~';
            $rs['info'][]= $result;
            return $rs;
        }else if($result == 0){
            $rs['code']=0;
            $rs['msg']='很抱歉，座位数量不足！';
            $rs['info']= array();
            return $rs;
        }else{
        	$rs['code']=0;
            $rs['msg']='申请失败，请重新选择';
            $rs['info']= array();
            return $rs;
        }
	}

	/**
	 * 乘客 取消订单
	 * @desc 乘客 取消订单
	 * @return 返回成功或者失败
	 * @return 
	 */
	public function cancelorder(){
		$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
    	$domain = new DomainPassenger();
        $result = $domain->cancelorder($this->uid,$this->pid,$this->remark);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='取消成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
        	$rs['code']=0;
            $rs['msg']='取消失败';
            $rs['info']= array();
            return $rs;
        }
	}

    /**
     * 乘客 取消报价
     * @desc 乘客 取消报价
     * @return 返回成功或者失败
     * @return 
     */
    public function canceloffer(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        
        $domain = new DomainPassenger();
        $result = $domain->canceloffer($this->uid,$this->pid);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='取消成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='取消失败';
            $rs['info']= array();
            return $rs;
        }
    }
}
