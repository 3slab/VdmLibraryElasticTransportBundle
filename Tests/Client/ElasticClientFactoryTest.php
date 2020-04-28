<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Tests\Client;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientFactory;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClient;

class ElasticClientFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $logger
     */
    private $logger;

    /**
     * @var ElasticClientFactory $elasticClientFactory
     */
    private $elasticClientFactory;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->elasticClientFactory = new ElasticClientFactory($this->logger);
    }

    public function testCreate()
    {    
        $elasticClient = $this->elasticClientFactory->create("elasticsearch://elasticsearch:9200", []);

        $this->assertInstanceOf(ElasticClient::class, $elasticClient);
    }
}
