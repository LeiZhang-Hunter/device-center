<?php

include_once "../device-center-framework/autoload.php";

$instance = Pendant\MQTT\DeviceCenterClient::getInstance();
$instance->setPort(9800);
$instance->publish("lei", "111111", "hahahaha");