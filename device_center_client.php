<?php

include_once "../device-center-framework/autoload.php";

$instance = Pendant\MQTT\DeviceCenterClient::getInstance();
$instance->setPort(9800);
$instance->publish("test", "111111", "hahahaha");