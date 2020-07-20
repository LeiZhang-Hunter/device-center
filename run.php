<?php
/**
 * Created by PhpStorm.
 * User: zhanglei
 * Date: 20-7-20
 * Time: 下午7:06
 */
include_once "../device-center-framework/autoload.php";
//设置服务名称
\Pendant\FrameworkEnv::setServiceName("device-center");

//设置项目根路径
\Pendant\FrameworkEnv::setProjectDir(__DIR__);

class A{

}

\Structural\System\EventStruct::addCall(1, 400 , A::class);

$swooleSocket = \Pendant\SwooleSysSocket::getInstance();

//运行程序
$swooleSocket->run();
