<?php

namespace DomUdall\ExtraLoggingBundle\Processor;

use Symfony\Component\HttpFoundation\Session;

/**
 * A slight rework of the Session/Request token example from the Symfony2 Cookbook
 *
 * http://symfony.com/doc/current/cookbook/logging/monolog.html#adding-some-extra-data-in-the-log-messages
 */
class SessionProcessor
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $token;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Adds the session token to the log record.
     *
     * @param Array $record Current log record
     * @return Array $record Log record with additional data
     */
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
