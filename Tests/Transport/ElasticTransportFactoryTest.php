<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Transport;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\ElasticClientBehaviorFactoryRegistry;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClient;
use Vdm\Bundle\LibraryElasticTransportBundle\Transport\ElasticTransport;

class ElasticTransportFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $logger
     */
    private $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $serializer
     */
    private $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $elasticClientFactory
     */
    private $elasticClientFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $elasticClientBehaviorFactoryRegistry
     */
    private $elasticClientBehaviorFactoryRegistry;

    /**
     * @var ElasticClient $elasticClient
     */
    private $elasticClient;

    /**
     * @var ElasticTransportFactory $elasticTransportFactory
     */
    private $elasticTransportFactory;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();
        $this->elasticClient = new ElasticClient('elasticsearch', 9200, '', '', 'http', $this->logger);
        $this->elasticClientFactory = $this
                ->getMockBuilder(ElasticClientFactory::class)
                ->setConstructorArgs([$this->logger])
                ->getMock();
        $this->elasticClientBehaviorFactoryRegistry = $this
                        ->getMockBuilder(ElasticClientBehaviorFactoryRegistry::class)
                        ->setConstructorArgs([$this->logger])
                        ->setMethods(['create'])
                        ->getMock();
        
        $this->elasticClientBehaviorFactoryRegistry->method('create')->willReturn($this->elasticClient);
        $this->elasticTransportFactory = new ElasticTransportFactory($this->logger, $this->elasticClientFactory, $this->elasticClientBehaviorFactoryRegistry);
    }

    public function testCreateTransport()
    {
        $dsn = "elasticsearch://localhost:9200";
        $options = [
            'es_conf' => [],
        ];
        $transport = $this->elasticTransportFactory->createTransport($dsn, $options, $this->serializer);

        $this->assertInstanceOf(ElasticTransport::class, $transport);
    }

    /**
     * @dataProvider dataProviderTestSupport
     */
    public function testSupports($dsn, $value)
    {
        $bool = $this->elasticTransportFactory->supports($dsn, []);

        $this->assertEquals($bool, $value);
    }

    public function dataProviderTestSupport()
    {
        yield [
            "elasticsearch://localhost:9200",
            true
        ];
        yield [
            "https://ipconfig.io/json",
            false
        ];

    }
}
