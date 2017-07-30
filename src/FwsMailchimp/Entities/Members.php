<?php

namespace FwsMailchimp\Entities;

use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Locations as LocationEntity;
use FwsMailchimp\Entities\MemberStats as MemberStatsEntity;
use FwsMailchimp\Entities\Languages as LanguageEntity;
use DateTime;
use FwsMailchimp\Exception\IncorrectStatusException;
use FwsMailchimp\Exception\IncorrectTypeException;

/**
 * Members Entity
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Members implements EntityInterface
{

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var string
     */
    private $emailAddress;

    /**
     *
     * @var array
     */
    private $mergeFields;

    /**
     *
     * @var string
     */
    private $uniqueEmailId;

    /**
     *
     * @var string
     */
    private $emailType;

    /**
     *
     * @var string
     */
    private $status;

    /**
     *
     * @var ArrayCollection
     */
    private $interestCategories;

    /**
     *
     * @var array
     */
    private $interests;

    /**
     *
     * @var MemberStatsEntity
     */
    private $stats;

    /**
     *
     * @var string
     */
    private $ipSignup;

    /**
     *
     * @var DateTime|NULL
     */
    private $timestampSignup;

    /**
     *
     * @var string
     */
    private $ipOpt;

    /**
     *
     * @var DateTime|NULL
     */
    private $timestampOpt;

    /**
     *
     * @var integer
     */
    private $memberRating;

    /**
     *
     * @var DateTime|NULL
     */
    private $lastChanged;

    /**
     *
     * @var LanguageEntity
     */
    private $language;

    /**
     *
     * @var boolean
     */
    private $vip;

    /**
     *
     * @var string
     */
    private $emailClient;

    /**
     *
     * @var LocationEntity
     */
    private $location;

    /**
     *
     * @var string
     */
    private $listId;

    /**
     *
     * @var array
     */
    private $validSubscribeStatus = array(
        'subscribed',
        'unsubscribed',
        'cleaned',
        'pending',
        'transactional',
    );

    private $validEmailTypes = array(
        'html',
        'text',
    );


    public function __construct()
    {
        $this->language = new LanguageEntity();
        $this->location = new LocationEntity();
        $this->stats = new MemberStatsEntity();
        $this->ipSignup = '';
        $this->ipOpt = '';
        $this->timestampSignup = '';
        $this->timestampOpt = '';
        $this->lastChanged = '';
    }

    /**
     *
     * @return array
     */
    public function getValidSubscribeStates()
    {
        return $this->validSubscribeStatus;
    }

    /**
     *
     * @return array
     */
    public function getValidEmailTypes()
    {
        return $this->validEmailTypes;
    }

    /**
     *
     * @return array
     */
    public function getValidLanguageCodes()
    {
        return $this->validLanguageCodes;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     *
     * @return array
     */
    public function getMergeFields()
    {
        return $this->mergeFields;
    }

    /**
     *
     * @return string
     */
    public function getUniqueEmailId()
    {
        return $this->uniqueEmailId;
    }

    /**
     *
     * @return string
     */
    public function getEmailType()
    {
        return $this->emailType;
    }

    /**
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getInterestCategories()
    {
        return $this->interestCategories;
    }

    /**
     *
     * @return array
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     *
     * @return MemberStatsEntity
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     *
     * @return string
     */
    public function getIpSignup()
    {
        return $this->ipSignup;
    }

    /**
     *
     * @return DateTime|NULL
     */
    public function getTimestampSignup()
    {
        return $this->timestampSignup;
    }

    /**
     *
     * @return string
     */
    public function getIpOpt()
    {
        return $this->ipOpt;
    }

    /**
     *
     * @return DateTime|NULL
     */
    public function getTimestampOpt()
    {
        return $this->timestampOpt;
    }

    /**
     *
     * @return integer
     */
    public function getMemberRating()
    {
        return (int) $this->memberRating;
    }

    /**
     *
     * @return DateTime|NULL
     */
    public function getLastChanged()
    {
        return $this->lastChanged;
    }

    /**
     *
     * @return LanguageEntity
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @return boolean
     */
    public function isVip()
    {
        return $this->vip;
    }

    /**
     *
     * @return string
     */
    public function getEmailClient()
    {
        return $this->emailClient;
    }

    /**
     *
     * @return LocationEntity
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }

    /**
     *
     * @param string $emailAddress
     * @return \FwsMailchimp\Entities\Members
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     *
     * @param array $mergeFields
     * @return \FwsMailchimp\Entities\Members
     */
    public function setMergeFields(Array $mergeFields)
    {
        $this->mergeFields = $mergeFields;
        return $this;
    }

    /**
     *
     * @param type $emailType
     * @return \FwsMailchimp\Entities\Members
     * @throws IncorrectTypeException
     */
    public function setEmailType($emailType)
    {
        if (in_array($emailType, $this->validEmailTypes)) {
            $this->emailType = $emailType;
        return $this;
        } else {
            throw new IncorrectTypeException(sprintf('Invalid email type, expected %s'), implode(', ', $this->validEmailTypes));
        }
    }

    /**
     *
     * @param string $status
     * @return \FwsMailchimp\Entities\Members
     * @throws IncorrectStatusException
     */
    public function setStatus($status)
    {
        if (in_array($status, $this->validSubscribeStatus)) {
            $this->status = $status;
            return $this;
        } else {
            throw new IncorrectStatusException(sprintf('Invalid status, expected %s'), implode(', ', $this->validSubscribeStatus));
        }
    }

    /**
     * Sets the group interest to TRUE to show member interested
     * @param string $interestId
     * @return \FwsMailchimp\Entities\Members
     */
    public function setInterested($interestId)
    {
        $this->interests[$interestId] = TRUE;
        return $this;
    }

    /**
     * Sets the group interest to FALSE to show member not interested
     * @param string $interestId
     * @return \FwsMailchimp\Entities\Members
     */
    public function unsetInterested($interestId)
    {
        $this->interests[$interestId] = FALSE;
        return $this;
    }

    /**
     *
     * @param string $ipSignup
     * @return \FwsMailchimp\Entities\Members
     */
    public function setIpSignup($ipSignup)
    {
        $this->ipSignup = $ipSignup;
        return $this;
    }

    /**
     *
     * @param DateTime||string $timestampSignup
     * @return \FwsMailchimp\Entities\Members
     */
    public function setTimestampSignup($timestampSignup)
    {
        if ($timestampSignup instanceof DateTime) {
            $this->timestampSignup = $timestampSignup;
        } elseif (is_string($timestampSignup)) {
            $this->timestampSignup = new DateTime($timestampSignup);
        } else {
            throw new IncorrectTypeException(sprintf(
                    'Invalid timestamp, expected string recieved %s',
                    is_object($timestampSignup) ? get_class($timestampSignup) : gettype($timestampSignup)));
        }
        return $this;
    }

    /**
     *
     * @param string $ipOpt
     * @return \FwsMailchimp\Entities\Members
     */
    public function setIpOpt($ipOpt)
    {
        $this->ipOpt = $ipOpt;
        return $this;
    }

    /**
     *
     * @param string $timestampOpt
     * @return \FwsMailchimp\Entities\Members
     */
    public function setTimestampOpt($timestampOpt)
    {
        if ($timestampOpt instanceof DateTime) {
            $this->timestampOpt = $timestampOpt;
        } elseif (is_string($timestampOpt)) {
            $this->timestampOpt = new DateTime($timestampOpt);
        } else {
            throw new IncorrectTypeException(sprintf(
                    'Invalid timestamp, expected string recieved %s',
                    is_object($timestampOpt) ? get_class($timestampOpt) : gettype($timestampOpt)));
        }
        return $this;
    }

    /**
     *
     * @param LanguageEntity $language
     * @return \FwsMailchimp\Entities\Members
     */
    public function setLanguage(LanguageEntity $language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     *
     * @param boolean $vip
     * @return \FwsMailchimp\Entities\Members
     */
    public function setVip($vip)
    {
        $this->vip = (bool) $vip;
        return $this;
    }

    /**
     *
     * @param LocationEntity $location
     * @return \FwsMailchimp\Entities\Members
     */
    public function setLocation(LocationEntity $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Convert Members object to array for use in mailchimp api
     * @return array
     */
    public function toArray()
    {
        $array = array(
            'email_address' => $this->emailAddress,
            'email_type' => $this->emailType,
            'status' => $this->status,
            'merge_fields' => $this->mergeFields,
            'interests' => $this->interests,
            'language' => $this->language->getId(),
            'vip' => $this->vip,
            'location' => $this->location->toArray(),
            'ip_signup' => $this->ipSignup,
            'ip_opt' => $this->ipOpt,
        );
        if ($this->timestampSignup instanceof DateTime) {
            $array['timestamp_signup'] = $this->formatDate($this->timestampSignup);
        } else {
            $array['timestamp_signup'] = $this->timestampSignup;
        }
        if ($this->timestampOpt instanceof DateTime) {
            $array['timestamp_opt'] = $this->formatDate($this->timestampOpt);
        } else {
            $array['timestamp_opt'] = $this->timestampOpt;
        }
        return $array;
    }

    /**
     * Format date from \DateTime object
     * @param DateTime $date
     * @return string
     */
    private function formatDate(DateTime $date)
    {
        return $date->format(DATE_ATOM);
    }

}
