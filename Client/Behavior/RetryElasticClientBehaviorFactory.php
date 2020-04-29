<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Client\Behavior;

use Psr\Log\LoggerInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\ElasticClientInterface;
use Vdm\Bundle\LibraryElasticTransportBundle\Client\RetryElasticClientBehavior;

class RetryElasticClientBehaviorFactory implements ElasticClientBehaviorFactoryInterface
{
    public static function priority(int $priority = 0)
    {
        return $priority;
    }

    public function createDecoratedElasticClient(LoggerInterface $logger, ElasticClientInterface $elasticClient, array $options)
    {
        $number = 5;

        if (isset($options['retry']['number'])) {
            $number = $options['retry']['number'];
        }

        if (isset($options['retry']['timeBeforeRetry'])) {
            $timeBeforeRetry = $options['retry']['timeBeforeRetry'];
        }

        return new RetryElasticClientBehavior($logger, $elasticClient, $number, $timeBeforeRetry);
    }

    public function support(array $options)
    {
        if (isset($options['retry']['enabled']) && $options['retry']['enabled'] === true) {
            return true;
        }

        return false;
    }
}
