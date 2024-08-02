<?php

namespace FwsMailchimp\Entities;

use DateTimeInterface;
use Exception;
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

    private string $id = '';

    private string $emailAddress = '';

    private array $mergeFields = [];

    private string $uniqueEmailId = '';

    private string $emailType = '';

    private string $status = '';

    private ArrayCollection $interestCategories;

    /**
     *
     * @var bool[]
     */
    private array $interests = [];


    private MemberStatsEntity $stats;

    private string $ipSignup = '';

    private string|DateTimeInterface $timestampSignup = '';

    private string $ipOpt = '';

    private string|DateTimeInterface $timestampOpt = '';

    private int|null $memberRating = null;

    private DateTimeInterface|string $lastChanged = '';

    private LanguageEntity $language;

    private bool $vip = false;

    private string $emailClient = '';

    private LocationEntity $location;

    private string $listId = '';

    /**
     *
     * @var string[]
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
     * @var string[]
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
     * Get members valid subscribe status
     * @return string[]
     */
    public function getValidSubscribeStates(): array
    {
        return $this->validSubscribeStatus;
    }

    /**
     * Get members valid email types
     * @return string[]
     */
    public function getValidEmailTypes(): array
    {
        return $this->validEmailTypes;
    }

    /**
     * Get members email address
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * Set the members email address
     * @param string $emailAddress
     * @return Members
     */
    public function setEmailAddress(string $emailAddress): Members
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * Get the members merge fields data
     * @return array<string, string>
     */
    public function getMergeFields(): array
    {
        return $this->mergeFields;
    }

    /**
     * Set the members merge fields data
     * @param array<string, string> $mergeFields
     * @return Members
     */
    public function setMergeFields(array $mergeFields = []): Members
    {
        $this->mergeFields = $mergeFields;
        return $this;
    }

    /**
     * Get the unique email id
     * @return string
     */
    public function getUniqueEmailId(): string
    {
        return $this->uniqueEmailId;
    }

    /**
     * Get the email type
     * @return string
     */
    public function getEmailType(): string
    {
        return $this->emailType;
    }

    /**
     * Set the email type
     * @param string $emailType
     * @return Members
     * @throws IncorrectTypeException
     */
    public function setEmailType(string $emailType): Members
    {
        if (in_array($emailType, $this->validEmailTypes)) {
            $this->emailType = $emailType;
            return $this;
        }
        throw new IncorrectTypeException(sprintf('Invalid email type, expected %s', implode(', ', $this->validEmailTypes)));
    }

    /**
     * Get the members status
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the members status
     * @param string $status
     * @return Members
     * @throws IncorrectStatusException
     */
    public function setStatus(string $status): Members
    {
        if (in_array($status, $this->validSubscribeStatus)) {
            $this->status = $status;
            return $this;
        } else {
            throw new IncorrectStatusException(sprintf('Invalid status, expected %s', implode(', ', $this->validSubscribeStatus)));
        }
    }

    /**
     * Get members interest categories
     * @return ArrayCollection
     */
    public function getInterestCategories(): ArrayCollection
    {
        return $this->interestCategories;
    }

    /**
     * Get members interests
     * @return bool[]
     */
    public function getInterests(): array
    {
        return $this->interests;
    }

    /**
     * Get the members stats entity
     * @return MemberStatsEntity
     */
    public function getStats(): MemberStatsEntity
    {
        return $this->stats;
    }

    /**
     * Get members signup IP address
     * @return string
     */
    public function getIpSignup(): string
    {
        return $this->ipSignup;
    }

    /**
     * Set members signup IP address
     * @param string $ipSignup
     * @return Members
     */
    public function setIpSignup(string $ipSignup): Members
    {
        $this->ipSignup = $ipSignup;
        return $this;
    }

    /**
     * Get members signup date time
     * @return DateTimeInterface|string
     */
    public function getTimestampSignup(): DateTimeInterface|string
    {
        return $this->timestampSignup;
    }

    /**
     * Get members signup date time
     * @param DateTimeInterface|string $timestampSignup
     * @return Members
     * @throws IncorrectTypeException
     * @throws Exception
     */
    public function setTimestampSignup(DateTimeInterface|string $timestampSignup): Members
    {
        if ($timestampSignup instanceof DateTimeInterface) {
            $this->timestampSignup = $timestampSignup;
            return $this;
        }

        $this->timestampSignup = new DateTime($timestampSignup);
        return $this;
    }

    /**
     * Get members opt-in IP address
     * @return string
     */
    public function getIpOpt(): string
    {
        return $this->ipOpt;
    }

    /**
     * Set members opt-in IP address
     * @param string $ipOpt
     * @return Members
     */
    public function setIpOpt(string $ipOpt): Members
    {
        $this->ipOpt = $ipOpt;
        return $this;
    }

    /**
     *
     * @return DateTimeInterface|string
     */
    public function getTimestampOpt(): DateTimeInterface|string
    {
        return $this->timestampOpt;
    }

    /**
     * Get members opt-in date time
     * @param DateTimeInterface|string $timestampOpt
     * @return Members
     * @throws IncorrectTypeException
     * @throws Exception
     */
    public function setTimestampOpt(DateTimeInterface|string $timestampOpt): Members
    {
        if ($timestampOpt instanceof DateTimeInterface) {
            $this->timestampOpt = $timestampOpt;
            return $this;
        }

        $this->timestampOpt = new DateTime($timestampOpt);

        return $this;
    }

    /**
     * Get member rating
     * @return integer
     */
    public function getMemberRating(): int
    {
        return (int)$this->memberRating;
    }

    /**
     * Get member record last changed date time
     * @return DateTimeInterface|string
     */
    public function getLastChanged(): DateTimeInterface|string
    {
        return $this->lastChanged;
    }

    /**
     * Get members language entity
     * @return LanguageEntity
     */
    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }

    /**
     * Set members language entity
     * @param LanguageEntity $language
     * @return Members
     */
    public function setLanguage(LanguageEntity $language): Members
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Is VIP member
     * @return boolean
     */
    public function isVip(): bool
    {
        return $this->vip;
    }

    /**
     * Set VIP member
     * @param boolean $vip
     * @return Members
     */
    public function setVip(bool $vip): Members
    {
        $this->vip = $vip;
        return $this;
    }

    /**
     * Get email client
     * @return string
     */
    public function getEmailClient(): string
    {
        return $this->emailClient;
    }

    /**
     * Get members location entity
     * @return LocationEntity
     */
    public function getLocation(): LocationEntity
    {
        return $this->location;
    }

    /**
     * Set members location entity
     * @param LocationEntity $location
     * @return Members
     */
    public function setLocation(LocationEntity $location): Members
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get list id
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * Sets the group interest to true to show member interested
     * @param string|string[] $interest
     * @return Members
     */
    public function setInterested(array|string $interest): Members
    {
        if (is_string($interest)) {
            $interest = [$interest];
        }

        foreach ($interest as $interestId) {
            $this->interests[$interestId] = true;
        }
        return $this;
    }

    /**
     * Sets the group interest to false in order to show member not interested
     * @param string|string[] $interest
     * @return Members
     */
    public function unsetInterested(array|string $interest): Members
    {
        if (is_string($interest)) {
            $interest = [$interest];
        }

        foreach ($interest as $interestId) {
            $this->interests[$interestId] = false;
        }
        return $this;
    }

    /**
     *  Used for Mailchimp API
     * @return string[]
     */
    public function toArray(): array
    {
        $array = [
            'email_address' => $this->emailAddress,
            'email_type' => $this->emailType,
            'status' => $this->status,
            'merge_fields' => $this->mergeFields,
            'interests' => $this->interests,
            'language' => $this->language->getId(),
            'vip' => $this->vip,
            'location' => $this->location->toArray(),
            'ip_signup' => $this->ipSignup,
            'ip_opt' => $this->ipOpt
        ];

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
     *
     * @return string|null
     */
    public function getId(): string|null
    {
        return $this->id;
    }

    /**
     * Format date from DateTime object
     * @param DateTimeInterface $date
     * @return string
     */
    private function formatDate(DateTimeInterface $date): string
    {
        return $date->format(DATE_ATOM);
    }

}
