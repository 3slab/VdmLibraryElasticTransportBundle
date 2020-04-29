<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Transport;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\ElasticClientBehaviorFactoryRegistry;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientFactoryInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;

class ElasticTransportFactory implements TransportFactoryInterface
{
    private const DSN_PROTOCOL_ES = 'elasticsearch://';

    private const DSN_PROTOCOLS = [
        self::DSN_PROTOCOL_ES
    ];

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var ElasticClientFactoryInterface $elasticClientFactory
     */
    private $elasticClientFactory;

    /**
     * @var ElasticClientInterface $elasticClient
     */
    private $elasticClient;

    /**
     * @var ElasticClientBehaviorFactoryRegistry $elasticClientBehaviorFactoryRegistry
     */
    private $elasticClientBehaviorFactoryRegistry;

    public function __construct(
        LoggerInterface $logger, 
        ElasticClientFactoryInterface $elasticClientFactory,
        ElasticClientBehaviorFactoryRegistry $elasticClientBehaviorFactoryRegistry
    )
    {
        $this->logger = $logger;
        $this->elasticClientFactory = $elasticClientFactory;
        $this->elasticClientBehaviorFactoryRegistry = $elasticClientBehaviorFactoryRegistry;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        $es_options = $options['es_conf'];

        $this->elasticClient = $this->elasticClientFactory->create($dsn, $es_options);
        $this->elasticClient = $this->elasticClientBehaviorFactoryRegistry->create($this->elasticClient, $options);

        return new ElasticTransport($this->logger, $this->elasticClient, $dsn, $es_options);
    }

    public function supports(string $dsn, array $options): bool
    {
        foreach (self::DSN_PROTOCOLS as $protocol) {
            if (0 === strpos($dsn, $protocol)) {
                return true;
            }
        }
        return false;
    }
}
