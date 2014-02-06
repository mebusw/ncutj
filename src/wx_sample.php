<?php
/**
  * wechat php test
  */


// ------ Settings & Includes ----------
header("content-type:text/html; charset=utf-8");
// require_once("config.php");

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
	public function valid()
    {
        if( isset($_REQUEST['echostr']) ) {
            $echoStr = $_GET["echostr"];
            echo $echoStr;
            exit;
        }

        echo $this->checkSignature();
        $this->responseMsg();
        exit;

    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $msgType = trim($postObj->MsgType);
                $event = trim($postObj->Event);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
                            <Event><![CDATA[%s]]></Event>
							</xml>";    

                switch ($msgType) {
                    case "event":
                     # code...
                     break;
                    case "text":
                        break;
                    default:
                     # code...
                     break;
                 }                                     


            	$contentStr = "Welcome to wechat world!";
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr, $event);
            	echo $resultStr;

        }else {
        	echo "No POST raw data.";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;

        echo "=".$signature.$timestamp.$nonce.$token."=";

		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>