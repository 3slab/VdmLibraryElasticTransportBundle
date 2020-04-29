<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Client\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ElasticClientReceivedEvent extends Event
{
    /**
     * @var array|null $elasticResponse
     */
    private $elasticResponse;

    /**
     * ElasticClientReceivedEvent constructor
     */
    public function __construct(?array $elasticResponse)
    {
        $this->elasticResponse = $elasticResponse;
    }

    /**
     * @return array|null
     */
    public function getElasticResponse(): ?array
    {
        return $this->elasticResponse;
    }
}
