<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Client;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;

abstract class DecoratorElasticClient implements ElasticClientInterface
{
    /** 
     * @var LoggerInterface $logger
    */
    protected $logger;

    /** 
     * @var ElasticClientInterface $elasticClient
    */
    protected $elasticClientDecorated;

    public function __construct(LoggerInterface $logger, ElasticClientInterface $elasticClient) {
        $this->logger = $logger;
        $this->elasticClientDecorated = $elasticClient;
    }
    
    /**
     * {@inheritDoc}
     */
    public function post(Envelope $envelope, string $index): ?array
    {
        return $this->elasticClientDecorated->post($envelope, $index);
    }
}
