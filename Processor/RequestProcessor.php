<?php

namespace DomUdall\ExtraLoggingBundle\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RequestProcessor
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(array $record)
    {
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

        return $record;
    }
}