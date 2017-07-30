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
    private $latitude;

    /**
     *
     * @var float
     */
    private $longitude;

    /**
     *
     * @var integer
     */
    private $gmtoff;

    /**
     *
     * @var integer
     */
    private $dstoff;

    /**
     *
     * @var string
     */
    private $countryCode;

    /**
     *
     * @var string
     */
    private $timezone;

    public function __construct()
    {
        $this->latitude = 0;
        $this->longitude = 0;
    }

    public function getId()
    {
        // not used, required by EntityInterface
    }

    /**
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     *
     * @return integer
     */
    public function getGmtoff()
    {
        return $this->gmtoff;
    }

    /**
     *
     * @return integer
     */
    public function getDstoff()
    {
        return $this->dstoff;
    }

    /**
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     *
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     *
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function toArray()
    {
        return array(
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        );
    }

}
