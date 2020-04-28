<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Transport;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClient;
use Vdm\Bundle\LibraryBundle\Model\Message;

class ElasticTransportTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $logger
     */
    private $logger;

    /**
     * @var ElasticClient $elasticClient
     */
    private $elasticClient;

    /**
     * @var ElasticTransport $elasticTransport
     */
    private $elasticTransport;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->elasticClient = $this->getMockBuilder(ElasticClient::class)->disableOriginalConstructor()->getMock();
        $this->elasticTransport = new ElasticTransport($this->logger, $this->elasticClient, "elasticsearch://localhost:9200", ['index' => 'test']);
    }

    public function testSend()
    {
        $envelope = new Envelope(new Message('test'));

        $envelopeResponse = $this->elasticTransport->send($envelope);

        $this->assertInstanceOf(Envelope::class, $envelopeResponse);
    }
}
