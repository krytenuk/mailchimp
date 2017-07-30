<?php

namespace FwsMailchimp\Entities;

/**
 * Description of MemberStats
 *
 * @author User
 */
class MemberStats implements EntityInterface
{

    /**
     *
     * @var number
     */
    private $avgOpenRate;

    /**
     *
     * @var number
     */
    private $avgClickRate;

    public function getId()
    {
        return NULL; // not used, required by EntityInterface
    }

    /**
     *
     * @return number
     */
    public function getAvgOpenRate()
    {
        return $this->avgOpenRate;
    }

    /**
     *
     * @return number
     */
    public function getAvgClickRate()
    {
        return $this->avgClickRate;
    }

    /**
     * Convert Members object to array for use in mailchimp api
     * @return array
     */
    public function toArray()
    {
        return array(); // not used, required by EntityInterface
    }

}
