<?php

namespace Leo\Green;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Green
{
    private $accessKeyId,$accessKeySecret,$regionId,$debug,$timeout,$connectTimeout;
    //初始化
    public function __construct($accessKeyId = NULL,$accessKeySecret = NULL,$regionId='cn-shanghai', $debug = false, $timeout=6, $connectTimeout=10)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->regionId = $regionId;
        $this->debug = $debug;
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
    //建立连接    
        try {
            AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
               ->regionId($this->regionId)// 设置客户端区域，
               ->timeout($this->timeout)  // 超时10秒，使用该客户端且没有单独设置的请求都使用此设置
               ->connectTimeout( $this->connectTimeout)// 连接超时10秒
               ->debug($this->debug) // 开启调试
               ->asDefaultClient();
       } catch (Exception $e) {
           return ['code' => 0, 'message' => $e->getErrorMessage()];
       }

    }

    //使用roa调用
    protected function roa($action,$body){
        try {
            $result = AlibabaCloud::roa()
                ->product('Green')
                ->version('2018-05-09')
                ->pathPattern($action)
                ->method('POST')
                ->body(json_encode($body))
                ->request();
            return $result->toArray();
        } catch (ClientException $exception) {
            return ['code' => 0, 'message' => $exception->getErrorMessage()];
        } catch (ServerException $exception) {
            return ['code' => 0, 'message' => $exception->getErrorMessage()];
        }
    }


    /**
     * 图片同步检测
     * 接口：/green/image/scan
     * @param $url 设置待检测的图片，一张图片对应一个检测任务
     * @param string[] $scenes//默认：porn：图片智能鉴黄,terrorism：暴恐涉政
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function ImageScan($url){        
        $tasks[] = [
            'dataId'=>uniqid(),
            'url'=>$url
        ];
        $data = [
            'tasks' => $tasks,
            'scenes' => array("porn", "terrorism"),
        ];
        return $this->roa('/green/image/scan',$data);
    }

    /**
     * 文本内容检测
     * 接口：/green/text/scan
     * @param $text
     */
    public function textScan($text)
    {
        $tasks[] = [
            'dataId'=>uniqid(),
            'content'=>$text
        ];
        $data = array(
            'tasks' => $tasks,
            'scenes' => array("antispam")
        );
        return $this->roa('/green/text/scan', $data);
    }


    /**
     * 视频URL检测
     * 接口：/green/video/asyncscan
     * @param $text
     */
    public function VideoScan($url){
        $tasks[] = [
            'dataId'=>uniqid(),
            'url'=>$url
        ];
        $data = array(
            'tasks' => $tasks,
            'scenes' => array("porn","terrorism")
        );
        return $this->roa('/green/video/asyncscan', $data);

    }

    /**
     * 视频检测结果反馈
     * 业务接口：/green/video/results
     * @param $text
     */
    public function VideoResults($taskId){
        if(!is_array($taskId)){
            $taskId = [$taskId];
        }
        return $this->roa('/green/video/results', $taskId); 
    }

    /**
     * 停止检测
     * 接口名： /green/video/cancelscan
     * @param $text
     */
    public function VideoCancelscan($taskId){
        if(!is_array($taskId)){
            $taskId = [$taskId];
        }
        return $this->roa('/green/video/results', $taskId); 
    }
    

}