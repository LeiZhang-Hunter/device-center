<?php
/**
 * Created by PhpStorm.
 * User: zhanglei
 * Date: 20-7-22
 * Time: 下午8:24
 */
namespace Controller;
use Pendant\Common\MQTTProxyTool;
use Pendant\Common\Tool;
use Pendant\MQTT\DeviceCenterHandle;
use Pendant\MQTT\MQTTProxyHandle;
use Pendant\SwooleSysSocket;
use Structural\System\DeviceCenterClientStruct;
use Structural\System\MQTTProxyProtocolStruct;

class MQTTProxyController extends MQTTProxyHandle
{
    public $tool;

    public function __construct()
    {
        $this->regDeviceCenterServer(DeviceCenterController::class);
        $this->tool = MQTTProxyTool::getInstance();
    }

    public function onConnect(MQTTProxyProtocolStruct $protocol)
    {
        //重新初始化回调协议 发送数据
        $protocol->type = 0;
        $protocol->mqtt_type = MQTTProxyProtocolStruct::OnConnectMessage;
        $protocol->message_no = 0;
        $protocol->payload = "";
        SwooleSysSocket::$swoole_server->send($protocol->fd,
            $this->tool->pack($protocol));
    }

    public function onDisConnect(MQTTProxyProtocolStruct $protocol)
    {

    }

    public function onSubscribe(MQTTProxyProtocolStruct $protocol)
    {
        $topic = $protocol->payload["topic"];
        $message_id = $protocol->payload["message_id"];
        $qos_level = $protocol->payload["qos_level"];
        $protocol->type = 0;
        $protocol->mqtt_type = MQTTProxyProtocolStruct::OnSubscribeMessage;
        $protocol->message_no = 0;
        $protocol->payload = json_encode([
            "topic"=>$topic,
            "message_id" => $message_id,
            "qos_level" => $qos_level
        ]);

        SwooleSysSocket::$swoole_server->send($protocol->fd,
            $this->tool->pack($protocol));
    }

    public function onUnSubscribe(MQTTProxyProtocolStruct $protocol)
    {

    }

    public function onPublish(MQTTProxyProtocolStruct $protocol)
    {
        $topic = $protocol->payload["topic"];
        $message_id = $protocol->payload["message_id"];
        $qos_level = $protocol->payload["qos_level"];
        $message = $protocol->payload["message"];
        $protocol->type = 0;
        $protocol->mqtt_type = MQTTProxyProtocolStruct::OnPublishMessage;
        $protocol->message_no = 0;
        $payload = [
            "topic"=>$topic,
            "message_id" => $message_id,
            "qos_level" => $qos_level,
            "message" => $message
        ];
        $protocol->payload = json_encode($payload);
        SwooleSysSocket::$swoole_server->send($protocol->fd,
            $this->tool->pack($protocol));

        $request = explode("/", $topic);
        if ($request[1] == "response") {
            $protocol->payload = $payload;
            $protocol->type = DeviceCenterClientStruct::OnClientReceive;
            $this->getDeviceCenterDispatch()->dispatcher($protocol);
        }
    }
}