<?php

namespace FwsMailchimp\Entities;

/**
 * Languages Entity
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Languages implements EntityInterface
{

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var array
     */
    private $validLanguageCodes = array(
        'en' => 'English',
        'ar' => 'Arabic',
        'af' => 'Afrikaans',
        'be' => 'Belarusian',
        'bg' => 'Bulgarian',
        'ca' => 'Catalan',
        'zh' => 'Chinese',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'nl' => 'Dutch',
        'et' => 'Estonian',
        'fa' => 'Farsi',
        'fi' => 'Finnish',
        'fr' => 'French (France)',
        'fr_CA' => 'French (Canada)',
        'de' => 'German',
        'el' => 'Greek',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'hu' => 'Hungarian',#
        'is' => 'Icelandic',
        'id' => 'Indonesian',
        'ga' => 'Irish',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'km' => 'Khmer',
        'ko' => 'Korean',
        'lv' => 'Latvian',
        'lt' => 'Lithuanian',
        'mt' => 'Maltese',
        'ms' => 'Malay',
        'mk' => 'Macedonian',
        'no' => 'Norwegian',
        'pl' => 'Polish',
        'pt' => 'Portuguese (Brazil)',
        'pt_PT' => 'Portuguese (Portugal)',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sr' => 'Serbian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'es' => 'Spanish (Mexico)',
        'es_ES' => 'Spanish (Spain)',
        'sw' => 'Swahili',
        'sv' => 'Swedish',
        'ta' => 'Tamil',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'vi' => 'Vietnamese',
    );

    /**#
     * Initialize class
     */
    public function __construct()
    {
        $this->id = '';
    }

    /**
     * Get the language code
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the language
     * @return string
     */
    public function getLanguage()
    {
        if (array_key_exists($this->id, $this->validLanguageCodes)) {
            return $this->validLanguageCodes[$this->id];
        }
        return '';
    }

    /**
     * Get all valid language codes
     * @return array
     */
    public function getValidLanguageCodes()
    {
        return $this->validLanguageCodes;
    }

    /**
     * Set the language code
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'language' => $this->getLanguage(),
        );
    }

}
