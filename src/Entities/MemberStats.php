<?php

namespace FwsMailchimp\Entities;

/**
 * Description of MemberStats
 *
 * @author User
 */
class MemberStats implements EntityInterface
{

    private float $avgOpenRate = 0.00;

    private float $avgClickRate = 0.00;

    public function getId(): int|null
    {
        return null; // not used, required by EntityInterface
    }

    /**
     * Get average open rate
     * @return float
     */
    public function getAvgOpenRate(): float
    {
        return $this->avgOpenRate;
    }

    /**
     * Get average click rate
     * @return float
     */
    public function getAvgClickRate(): float
    {
        return (float) $this->avgClickRate;
    }

    /**
     * Used for Mailchimp API
     * @return array
     */
    public function toArray(): array
    {
        return []; // not used, required by EntityInterface
    }

}
