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
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\MonitoringElasticClientBehaviorFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\MonitoringElasticClientBehavior;

class MonitoringElasticClientBehaviorFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $logger
     */
    private $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $elasticClient
     */
    private $elasticClient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var MonitoringElasticClientBehavior $monitoringElasticClient
     */
    private $monitoringElasticClient;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->elasticClient = $this->getMockBuilder(ElasticClientInterface::class)->getMock();
        $this->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        $this->monitoringElasticClient = new MonitoringElasticClientBehaviorFactory($this->eventDispatcher);
    }
    
    public function testPriority()
    {
        $monitoring = MonitoringElasticClientBehaviorFactory::priority(5);

        $this->assertEquals(5, $monitoring);
    }

    public function testCreateDecoratedElasticClient()
    {
        $monitoringElasticClient = $this->monitoringElasticClient->createDecoratedElasticClient($this->logger, $this->elasticClient, []);
        
        $this->assertInstanceOf(MonitoringElasticClientBehavior::class, $monitoringElasticClient);
    }

    public function testSupport()
    {
        $options["monitoring"] = [
            "enabled" => true
        ];
        $result = $this->monitoringElasticClient->support($options);

        $this->assertTrue($result);
    }

    public function testNotSupport()
    {
        $options["monitoring"] = [
            "enabled" => false
        ];
        $result = $this->monitoringElasticClient->support($options);

        $this->assertFalse($result);
    }
}
