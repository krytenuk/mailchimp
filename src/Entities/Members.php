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
    private string $id = '';

    /**
     *
     * @var string
     */
    private string $emailAddress = '';

    /**
     * 
     * @var array
     */
    private array $mergeFields = [];

    /**
     *
     * @var string
     */
    private string $uniqueEmailId = '';

    /**
     *
     * @var string
     */
    private string $emailType = '';

    /**
     *
     * @var string
     */
    private string $status = '';

    /**
     *
     * @var ArrayCollection
     */
    private ArrayCollection $interestCategories;

    /**
     *
     * @var array
     */
    private array $interests = [];

    /**
     *
     * @var MemberStatsEntity
     */
    private MemberStatsEntity $stats;

    /**
     *
     * @var string
     */
    private string $ipSignup = '';

    /**
     *
     * @var DateTime|string
     */
    private $timestampSignup = '';

    /**
     *
     * @var string
     */
    private string $ipOpt = '';

    /**
     *
     * @var DateTime|string
     */
    private $timestampOpt = '';

    /**
     * 
     * @var int|null
     */
    private ?int $memberRating;

    /**
     *
     * @var DateTime|string
     */
    private $lastChanged = '';

    /**
     *
     * @var LanguageEntity
     */
    private LanguageEntity $language;

    /**
     *
     * @var bool
     */
    private bool $vip = false;

    /**
     *
     * @var string
     */
    private string $emailClient = '';

    /**
     *
     * @var LocationEntity
     */
    private LocationEntity $location;

    /**
     *
     * @var string|null
     */
    private string $listId = '';

    /**
     *
     * @var array
     */
    private array $validSubscribeStatus = [
        'subscribed',
        'unsubscribed',
        'cleaned',
        'pending',
        'transactional',
    ];

    /**
     * 
     * @var array
     */
    private array $validEmailTypes = [
        'html',
        'text',
    ];

    public function __construct()
    {
        $this->interestCategories = new ArrayCollection();
        $this->location = new LocationEntity();
        $this->stats = new MemberStatsEntity();
        $this->language = new LanguageEntity();
    }

    /**
     *
     * @return array
     */
    public function getValidSubscribeStates(): array
    {
        return $this->validSubscribeStatus;
    }

    /**
     *
     * @return array
     */
    public function getValidEmailTypes(): array
    {
        return $this->validEmailTypes;
    }

    /**
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     *
     * @return array
     */
    public function getMergeFields(): array
    {
        return $this->mergeFields;
    }

    /**
     *
     * @return string
     */
    public function getUniqueEmailId(): string
    {
        return $this->uniqueEmailId;
    }

    /**
     *
     * @return string
     */
    public function getEmailType(): string
    {
        return $this->emailType;
    }

    /**
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getInterestCategories(): ArrayCollection
    {
        return $this->interestCategories;
    }

    /**
     *
     * @return array
     */
    public function getInterests(): array
    {
        return (array) $this->interests;
    }

    /**
     *
     * @return MemberStatsEntity
     */
    public function getStats(): MemberStatsEntity
    {
        return $this->stats;
    }

    /**
     *
     * @return string
     */
    public function getIpSignup(): string
    {
        return $this->ipSignup;
    }

    /**
     *
     * @return DateTime|null|string
     */
    public function getTimestampSignup()
    {
        return $this->timestampSignup;
    }

    /**
     *
     * @return string
     */
    public function getIpOpt(): string
    {
        return $this->ipOpt;
    }

    /**
     *
     * @return DateTime|null|string
     */
    public function getTimestampOpt()
    {
        return $this->timestampOpt;
    }

    /**
     *
     * @return integer
     */
    public function getMemberRating(): int
    {
        return (int) $this->memberRating;
    }

    /**
     *
     * @return DateTime|null|string
     */
    public function getLastChanged()
    {
        return $this->lastChanged;
    }

    /**
     *
     * @return LanguageEntity
     */
    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }

    /**
     *
     * @return boolean
     */
    public function isVip(): bool
    {
        return (bool) $this->vip;
    }

    /**
     *
     * @return string
     */
    public function getEmailClient(): string
    {
        return $this->emailClient;
    }

    /**
     *
     * @return LocationEntity
     */
    public function getLocation(): LocationEntity
    {
        return $this->location;
    }

    /**
     * 
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     *
     * @param string $emailAddress
     * @return Members
     */
    public function setEmailAddress(string $emailAddress): Members
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     *
     * @param array $mergeFields
     * @return Members
     */
    public function setMergeFields(array $mergeFields = []): Members
    {
        $this->mergeFields = $mergeFields;
        return $this;
    }

    /**
     *
     * @param string $emailType
     * @return \FwsMailchimp\Entities\Members
     * @throws IncorrectTypeException
     */
    public function setEmailType(string $emailType): Members
    {
        if (in_array($emailType, $this->validEmailTypes)) {
            $this->emailType = $emailType;
            return $this;
        }
        throw new IncorrectTypeException(sprintf('Invalid email type, expected %s'), implode(', ', $this->validEmailTypes));
    }

    /**
     *
     * @param string $status
     * @return \FwsMailchimp\Entities\Members
     * @throws IncorrectStatusException
     */
    public function setStatus(string $status): Members
    {
        if (in_array($status, $this->validSubscribeStatus)) {
            $this->status = $status;
            return $this;
        } else {
            throw new IncorrectStatusException(sprintf('Invalid status, expected %s'), implode(', ', $this->validSubscribeStatus));
        }
    }

    /**
     * Sets the group interest to true to show member interested
     * @param string|array $interest
     * @return \FwsMailchimp\Entities\Members
     */
    public function setInterested($interest): Members
    {
        if (is_string($interest) === true) {
            $interest = [$interest];
        }
        
        if (is_array($interest) === false) {
            return $this;
        }
        
        foreach ($interest as $interestId) {
            $this->interests[$interestId] = true;
        }
        return $this;
    }

    /**
     * Sets the group interest to false to show member not interested
     * @param string|array $interest
     * @return \FwsMailchimp\Entities\Members
     */
    public function unsetInterested($interest): Members
    {
        if (is_string($interest) === true) {
            $interest = [$interest];
        }
        
        if (is_array($interest) === false) {
            return $this;
        }
        
        foreach ($interest as $interestId) {
            $this->interests[$interestId] = false;
        }
        return $this;
    }

    /**
     *
     * @param string $ipSignup
     * @return \FwsMailchimp\Entities\Members
     */
    public function setIpSignup(string $ipSignup): Members
    {
        $this->ipSignup = $ipSignup;
        return $this;
    }

    /**
     * 
     * @param DateTime $timestampSignup
     * @return Members
     * @throws IncorrectTypeException
     */
    public function setTimestampSignup($timestampSignup): Members
    {
        if ($timestampSignup instanceof DateTime) {
            $this->timestampSignup = $timestampSignup;
            return $this;
        }

        if (is_string($timestampSignup)) {
            $this->timestampSignup = new DateTime($timestampSignup);
            return $this;
        }

        throw new IncorrectTypeException(sprintf(
                                'Invalid timestamp, expected string or DateTime object, recieved %s',
                                is_object($timestampSignup) ? get_class($timestampSignup) : gettype($timestampSignup)));
    }

    /**
     *
     * @param string $ipOpt
     * @return \FwsMailchimp\Entities\Members
     */
    public function setIpOpt(string $ipOpt): Members
    {
        $this->ipOpt = $ipOpt;
        return $this;
    }

    /**
     * 
     * @param DateTime $timestampOpt
     * @return Members
     * @throws IncorrectTypeException
     */
    public function setTimestampOpt($timestampOpt): Members
    {
        if ($timestampOpt instanceof DateTime) {
            $this->timestampOpt = $timestampOpt;
            return $this;
        }
        
        if (is_string($timestampOpt)) {
            $this->timestampOpt = new DateTime($timestampOpt);
            return $this;
        } 
        
        throw new IncorrectTypeException(sprintf(
                                    'Invalid timestamp, expected string or DateTime object, recieved %s',
                                    is_object($timestampOpt) ? get_class($timestampOpt) : gettype($timestampOpt)));
    }

    /**
     *
     * @param LanguageEntity $language
     * @return \FwsMailchimp\Entities\Members
     */
    public function setLanguage(LanguageEntity $language): Members
    {
        $this->language = $language;
        return $this;
    }

    /**
     *
     * @param boolean $vip
     * @return \FwsMailchimp\Entities\Members
     */
    public function setVip(bool $vip): Members
    {
        $this->vip = $vip;
        return $this;
    }

    /**
     *
     * @param LocationEntity $location
     * @return \FwsMailchimp\Entities\Members
     */
    public function setLocation(LocationEntity $location): Members
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Convert Members object to array for use in mailchimp API
     * @return array
     */
    public function toArray(): array
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
            $array['timestamp_opt'] = $this->timestampSignup;
        }
        return $array;
    }

    /**
     * Format date from \DateTime object
     * @param DateTime $date
     * @return string
     */
    private function formatDate(DateTime $date): string
    {
        return $date->format(DATE_ATOM);
    }

}
