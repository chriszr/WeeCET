<?php

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
  $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];	
                    
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
	
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

       
    
    public function responseMsg()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$RX_TYPE = trim($postObj->MsgType);

			switch ($RX_TYPE)
			{
				case "text":
					$resultStr = $this->receiveText($postObj);
					break;
				case "event":
					$resultStr = $this->receiveEvent($postObj);
					break;
				default:
					$resultStr = $this->getMenuCont($postObj);
					break;
			}
			echo $resultStr;
		}else {
			echo "";
			exit;
		}
	}
    
    
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "0、进入主菜单
1、按身份证号码查询成绩信息
2、按准考证号码查询成绩信息
3、按学生号码查询成绩信息
如，回复 CET1#211******，进行查询";
                break;
            case "unsubscribe":
                $contentStr = "";
                break;
            default:
                $contentStr = "receive a new event: ".$object->Event;
                break;
        }
        $resultStr = $this->transmitText($object, $contentStr);
        return $resultStr;
    }
    
    private function transmitText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>%d</FuncFlag>
				</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
        
    private function  receiveText($object)
    {
		$funcFlag = 0;
        //$contentStr = $this->getContents(trim($object->Content));
        //$resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        
        $keyword=trim($object->Content);
        $keywordFlag = strtok($keyword,"#");
        if($keywordFlag =="CET1"){
            require_once "./CET/class.php";
            $gradeQuery = new gradeQuery();
            $contentStr = $gradeQuery->get_cet_score($keyword,$keywordFlag);

            
        }else if($keywordFlag =="CET2"){
            require_once "./CET/class.php";
            $gradeQuery = new gradeQuery();
            $contentStr = $gradeQuery->get_cet_score($keyword,$keywordFlag);
            
        }else if($keywordFlag =="CET3"){
            require_once "./CET/class.php";
            $gradeQuery = new gradeQuery();
            $contentStr = $gradeQuery->get_cet_score($keyword,$keywordFlag);
            
        } else {
        	$resultStr = $this->getMenuCont($object);
             return $resultStr;
        }
        
        $resultStr = $this->transmitText($object, $contentStr);
        return $resultStr;
	}
    
    
    private function transmitNews($object, $arr_item, $flag = 0)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "<item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['Picurl'], $item['Url']);

        $newsTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <Content><![CDATA[]]></Content>
                <ArticleCount>%s</ArticleCount>
                <Articles>
                $item_str</Articles>
                <FuncFlag>%s</FuncFlag>
                </xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $flag);
        return $resultStr;
    }
    
     private function getMenuCont($object)
    {
		 $contentStr="CET进入主菜单
1、按身份证号码查询成绩信息
2、按准考证号码查询成绩信息
3、按学生号码查询成绩信息
如，回复 CET1#211******，进行查询";  
                  
         $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
         return $resultStr;
     }
    
    private function getAppointment($object)
    {
		 $contentStr="如需预约业务办理请回复：预约办理：+ 内容 + 预约时间。";
         
         $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
         return $resultStr;
     }
      private function getResponseCont($object)
    {
		 $contentStr="多多已收到您的预约信息，稍后与您联系。";
         
         $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
         return $resultStr;
     }
     
    private function getSuggestion($object)
    {
		 $contentStr="意见箱使用方法 请回复：意见：+ 内容 。";
         
         $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
         return $resultStr;
     }
      private function getResponseSugCont($object)
    {
		 $contentStr="多多已收到您的意见，稍后与您联系。";
         
         $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
         return $resultStr;
     }
    
    
}

?>