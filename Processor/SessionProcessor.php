<?php

namespace DomUdall\ExtraLoggingBundle\Processor;

use Symfony\Component\HttpFoundation\Session;

class SessionProcessor
{
    /**
     * @var Session
     */
    private $session;

    private $token;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(array $record)
    {
        if (null === $this->token) {
            try {
                $this->token = substr($this->session->getId(), 0, 8);
            } catch (\RuntimeException $e) {
                $this->token = '????????';
            }
            $this->token .= '-' . substr(uniqid(), -8);
        }
        $record['session']['token'] = $this->token;

        return $record;
    }
}