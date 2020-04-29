<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Client;

use Psr\Log\LoggerInterface;

class ElasticClientFactory implements ElasticClientFactoryInterface
{
    /**
     * @var LoggerInterface $messengerLogger
     */
    private $logger;

    public function __construct(LoggerInterface $messengerLogger) {
        $this->logger = $messengerLogger;
    }

    public function create(string $dsn, ?array $options): ElasticClient
    {
        $dsn_regex = '/^((?P<driver>\w+):\/\/)?((?P<user>\w+)?(:(?P<password>\w+))?@)?(?P<host>[\w\-\.]+)(:(?P<port>\d+))?$/Uim';
        
        $scheme =  (isset($options['scheme'])) ? $options['scheme'] : "https";
        preg_match($dsn_regex, $dsn, $result);

        return new ElasticClient($result['host'], $result['port'], $result['user'], $result['password'], $scheme, $this->logger);   
    }
}
