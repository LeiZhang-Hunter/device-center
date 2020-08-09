<?php
//mqtt 代理压力测试类包
include_once "../device-center-framework/autoload.php";
$pool = [];

$process_num = 100;
for($i = 0;$i<$process_num;$i++)
{
    $process = new Swoole\Process(function ($worker){
        $index = $worker->index;
        exec("/home/zhanglei/ourc/mosquitto-1.4.10/client/mosquitto_sub -p 9500 -t hello{$index} -d");
    });
    $process->index = $i;
    $process->start();
    $pool[] = $process;
}

foreach ($pool as $process)
{
    $process->wait();
}