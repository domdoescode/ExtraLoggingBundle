<?php

namespace DomUdall\ExtraLoggingBundle\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;

class RequestProcessor
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Adds request-base data retrieved from Symfony2's Request service to the log record.
     *
     * @param Array $record Current log record
     * @return Array $record Log record with additional data
     */
    public function __invoke(array $record)
    {
        try {
            if (null === $this->request) {
                $this->request = $this->container->get("request");
            }
    
            $record['request']['base_url'] = $this->request->getBaseUrl();
            $record['request']['scheme'] = $this->request->getScheme();
            $record['request']['port'] = $this->request->getPort();
            $record['request']['request_uri'] = $this->request->getRequestUri();
            $record['request']['uri'] = $this->request->getUri();
            $record['request']['query_string'] = $this->request->getQueryString();
            $record['request']['_route'] = $this->request->get("_route");
        } catch (InactiveScopeException $e) {
            // This stops errors occuring in the CLI
        }

        return $record;
    }
}
