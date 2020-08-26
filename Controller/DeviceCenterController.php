<?php

namespace Controller;

use Pendant\Common\MQTTProxyTool;
use Pendant\MQTT\DeviceCenterHandle;
use Pendant\MQTT\MQTTProxyPool;
use Pendant\SwooleSysSocket;
use Structural\System\MQTTProxyProtocolStruct;

class DeviceCenterController extends DeviceCenterHandle
{
    public $tool;

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
        if ($protocol->type == MQTTProxyProtocolStruct::DEVICE_CENTER_CLIENT) {
            $client_id = $protocol->client_id;
            $payload = ($protocol->payload);
            $token = md5(uniqid());
            $pack_protocol = new MQTTProxyProtocolStruct();
            $pack_protocol->type = MQTTProxyProtocolStruct::MQTT_PROXY;
            $pack_protocol->client_id = $protocol->client_id;
            $pack_protocol->mqtt_type = MQTTProxyProtocolStruct::OnPublishMessage;
            $pack_protocol->message_no = MQTTProxyProtocolStruct::RETURN_OK;
            $payload["topic"] = $client_id."/request/".$token;
            $pack_protocol->payload = json_encode($payload);

            SwooleSysSocket::$swoole_server->send(MQTTProxyPool::getInstance()->getProxy(),
                $this->tool->pack($pack_protocol));
        } else {

        }
    }

    public function onClose()
    {
        // TODO: Implement onClose() method.
    }

}
