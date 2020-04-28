<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Client\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ElasticClientErrorEvent extends Event
{
    public function getError()
    {
        return 1;
    }
}
