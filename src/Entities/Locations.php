<?php

namespace FwsMailchimp\Entities;

/**
 * Locations Entity
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Locations implements EntityInterface
{

    /**
     *
     * @var float
     */
    private float $latitude = 0.00;

    /**
     *
     * @var float
     */
    private float $longitude = 0.00;

    /**
     *
     * @var int
     */
    private ?int $gmtoff = null;

    /**
     *
     * @var int
     */
    private ?int $dstoff = null;

    /**
     *
     * @var string
     */
    private string $countryCode = '';

    /**
     *
     * @var string
     */
    private string $timezone = '';

    /**
     * 
     * @return void
     */
    public function getId(): void
    {
        // not used, required by EntityInterface
    }

    /**
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     *
     * @return integer
     */
    public function getGmtoff(): ?int
    {
        return $this->gmtoff;
    }

    /**
     *
     * @return integer
     */
    public function getDstoff(): ?int
    {
        return $this->dstoff;
    }

    /**
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * 
     * @param float $latitude
     * @return Locations
     */
    public function setLatitude(float $latitude): Locations
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * 
     * @param float $longitude
     * @return Locations
     */
    public function setLongitude(float $longitude): Locations
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Get entity as an array as used by Mailchimp API
     * @return array
     */
    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

}
