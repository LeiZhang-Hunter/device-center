<?php
//mqtt 代理压力测试类包
include_once "../device-center-framework/autoload.php";
$pool = [];

$process_num = 100;
for($i = 0;$i<$process_num;$i++)
{
    $process = new Swoole\Process(function ($worker){
        exec("php /home/zhanglei/data/www/master/swoole_mqtt/examples/subscribe.php ".$process->index);
    });
    $process->index = $i;
    $process->start();
    $pool[] = $process;
}

foreach ($pool as $process)
{
    $process->wait();
}