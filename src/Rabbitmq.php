<?php


namespace kdy\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq
{
    protected $conf;

    protected $connection;

    protected $channel;

    protected $exchangeName;

    protected $queueName;

    protected $routyKey;

    protected static $service = [];

    public function __construct()
    {
        $this->init();
    }

    public static function instance() {

        if (isset(self::$service[static::class])) {
            return self::$service[static::class];
        } else {

            $obj = new static();
            self::$service[static::class] = $obj;
            return $obj;
        }
    }

    /**
     * @desc 初始化配置信息
     */
    private function init()
    {
        $this->conf = config('rabbitmq');
        if(!$this->conf){
            exit('配置文件未找到');
        }

    }

    /**
     * @desc 创建连接
     *
     */
    public function onConnection($connection='')
    {
        if($connection){
            $this->connection = $this->conf['connections'][$connection];
        }else{
            $this->connection = $this->conf['connections']['default'];
        }
        $this->connection = new AMQPStreamConnection(
            $connection['host'],
            $connection['port'],
            $connection['username'],
            $connection['password'],
            $connection['vhost']
        );
        if(!$this->connection){
            exit('创建连接失败');
        }
        //创建通道
        $this->channel = $this->connection->channel();
        if(!$this->channel){
            exit('创建通道失败');
        }
        return $this;
    }

    /**
     * @desc 创建交换机
     * @param string $exchange 交换机配置文件key
     * @readme exchange_declare 交换机名称 类型 只判断不创建 设置是否持久化 设置是否自动删除
     *
     */
    public function onExchange($exchange = '')
    {
        $this->exchangeName = $this->conf['exchange'][$exchange]['name'];
        $this->channel->exchange_declare($this->exchangeName, 'direct', false, false, false);
        return $this;
    }

    /**
     * @desc 创建绑定队列
     * @param string $queue 交换机配置文件key
     * @readme queue_declare 队列名称 只判断不创建 设置是否持久化 设置是否排他 设置是否自动删除
     *
     */

    public function onQueue($queue = '')
    {
        $this->queueName = $this->conf['queue'][$queue]['name'];
        $this->routyKey = $this->conf['queue'][$queue]['route_key'];

        $this->channel->queue_declare($this->queueName,false,true,false,false);
        $this->channel->queue_bind(
            $this->queueName,
            $this->exchangeName,
            $this->routyKey
        );
        return $this;
    }

    /**
     * @desc 消息分发
     * @readme basic_publish 消息 交换机名称 路由key
     */
    public function dispatch($data=[])
    {

        if(!$data){
            exit('消息数据不能为空');
        }
        $msg = new AMQPMessage(json_encode($data),[
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg,$this->exchangeName,$this->routyKey);
    }

    /**
     * @desc 消息接收
     * @readme basic_consume 队列名称 消费者标签 AMQP的标准 ack应答false手动应答 设置是否排他 不等待服务器回执信息 回调函数
     * $param function $callback 回调方法
     */
    public function accept($callback)
    {
        echo " 等待消息中...";
        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);
        while (count($this->channel->callbacks)) {
                $this->channel->wait();
        }
        $this->channel->close();
        $this->connection->close();
    }


    /**
     * 确认消息
     *
     * @param $message AMQPMessage 当前消息
     */
    public function ack($message)
    {

    }

    /**
     * 拒收消息
     *
     * @param $message AMQPMessage 当前消息
     * @param $multiple bool 是否应用于多消息
     * @param $requeue bool 是否requeue
     */
    protected function nack($message, $multiple = false, $requeue = false)
    {

    }

    /**
     * 拒绝消息并选择是否重新入队
     *
     * @param $message AMQPMessage 当前消息
     * @param $requeue bool 是否requeue true则重新入队列(该消费者还是会消费到该条被reject的消息),否则丢弃或者进入死信队列。
     */
    protected function reject($message, $requeue = false)
    {

    }

    /**
     * 是否恢复消息到队列
     *
     * @param $requeue bool true则重新入队列并且尽可能的将之前recover的消息投递给其他消费者消费,而不是自己再次消费,false则消息会重新被投递给自己
     */
    protected function recover($requeue = false)
    {

    }

//    public function __destruct()
//    {
//        $this->channel->close();
//        $this->connection->close();
//    }

}