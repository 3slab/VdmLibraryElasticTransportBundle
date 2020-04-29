<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Vdm\Bundle\LibraryBundle\Monitoring\StatsStorageInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\Event\ElasticClientReceivedEvent;
use Vdm\Bundle\LibraryElasticTransportBundle\EventListener\MonitoringElasticClientSubscriber;

class MonitoringElasticClientSubscriberTest extends TestCase
{
    /**
     * @var StatsStorageInterface $statsStorageInterface
     */
    private $statsStorageInterface;

    protected function getSubscriber(array $calls)
    {
        $this->statsStorageInterface = $this->getMockBuilder(StatsStorageInterface::class)->getMock();
        $this->responseInterface = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->statsStorageInterface->expects($calls['sendStat'])->method('sendStat');

        return new MonitoringElasticClientSubscriber($this->statsStorageInterface, new NullLogger());
    }

    /**
     * @dataProvider dataProviderTestOnElasticClientReceivedEvent
     */
    public function testOnElasticClientReceivedEvent($methodCall)
    {
        $listener = $this->getSubscriber(['sendStat' => $methodCall]);

        $response = [
            '_index' => 1,
            'result' => 'created',
            '_shards' => [
                'successful' => true
            ],
        ];
        $event = new ElasticClientReceivedEvent($response);

        $listener->onElasticClientReceivedEvent($event);
    }

    public function dataProviderTestOnElasticClientReceivedEvent()
    {
        yield [
            $this->once()
        ];
    }
}
