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

            switch ($msgType) {
                case "event":
                    switch ($event) {
                        case 'subscribe':
                            $contentStr = "欢迎关注中央大学天津校友会。";
                            break;
                        case 'unsubscribe':
                            $contentStr = "谢谢光临。";
                            break;
                    }                        
                    break;

                case "text":
                    switch ($keyword) {
                        case '1':
                            $contentStr = "最近尚未安排活动，敬请期待。";
                            break;
                        case '2':
                            $contentStr = "请留下你微信号。";
                            break;
                        default:
                            $contentStr = "回复数字:\n1.获取最新活动安排。\n2.申请加入微信大群。\n";
                            break;
                    }
                    break;

                default:
                    break;
            } 

            $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
                <Event><![CDATA[%s]]></Event>
                </xml>";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr, $event);
            echo $resultStr;

        } else {
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