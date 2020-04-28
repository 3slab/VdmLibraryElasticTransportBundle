<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Tests\Client;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Elasticsearch\Client;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\DecoratorElasticClient;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClient;
use Vdm\Bundle\LibraryBundle\Model\Message;

class DecoratorElasticClientTest extends TestCase
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
     * @var DecoratorElasticClient $decoratorElasticClient
     */
    private $decoratorElasticClient;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->setMethods(['index'])->getMock();
        $this->elasticClient = $this->getMockBuilder(ElasticClient::class)->disableOriginalConstructor()->setMethods(['post'])->getMock();
        $this->elasticClient->setClient($this->client);
        $this->decoratorElasticClient = $this->getMockForAbstractClass(DecoratorElasticClient::class, [$this->logger, $this->elasticClient]);
    }

    public function testPost()
    {       
        $index = 'test';
        $body = 'test';

        $params = [
            'index' => $index,
            'body'  => (is_array($body)) ? $body : [ 'message' => $body ]
        ];
        $reponseClient = $this->client->index($params);

        $envelope = new Envelope(new Message($body));
        $reponsePost = $this->decoratorElasticClient->post($envelope, $index);

        $this->assertSame($reponseClient, $reponsePost);
    }
}
