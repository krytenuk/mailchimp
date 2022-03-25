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
     * @var float
     */
    private float $avgOpenRate = 0.00;

    /**
     *
     * @var float
     */
    private float $avgClickRate = 0.00;

    public function getId()
    {
        return null; // not used, required by EntityInterface
    }

    /**
     *
     * @return number
     */
    public function getAvgOpenRate(): float
    {
        return (float) $this->avgOpenRate;
    }

    /**
     *
     * @return number
     */
    public function getAvgClickRate(): float
    {
        return (float) $this->avgClickRate;
    }

    /**
     * Convert Members object to array for use in mailchimp API
     * @return array
     */
    public function toArray()
    {
        return []; // not used, required by EntityInterface
    }

}
