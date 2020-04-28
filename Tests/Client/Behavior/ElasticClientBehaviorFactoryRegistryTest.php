<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Tests\Client\Behavior;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\ElasticClientBehaviorFactoryRegistry;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\RetryElasticClientBehaviorFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\MonitoringElasticClientBehaviorFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\MonitoringElasticClientBehavior;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClient;

class ElasticClientBehaviorFactoryRegistryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $logger
     */
    private $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ElasticClient $elasticClient
     */
    private $elasticClient;

    /**
     * @var ElasticClientBehaviorFactoryRegistry $elasticClientBehavior
     */
    private $elasticClientBehavior;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->elasticClient = new ElasticClient('elasticsearch', 9200, '', '', 'http', $this->logger);

        $this->elasticClientBehavior = new ElasticClientBehaviorFactoryRegistry($this->logger);
    }

    public function testAddFactory()
    {
        $retryElasticClientBehaviorFactory = new RetryElasticClientBehaviorFactory();
        $monitoringrElasticClientBehaviorFactory = new MonitoringElasticClientBehaviorFactory($this->eventDispatcher);
        $priorityRetry = 100;
        $priorityMonitoring = 0;

        $property = new \ReflectionProperty(ElasticClientBehaviorFactoryRegistry::class, 'elasticClientBehavior');
        $property->setAccessible(true);
        $value = $property->getValue($this->elasticClientBehavior);
        $this->assertEmpty($value);
        try {
            $this->elasticClientBehavior->addFactory($retryElasticClientBehaviorFactory, $priorityRetry);
            $this->elasticClientBehavior->addFactory($monitoringrElasticClientBehaviorFactory, $priorityMonitoring);
        } catch (\Exception $exception) {

        }

        $value = $property->getValue($this->elasticClientBehavior);
        $this->assertNotEmpty($value);
        $this->assertCount(2, $value);
    }

    public function testCreateNotSupport()
    {
        $elasticClient = $this->elasticClientBehavior->create($this->elasticClient, ['monitoring' => ['enabled' => true]]);

        $this->assertInstanceOf(ElasticClient::class, $elasticClient);
    }

    public function testCreateSupport()
    {
        $monitoringrElasticClientBehaviorFactory = new MonitoringElasticClientBehaviorFactory($this->eventDispatcher);
        $priorityMonitoring = 0;
        $this->elasticClientBehavior->addFactory($monitoringrElasticClientBehaviorFactory, $priorityMonitoring);
        $elasticClient = $this->elasticClientBehavior->create($this->elasticClient, ['monitoring' => ['enabled' => true]]);

        $this->assertInstanceOf(MonitoringElasticClientBehavior::class, $elasticClient);
    }
}
