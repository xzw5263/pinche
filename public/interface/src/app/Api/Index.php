<?php
namespace App\Api;

use App\Domain\Index as DomainIndex;
use PhalApi\Api;

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Index extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username', 'default' => 'PhalApi'),
            ),
            'mobilelogin' =>array(
            	'mobile'=>array('name'=>'mobile','type'=>'string','max'=>11,'min'=>11,'require'=>true,'desc'=>'手机号'),
            	'captcha'=>array('name'=>'captcha','type'=>'string','max'=>'6','min'=>'6','require'=>true,'desc'=>'验证码')
            ),
             'getVerifyCode' => array(
                'mobile' 	=> array('name' => 'mobile', 'type' => 'string', 'require'=>true,'min'=>'11','max'=>'11','desc'=>'手机号'),
                'type'=>array('name'=>'type','type'=>'int','require'=>true,'desc'=>'1、注册，2、更改手机号')
            ),
             'register'=>array(
                'mobile'    => array('name' => 'mobile', 'type' => 'string', 'require'=>true,'min'=>'11','max'=>'11','desc'=>'手机号'),
                'password'=>array('name'=>'password','type'=>'string','min'=>6,'max'=>20,'require'=>true,'desc'=>'登录密码')
             ),
             'authentication'=>array(
             	'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
             	'licencepic'=>array('name'=>'licencepic','type'=>'file','desc'=>'驾照'),
             	'driving'=>array('name'=>'driving','type'=>'file','desc'=>'行驶证'),
             	'cartype'=>array('name'=>'cartype','type'=>'string','desc'=>'车型'),
             	'licence'=>array('name'=>'licence','type'=>'string','desc'=>'车牌号'),
             	'color'=>array('name'=>'color','type'=>'string','desc'=>'车颜色')
             ),
             'idcardauth'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'realname'=>array('name'=>'realname','type'=>'string','require'=>true,'desc'=>'真实姓名'),
                'idcard'=>array('name'=>'idcard','type'=>'string','max'=>'18','require'=>true,'desc'=>'身份证号'),
                'picture1'=>array('name'=>'picture1','type'=>'file','desc'=>'身份证 正面'),
                'picture2'=>array('name'=>'picture2','type'=>'file','desc'=>'身份证 反面'),
             ),
             'login'=>array(
                'mobile'=>array('name'=>'mobile','type'=>'string','max'=>11,'min'=>11,'require'=>true,'desc'=>'手机号'),
                'password'=>array('name'=>'password','type'=>'string','min'=>6,'max'=>20,'require'=>true,'desc'=>'登录密码')
             ),
             'notice'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
             ),
             'editavatar'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'avatar'=>array('name'=>'avatar','type'=>'file','require'=>true,'desc'=>'上传头像')
             ),
             'editnick'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'nick'=>array('name'=>'nick','type'=>'string','require'=>true,'desc'=>'昵称')
             ),
             'editsex'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'sex'=>array('name'=>'sex','type'=>'int','require'=>true,'desc'=>'性别：1：男，2：女')
             ),
             'editpass'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'oldpass'=>array('name'=>'oldpass','type'=>'string','require'=>true,'min'=>6,'desc'=>'旧密码，密码长度不能小于6位'),
                'newpass'=>array('name'=>'newpass','type'=>'string','require'=>true,'min'=>6,'desc'=>'新密码，密码长度不能小于6位')
             ),
             'changemobile'=>array(
                'uid'=>array('name'=>'uid','type'=>'int','require'=>true,'desc'=>'用户id'),
                'mobile'=>array('name'=>'mobile','type'=>'string','max'=>11,'min'=>11,'require'=>true,'desc'=>'手机号'),
                'captcha'=>array('name'=>'captcha','type'=>'string','max'=>'6','min'=>'6','require'=>true,'desc'=>'验证码')
             )
        );
	}
	
	/**
	 * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
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
	 * 登录接口
	 * @desc 手机验证码登录接口
	 * @return string mobile 用户手机号
	 * @return string id 用户id
	 * @return string state 1、未认证，2、已认证
	 * @return int create_time 注册时间
	 */
	public function mobilelogin(){
		$rs=array(
            'code'=>1,
            'msg'=>'',
            'info'=>array(),
        );
        $domail=new DomainIndex();
        $result=$domail->Login($this->mobile,$this->captcha);
        if($result['code']==0){
            $rs['msg']='登录成功!';
            $rs['info'][] = $result['info'];
        }else if($result['code']==1){
            $rs['msg']='验证码不正确！';
            $rs['code']=0;
            $rs['info'] = array();
        }else if($result['code']==2){
			$rs['msg']='未找到此手机号';
            $rs['code']=0;
            $rs['info'] = array();
        }else{
        	$rs['msg']='登录失败!';
            $rs['code']=0;
            $rs['info'] = array();
        }
        return $rs;
	}

    /**
     * 注册接口
     * @desc 手机号 注册接口
     * @return string mobile 用户手机号
     * @return string id 用户id
     * @return string state 1、未认证，2、已认证
     * @return int create_time 注册时间
     */
    public function register(){
        $rs=array(
            'code'=>1,
            'msg'=>'',
            'info'=>array(),
        );
        $domail=new DomainIndex();
        $result=$domail->register($this->mobile,$this->password);
        if($result['code']==1){
            $rs['msg']='注册成功!';
            $rs['info'][] = $result;
        }else if($result['code']==2){
            $rs['msg']='该手机号已注册！';
            $rs['code']=0;
            $rs['info'] = array();
        }else{
            $rs['msg']='注册失败!';
            $rs['code']=0;
            $rs['info'] = array();
        }
        return $rs;
    }

    /**
     * 登录接口
     * @desc 手机号、密码登录接口
     * @return array info 用户信息
     * @return string id 用户id
     * @return string state 1、未认证，2、已认证
     * @return int create_time 注册时间
     */
    public function login(){
        $rs=array(
            'code'=>1,
            'msg'=>'',
            'info'=>array(),
        );
        $domail=new DomainIndex();
        $result=$domail->logins($this->mobile,$this->password);
        if($result['code']==0){
            $rs['msg']='登录成功!';
            $rs['info'][] = $result['info'];
        }else if($result['code']==1){
            $rs['msg']='密码错误！';
            $rs['code']=0;
            $rs['info'] = array();
        }else if($result['code']==2){
            $rs['msg']='未找到此手机号';
            $rs['code']=0;
            $rs['info'] = array();
        }else{
            $rs['msg']='登录失败!';
            $rs['code']=0;
            $rs['info'] = array();
        }
        return $rs;
    }

	/**
     * 获取短信验证码
     * 
     * @return int code 操作码，1表示成功 ,0表示失败
     * @return string msg 提示信息
     * @return array info 验证码列表
     * @return string info[] 验证码
     */
    public function getVerifyCode(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->getVerifyCode($this->mobile,$this->type);
        if ($result['re'] == 0) {
            unset($result['re']);
            $rs['code']=1;
            $rs['msg']='发送成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='发送失败！';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
	 * 车主 认证接口
	 * @desc 认证接口，车主认证时使用
	 * @return string state 1、成功 0、失败
	 */
    public function authentication(){
    	$rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );

    	if(!empty($this->licencepic)){
            $tmpName = $this->licencepic['tmp_name'];
            $name = md5($this->licencepic['name'] . $_SERVER['REQUEST_TIME']);
            $ext = strrchr($this->licencepic['name'], '.');
            if(empty($ext)){
                $ext='.jpg';
            }
            $uploadFolder = sprintf('%s/public/uploads/auth/', API_ROOT);
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }
            $imgPath = $uploadFolder .  $name . $ext;
            if (move_uploaded_file($tmpName, $imgPath)) {
                //$licencepic = sprintf('http://%s/phalapi/public/uploads/auth/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
                $licencepic='auth/'.$name.$ext;
            }
        }else{
        	$licencepic='';
        }

        if(!empty($this->driving)){
            $tmpName = $this->driving['tmp_name'];
            $name = md5($this->driving['name'] . $_SERVER['REQUEST_TIME']);
            $ext = strrchr($this->driving['name'], '.');
            if(empty($ext)){
                $ext='.jpg';
            }
            $uploadFolder = sprintf('%s/public/uploads/auth/', API_ROOT);
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }
            $imgPath = $uploadFolder .  $name . $ext;
            if (move_uploaded_file($tmpName, $imgPath)) {
                //$driving = sprintf('http://%s/phalapi/public/uploads/auth/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
            	$driving='auth/'.$name.$ext;
            }
        }else{
        	$driving='';
        }

        $domain = new DomainIndex();
        $result = $domain->authentication($this->uid,$licencepic,$driving,$this->cartype,$this->licence,$this->color);
        if ($result==0) {
            $rs['code']=1;
            $rs['msg']='认证申请成功，请耐心等待！';
            $rs['info']= $result;
            return $rs;
        }else if($result==1){
            $rs['code']=0;
            $rs['msg']='您正在申请认证，请勿重复提交';
            $rs['info']= array();
            return $rs;
        }else if($result==2){
        	$rs['code']=0;
            $rs['msg']='您的认证申请通过！';
            $rs['info']= array();
            return $rs;
        }else{
        	$rs['code']=0;
            $rs['msg']='认证申请提交失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 实名认证接口
     * @desc 认证接口，乘客认证时使用
     * @return string state 1、成功 0、失败
     */
    public function idcardauth(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        if(!empty($this->picture1)){
            $tmpName = $this->picture1['tmp_name'];
            $name = md5($this->picture1['name'] . $_SERVER['REQUEST_TIME']);
            $ext = strrchr($this->picture1['name'], '.');
            if(empty($ext)){
                $ext='.jpg';
            }
            $uploadFolder = sprintf('%s/public/uploads/auth/', API_ROOT);
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }
            $imgPath = $uploadFolder .  $name . $ext;
            if (move_uploaded_file($tmpName, $imgPath)) {
                //$licencepic = sprintf('http://%s/phalapi/public/uploads/auth/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
                $pictures='auth/'.$name.$ext;
            }
        }else{
            $licencepic='';
        }

        if(!empty($this->picture2)){
            $tmpName = $this->picture2['tmp_name'];
            $name = md5($this->picture2['name'] . $_SERVER['REQUEST_TIME']);
            $ext = strrchr($this->picture2['name'], '.');
            if(empty($ext)){
                $ext='.jpg';
            }
            $uploadFolder = sprintf('%s/public/uploads/auth/', API_ROOT);
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }
            $imgPath = $uploadFolder .  $name . $ext;
            if (move_uploaded_file($tmpName, $imgPath)) {
                //$driving = sprintf('http://%s/phalapi/public/uploads/auth/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
                $picturef='auth/'.$name.$ext;
            }
        }else{
            $driving='';
        }

        $domain = new DomainIndex();
        $result = $domain->idcardauth($this->uid,$this->realname,$this->idcard,$pictures,$picturef);
        if ($result==0) {
            $rs['code']=1;
            $rs['msg']='认证申请成功，请耐心等待！';
            $rs['info']= $result;
            return $rs;
        }else if($result==1){
            $rs['code']=0;
            $rs['msg']='您正在申请认证，请勿重复提交';
            $rs['info']= array();
            return $rs;
        }else if($result==2){
            $rs['code']=0;
            $rs['msg']='您的认证申请通过！';
            $rs['info']= array();
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='认证申请提交失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 消息接口
     * @desc 用户消息列表
     * @return string title 标题
     * @return string notice 内容
     * @return string create_time 当前时间
     */
    public function notice() {
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->notice($this->uid);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='获取成功！';
            $rs['info']= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='暂无数据！';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 修改头像接口
     * @desc 用户修改个人头像
     * @return string state 1、成功 0、失败
     */
    public function editavatar(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        if(!empty($this->avatar)){
            $tmpName = $this->avatar['tmp_name'];
            $name = md5($this->avatar['name'] . $_SERVER['REQUEST_TIME']);
            $ext = strrchr($this->avatar['name'], '.');
            if(empty($ext)){
                $ext='.jpg';
            }
            $uploadFolder = sprintf('%s/public/uploads/avatar/', API_ROOT);
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777);
            }
            $imgPath = $uploadFolder .  $name . $ext;
            if (move_uploaded_file($tmpName, $imgPath)) {
                //$licencepic = sprintf('http://%s/phalapi/public/uploads/auth/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
                $pictures='avatar/'.$name.$ext;
            }
        }else{
            $pictures='';
        }

        $domain = new DomainIndex();
        $result = $domain->editavatar($this->uid,$pictures);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='上传成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='上传失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 修改昵称接口
     * @desc 用户修改昵称
     * @return string state 1、成功 0、失败
     */
    public function editnick(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->editnick($this->uid,$this->nick);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='修改成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='修改失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 修改性别接口
     * @desc 用户修改性别
     * @return string state 1、成功 0、失败
     */
    public function editsex(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->editsex($this->uid,$this->sex);
        if (!empty($result)) {
            $rs['code']=1;
            $rs['msg']='修改成功！';
            $rs['info'][]= $result;
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='修改失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 修改登录密码接口
     * @desc 用户修改登录密码
     * @return string state 1、成功 0、失败 2、旧密码不正确
     */
    public function editpass(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->editpass($this->uid,$this->oldpass,$this->newpass);
        if ($result ==2) {
            $rs['code']=0;
            $rs['msg']='旧密码不正确！';
            $rs['info'][]= $result;
            return $rs;
        }else if($result ==1){
            $rs['code']=1;
            $rs['msg']='修改成功~';
            $rs['info']= array();
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='修改失败~';
            $rs['info']= array();
            return $rs;
        }
    }

    /**
     * 修改手机号接口
     * @desc 用户修改手机号
     * @return string state 1、成功 0、失败 2、旧密码不正确
     */
    public function changemobile(){
        $rs = array(
            'code' => 1,
            'msg' => '',
            'info' => array()
        );
        $domain = new DomainIndex();
        $result = $domain->changemobile($this->uid,$this->mobile,$this->captcha);
        if ($result ==2) {
            $rs['code']=0;
            $rs['msg']='验证码不正确！';
            $rs['info'][]= $result;
            return $rs;
        }else if($result ==1){
            $rs['code']=1;
            $rs['msg']='修改成功~';
            $rs['info']= array();
            return $rs;
        }else if($result == 3){
            $rs['code']=0;
            $rs['msg']='该手机号已被注册！';
            $rs['info']= array();
            return $rs;
        }else{
            $rs['code']=0;
            $rs['msg']='修改失败~';
            $rs['info']= array();
            return $rs;
        }
    }
}
