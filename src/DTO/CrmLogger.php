<?php


namespace Tvision\CrmLoggerBundle\DTO;

use Tvision\CrmLoggerBundle\RabbitMqMessage;

/**
 * Class CrmLogger
 * @package Tvision\CrmLoggerBundle\DTO
 */
class CrmLogger extends RabbitMqMessage
{
    const MESSAGE_TYPE = 'CrmLogger';

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $email;

    /**
     * @var mixed
     */
    private $date;


    /**
     * CrmLogger constructor.
     * @param $sessionId
     * @param $event
     * @param $data
     * @param $email
     * @param $date
     */
    public function __construct(
        $sessionId,
        $event,
        $data,
        $email,
        $date
    )
    {
        $this->sessionId = $sessionId;
        $this->event = $event;
        $this->data = $data;
        $this->email = $email;
        $this->date = ($date instanceof \DateTime) ? $date->format('Y-m-d H:i:s') : $date;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public static function fromArray(array $crmLogger)
    {
        return new self(
            $crmLogger['sessionId'],
            $crmLogger['event'],
            $crmLogger['data'],
            $crmLogger['email'],
            $crmLogger['date']
        );
    }

    public function toArray()
    {
        $crmLoggerArray = array(
            'sessionId' => $this->getSessionId(),
            'event' => $this->getEvent(),
            'data' => $this->getData(),
            'email' => $this->getEmail(),
            'date' => $this->getDate()
        );

        return $crmLoggerArray;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toRabbiMqMessage()
    {
        $array = $this->toArray();
        $array[self::MESSAGE_TYPE_INDEX] = self::MESSAGE_TYPE;

        return json_encode($array);
    }
}