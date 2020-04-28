<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Tests\Client\Behavior;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior\RetryElasticClientBehaviorFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\RetryElasticClientBehavior;

class RetryElasticClientBehaviorFactoryTest extends TestCase
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
     * @var RetryElasticClientBehaviorFactory $retryElasticClient
     */
    private $retryElasticClient;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->elasticClient = $this->getMockBuilder(ElasticClientInterface::class)->getMock();

        $this->retryElasticClient = new RetryElasticClientBehaviorFactory();
    }

    public function testPriority()
    {
        $monitoring = RetryElasticClientBehaviorFactory::priority(5);

        $this->assertEquals(5, $monitoring);
    }
    
    public function testCreateDecoratedElasticClient()
    {
        $options['retry'] = [
            "number" => 5,
            "timeBeforeRetry" => 5,
        ];
        
        $retryElasticClient = $this->retryElasticClient->createDecoratedElasticClient($this->logger, $this->elasticClient, $options);
        
        $this->assertInstanceOf(RetryElasticClientBehavior::class, $retryElasticClient);
    }


    public function testSupport()
    {
        $options["retry"] = [
            "enabled" => true
        ];
        $result = $this->retryElasticClient->support($options);

        $this->assertTrue($result);
    }

    public function testNotSupport()
    {
        $options["retry"] = [
            "enabled" => false
        ];
        $result = $this->retryElasticClient->support($options);

        $this->assertFalse($result);
    }
}
