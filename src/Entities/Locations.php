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
     * @var int|null
     */
    private ?int $gmtoff = null;

    /**
     *
     * @var int|null
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
     * @return null
     */
    public function getId(): mixed
    {
        return null;
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
     * @return int|null
     */
    public function getGmtoff(): ?int
    {
        return $this->gmtoff;
    }

    /**
     *
     * @return int|null
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
     * Used by Mailchimp API
     * @return array{latitude: float, longitude: float}
     */
    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

}
