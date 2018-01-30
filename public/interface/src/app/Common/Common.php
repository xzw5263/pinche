<?php
namespace App\Common;
class Object{
	public $result;
}
class Common{

  /*
   * $lat1, $lon1: 第一个点的经纬度
   * $lat2, $lon2: 第二个点的经纬度
   * $radius: 可选，默认为地球的半径
   */
  function calculate($lng1, $lat1, $lng2, $lat2, $radius=6378.135) {
  	$arr=new Object();
    $rad = doubleval(M_PI/180.0);

    $lat1 = doubleval($lat1) * $rad;
    $lon1 = doubleval($lng1) * $rad;
    $lat2 = doubleval($lat2) * $rad;
    $lon2 = doubleval($lng2) * $rad;

    $theta = $lng2 - $lng1;
    $dist = acos(sin($lat1) * sin($lat2) + 
                cos($lat1) * cos($lat2) * cos($theta));
    if($dist < 0) {
      $dist += M_PI;
    }
    // 单位为 千米
    $dist = $dist * $radius;
    $arr->result= sprintf("%2.f", $dist * 0.612);;
    return $arr;
  }

  
  //调用百度地图api
  public function calculate1($lng1, $lat1, $lng2, $lat2){
  	//http://api.map.baidu.com/direction/v2/driving?origin=40.01116,116.339303&destination=39.936404,116.452562&ak=您的ak
	/* $url = "http://api.map.baidu.com/direction/v2/driving";
	 $data=array(
	 	'origin'=>$lng1.','.$lat1,
	 	'destination'=>$lng2.','.$lat2,
	 	'ak'=>'wHKCXeieSkK4ZR1w9LGsuAt4K2bbKnme'
	 );
	 $distance = $this->curl_post($url,$data);
	 var_dump($distance);exit;
	 */
	 $distance=file_get_contents("http://api.map.baidu.com/direction/v2/driving?origin=$lat1,$lng1&destination=$lat2,$lng2&ak=wHKCXeieSkK4ZR1w9LGsuAt4K2bbKnme");
	 return json_decode($distance);
	 
  }

   private function curl_post($url, $post_arr, $referer = '')
    {
        $post_str = '';
        foreach ($post_arr as $k => $v) {
            $post_str .= $k . '=' . $v . '&';
        }
        $post_str = substr($post_str, 0, - 1);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址 即要登录的地址页面
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_str); // Post提交的数据包
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); // 使用自动跳转
        curl_setopt($curl, CURLOPT_REFERER, $referer); // 设置Referer
        // curl_setopt ( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1" ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HEADER, false); // 获取header信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 只需要设置一个秒的数量就可以
        $result = curl_exec($curl);
        return $result;
    }

}
