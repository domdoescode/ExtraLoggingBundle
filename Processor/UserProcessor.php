<?php

namespace DomUdall\ExtraLoggingBundle\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UserProcessor
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $user;

    protected $record = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(array $record)
    {
        if (null === $this->user) {
            $securityContext = $this->container->get("security.context");
            if (($securityContext->getToken() !== null) && ($securityContext->getToken()->getUser() instanceof \Symfony\Component\Security\Core\User\AdvancedUserInterface)) {
                $this->user = $securityContext->getToken()->getUser();
                $this->record['user']['username'] = $this->user->getUsername();
                $this->record['user']['roles'] = $this->user->getRoles();
                $this->record['user']['is_account_non_expired'] = $this->user->isAccountNonExpired();
                $this->record['user']['is_account_non_locked'] = $this->user->isAccountNonLocked();
                $this->record['user']['is_credentials_non_expired'] = $this->user->isCredentialsNonExpired();
                $this->record['user']['is_enabled'] = $this->user->isEnabled();

                $this->setAdditionalFields();
            }
        }

        return array_merge($record, $this->record);
    }

    public function setAdditionalFields()
    {
    }
}