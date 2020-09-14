<?php

include_once "../device-center-framework/autoload.php";

$instance = Pendant\MQTT\DeviceCenterClient::getInstance();
$instance->setPort(9800);
$r = $instance->publish("MQTT_FX_Client", "111111", 100);
var_dump($r);
