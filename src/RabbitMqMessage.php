<?php

namespace Tvision\CrmLoggerBundle;


abstract class RabbitMqMessage
{
    const MESSAGE_TYPE_INDEX = 'MessageType';

    abstract public function toRabbiMqMessage();
}