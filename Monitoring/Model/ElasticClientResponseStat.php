<?php

/**
 * @package    3slab/VdmLibraryElasticTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryElasticTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryElasticTransportBundle\Monitoring\Model;

use Vdm\Bundle\LibraryBundle\Monitoring\Model\StatModelInterface;
use Vdm\Bundle\LibraryBundle\Monitoring\Model\StatItems;

class ElasticClientResponseStat implements StatModelInterface
{
    /**
     * @var int
     */
    protected $success;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $response;

    /**
     * ElasticClientResponseStat constructor.
     *
     * @param int $success
     * @param string $index
     * @param string $response
     */
    public function __construct(int $success, string $index, string $response)
    {
        $this->success = $success;
        $this->index = $index;
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getSuccess(): int
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    public function getStats(): array
    {
        $tags = [
            "index" => $this->getIndex(),
            "response" => $this->getResponse(),
        ];

        $array[] = new StatItems('increment', 'elastic.response', $this->getSuccess(), $tags);

        return  $array;
    }
}
