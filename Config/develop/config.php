<?php
/**
 * Created by PhpStorm.
 * User: zhanglei
 * Date: 20-7-20
 * Time: 下午7:10
 */
return [
    "device-center-pid-file" => \Pendant\FrameworkEnv::$project_dir."/Run/device-center.pid",
    "sentry_log_file" => \Pendant\FrameworkEnv::$project_dir."/Log/",
    \Structural\System\ConfigStruct::SERVER => [
        [
            \Structural\System\ConfigStruct::S_IP=>"0.0.0.0",
            \Structural\System\ConfigStruct::S_PORT=>"9800",
            \Structural\System\ConfigStruct::S_TYPE=>\Structural\System\SwooleProtocol::TCP_PROTOCOL,
            \Structural\System\ConfigStruct::S_CONTROLLER=>\Controller\MQTTProxyController::class,
            \Structural\System\ConfigStruct::S_PROTOCOL_TYPE=>\Structural\System\ProtocolTypeStruct::MQTT_PROXY_PROTOCOL
        ]
    ]
];