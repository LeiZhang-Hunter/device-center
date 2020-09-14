<?php

namespace Controller;

use Pendant\Common\MQTTProxyTool;
use Pendant\MQTT\DeviceCenterHandle;
use Pendant\MQTT\MQTTProxyPool;
use Pendant\SwooleSysSocket;
use Structural\System\DeviceCenterClientStruct;
use Structural\System\MQTTProxyProtocolStruct;

class DeviceCenterController extends DeviceCenterHandle
{
    public $tool;

    public $responsePool = [];

    public function __construct()
    {
        // TODO: Implement onConnect() method.
        $this->tool = MQTTProxyTool::getInstance();
    }

    public function onConnect()
    {

    }

    public function onReceive(MQTTProxyProtocolStruct $protocol)
    {
        // TODO: Implement onReceive() method.
        //
        $payload = ($protocol->payload);
        $client_id = $protocol->client_id;
        $message = $protocol->payload["message"];
        $topic = isset($payload["topic"]) ? $payload["topic"] : "";
        if (!$topic) {
            $token = md5(uniqid());
            $pack_protocol = new MQTTProxyProtocolStruct();
            $pack_protocol->type = MQTTProxyProtocolStruct::MQTT_PROXY;
            $pack_protocol->client_id = $protocol->client_id;
            $pack_protocol->mqtt_type = MQTTProxyProtocolStruct::OnPublishMessage;
            $pack_protocol->message_no = MQTTProxyProtocolStruct::RETURN_OK;
            $payload["topic"] = $client_id . "/request/" . $token;
            $pack_protocol->payload = json_encode($payload);
            $this->responsePool[$token] = $protocol->fd;
            $this->responsePool[$protocol->fd] = $token;
            SwooleSysSocket::$swoole_server->send(MQTTProxyPool::getInstance()->getProxy(),
                $this->tool->pack($pack_protocol));
        } else {
            $responseMsg = explode("/", $topic);
            if ($responseMsg[1] == "response") {
                $response_token = $responseMsg[2];
                if (isset($this->responsePool[$response_token])) {
                    $client_fd = $this->responsePool[$response_token];
                    SwooleSysSocket::$swoole_server->send($client_fd, json_encode([
                        "code" => 0,
                        "msg" => $message
                    ]));
                }
            }

        }

    }

    public function onClose(MQTTProxyProtocolStruct $protocol)
    {
        if (isset($this->responsePool[$protocol->fd])) {
            $token = $this->responsePool[$protocol->fd];
            var_dump($token);
            unset($this->responsePool[$protocol->fd]);
            unset($this->responsePool[$token]);
        }
    }

}
