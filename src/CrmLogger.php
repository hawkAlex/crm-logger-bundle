<?php


namespace Tvision\CrmLoggerBundle;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Tvision\Bundle\UserBundle\Entity\User;
use Tvision\CrmLoggerBundle\DTO\CrmLogger as CrmLoggerDTO;

class CrmLogger
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var boolean
     */
    private $isLoggerActive;

    /**
     * @var Producer
     */
    private $rabbitMqProducer;

    /**
     * CrmLogger constructor.
     * @param Session $session
     * @param TokenStorage $tokenStorage
     * @param Producer $rabbitMqProducer
     * @param bool $isLoggerActive
     */
    public function __construct(
        Session $session,
        TokenStorage $tokenStorage,
        Producer $rabbitMqProducer,
        $isLoggerActive = false
    ){
        $this->session = $session;
        $this->isLoggerActive = $isLoggerActive;
        $this->tokenStorage = $tokenStorage;
        $this->rabbitMqProducer = $rabbitMqProducer;
    }

    /**
     * @param $event
     * @param $data
     * @param $email
     * @param bool $clear
     */
    public function write($event, $data, $email = null, $clear = false)
    {
        if (!$this->isLoggerActive) {

            return;
        }

        if (!$this->session->has('sessionUniqueId')) {
            $this->session->set('sessionUniqueId', md5(random_int(0, 99999) . microtime()));
        }

        $crmLogger = [
            'sessionId' => $this->session->get('sessionUniqueId'),
            'event' => $event,
            'data' => '\'' . json_encode($data) . '\'',
            'email' => $email != null ? $email : $this->getUserEmail(),
            'date' => new \DateTime()
        ];

        $crmLog = CrmLoggerDTO::fromArray($crmLogger);
        $rabbitMessage = $crmLog->toRabbiMqMessage();
        $this->rabbitMqProducer->setContentType('application/json');
        $this->rabbitMqProducer->publish($rabbitMessage);

        if ($clear) {
            $this->session->remove('sessionUniqueId');
        }
    }

    /**
     * @param $value
     * @return null
     */
    public function clear($value)
    {
        if (!isset($value) || !is_scalar($value)) {

            return null;
        }

        return $value;
    }

    /**
     * @return null|string
     */
    private function getUserEmail()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        /**
         * @var User $user
         */
        if (!is_object($user = $token->getUser())) {

            return null;
        }

        return $user->getEmail();
    }
}