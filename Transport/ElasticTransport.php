<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Transport;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;

class ElasticTransport implements TransportInterface
{
    /** 
     * @var LoggerInterface $logger
    */
    private $logger;

    /** 
     * @var ElasticClientInterface $elasticClient
    */
    private $elasticClient;

    /** 
     * @var array $options
    */
    private $options;

    public function __construct(
        LoggerInterface $logger, 
        ElasticClientInterface $elasticClient,
        string $dsn,
        array $options
    )
    {
        $this->logger = $logger;
        $this->elasticClient = $elasticClient;
        $this->dsn = $dsn;
        $this->options = $options;
    }

    /**
     * @codeCoverageIgnore
     */
    public function get(): iterable
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function ack(Envelope $envelope): void
    {
    }
    
    /**
     * @codeCoverageIgnore
     */
    public function reject(Envelope $envelope): void
    {        
    }

    public function send(Envelope $envelope): Envelope
    {
        $this->elasticClient->post($envelope, $this->options['index']);

        return $envelope;
    }
}
