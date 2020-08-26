<?php

namespace Controller;

use Pendant\MQTT\DeviceCenterHandle;
use Structural\System\MQTTProxyProtocolStruct;

class DeviceCenterController extends DeviceCenterHandle
{

    public function onConnect()
    {
        // TODO: Implement onConnect() method.
    }

    public function onReceive(MQTTProxyProtocolStruct $protocol)
    {
        // TODO: Implement onReceive() method.
        //
    }

    public function onClose()
    {
        // TODO: Implement onClose() method.
    }

}
