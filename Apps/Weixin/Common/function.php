<?php
function savexml($xml)
{
	if($xml!=''){
		M('xml')->add(array('xml'=>$xml));
	}
}

 function request($str_apiurl,$arr_param=array(),$str_returnType='array',$str_requestType='get'){
        if(!$str_apiurl){
            exit('request url is empty 请求地址不正确');
        }
        if($str_requestType=='get'){
            return getRequest($str_apiurl,$arr_param,$str_returnType);    //get请求
        }elseif($str_requestType=='post'){
            return postRequest($str_apiurl,$arr_param,$str_returnType);   //post请求
        }
    }
    
function getRequest($str_apiurl,$arr_param=array(),$str_returnType='array'){
        if(!$str_apiurl){
            exit('request url is empty 请求地址不正确');
        }

        //url拼装
        if(is_array($arr_param) && count($arr_param)>0){
            $tmp_param = http_build_query($arr_param);//&name=veiol&age=18
            echo '<br>';
            // dump($tmp_param);exit;
            if(strpos($str_apiurl, '?') !== false){
                $str_apiurl .= "&".$tmp_param;
            }else{
                $str_apiurl .= "?" . $tmp_param;
            }
        }elseif (is_string($arr_param)){
            if(strpos($str_apiurl, '?') !== false){
                $str_apiurl .= "&".$arr_param;
            }else{
                $str_apiurl .= "?" . $arr_param;
            }
        }
// dump($str_apiurl);exit;
        //请求头
        $this_header = array(
            "content-type: application/x-www-form-urlencoded; charset=UTF-8"
        );

        $ch = curl_init();  //初始curl
        curl_setopt($ch,CURLOPT_URL,$str_apiurl);   //需要获取的 URL 地址
        curl_setopt($ch,CURLOPT_HEADER,0);          //启用时会将头文件的信息作为数据流输出, 此处禁止输出头信息
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //获取的信息以字符串返回，而不是直接输出
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30); //连接超时时间
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);  //头信息
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); 
        $res = curl_exec($ch);                      //执行curl请求
        $response_code = curl_getinfo($ch);
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch)."<br>";
            //echo $res;
            var_dump($response_code);
        }

        //请求成功
        if($response_code['http_code'] == 200){
            if($str_returnType == 'array'){
                //echo $res;
                return json_decode($res,true);
            }else{
                return $res;
            }
        }else{
            $code = $response_code['http_code'];
            switch ($code) {
                case '404':
                    exit('请求的页面不存在');
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

function postRequest($str_apiurl,$arr_param=array(),$str_returnType='array'){
        if(!$str_apiurl){
            exit('request url is empty 请求地址不正确');
        }


        $ch = curl_init();  //初始curl
        curl_setopt($ch,CURLOPT_URL,$str_apiurl);   //需要获取的 URL 地址
        curl_setopt($ch,CURLOPT_HEADER,0);          //启用时会将头文件的信息作为数据流输出, 此处禁止输出头信息
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //获取的信息以字符串返回，而不是直接输出
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30); //连接超时时间
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在 
        //curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);  //头信息
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); 
        curl_setopt($ch, CURLOPT_POST, 1);          //post请求
        //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
        $res = curl_exec($ch);                      //执行curl请求
        $response_code = curl_getinfo($ch);

        //请求出错
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch)."<br>";
            //echo $res;
            var_dump($response_code);
        }

        //请求成功
        if($response_code['http_code'] == 200){
            if($str_returnType == 'array'){
                return json_decode($res,true);
            }else{
                return $res;
            }
        }else{
            $code = $response_code['http_code'];
            switch ($code) {
                case '404':
                    exit('请求的页面不存在');
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

function curl_post_https($url='', $postdata='',$options=FALSE){
        $curl = curl_init();// 启动一个CURL会话
         curl_setopt($curl, CURLOPT_URL, $url);//要访问的地址
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//对认证证书来源的检查
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);//从证书中检查SSL加密算法是否存在
         curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);//模拟用户使用的浏览器
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//使用自动跳转
         curl_setopt($curl, CURLOPT_AUTOREFERER, 1);//自动设置Referer
         if(!empty($postdata)){
            curl_setopt($curl, CURLOPT_POST, 1);//发送一个常规的Post请求
             if(is_array($postdata)){
                curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($postdata,JSON_UNESCAPED_UNICODE));//Post提交的数据包  
             }else{
                curl_setopt($curl, CURLOPT_POSTFIELDS,$postdata);//Post提交的数据包 
             }
         }
          curl_setopt($curl, CURLOPT_COOKIEFILE, './cookie.txt'); //读取上面所储存的Cookie信息
          curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时限制防止死循环
          curl_setopt($curl, CURLOPT_HEADER, $options);//显示返回的Header区域内容  可以是这样的字符串 "Content-Type: text/xml; charset=utf-8"
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回
         $output = curl_exec($curl);//执行操作
         if(curl_errno($curl)){
              if($this->debug == true){
                   $errorInfo='Errno'.curl_error($curl);
                   $this->responseMessage('text',$errorInfo);//将错误返回给微信端
              }
        }
         curl_close($curl);//关键CURL会话
         return $output;//返回数据
}

function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }