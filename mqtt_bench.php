<?php
//mqtt 代理压力测试类包
include_once "../device-center-framework/autoload.php";

$pool = [];

$process_num = 100;
$request_number = 100;
for($i = 0;$i<$process_num;$i++)
{
    $process = new Swoole\Process(function ($worker){
        $index = $worker->number;
        $request_number = $worker->request;
        $begin = $index*$request_number;
        $end = $begin + $request_number;
        for($i =$begin;$i<$end;$i++) {
            $client = new Swoole\Client(SWOOLE_SOCK_TCP);
            $r = $client->connect('127.0.0.1', 9500, -1);
            $package = array(
                'cmd' => \Vendor\MqttBench\Mqtt::CMD_CONNECT,
                'clean_session' => 0,
                'protocol_name' => "mqtt",
                'protocol_level' => 3,
                'client_id' => $i,
                'username' => "hahaha",
                'password' => "papapap",
            );
            $r = $client->send(\Vendor\MqttBench\Mqtt::encode($package));
            $r = $client->recv();
            if(!$r)
            {
                continue;
            }
            $package = array(
                'cmd' => \Vendor\MqttBench\Mqtt::CMD_SUBSCRIBE,
                'topics' => [
                    "a$i"=>1
                ],
                'message_id' => 1,
            );
            $client->send(\Vendor\MqttBench\Mqtt::encode($package));
        }
    });
    $process->number = $i;
    $process->request = $request_number;
    $process->start();
    $pool[] = $process;
}

foreach ($pool as $process)
{
    $process->wait();
}