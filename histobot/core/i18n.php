<?php

namespace HistoBot\Core;

class I18n
{
    /**
     * @var string Language code delimiter for the lang code format (e.g. 'en-US')
     */
    const LANG_DELIMITER = '-';

    /**
     * @var string A tag used for injecting the bot name into error messages
     * (a rather temporary solution)
     */
    const BOT_TAG = '[[BOT_NAME]]';

    /**
     * @var string Language code, two lowercase letters
     */
    protected $langCode;

    /**
     * @var array Message parts from a i18n file
     */
    protected $messageParts;

    /**
     * @var array Error messages from a i18n file
     */
    protected $errorMessages;

    /**
     * @var array Predefined messages for specific dates from a i18n file
     */
    protected $predefinedDateMessages;

    /**
     * @var object An instance of this class
     */
    protected static $instance = null;


    private function __construct() {}
    private function __clone() {}

    /**
     * Get the instance of this class (create one, if it doesn't exist yet).
     *
     * @return object
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::createInstance();
        }

        return self::$instance;
    }

    /**
     * Create and initializes an instance of this class.
     */
    public static function createInstance()
    {
        self::$instance = new self;
        self::$instance->setLanguage();
        self::$instance->loadI18n();
    }

    /**
     * Get the error message.
     *
     * @param string $key i18n message index key
     * @return string
     */
    public function getErrorMessage($key)
    {
        return str_replace(self::BOT_TAG, \Config::BOT_NAME, $this->errorMessages[$key]);
    }

    /**
     * Get the message parts.
     *
     * @return array
     */
    public function getMessageParts()
    {
        return $this->messageParts;
    }

    /**
     * Get the predefined date messages.
     *
     * @return array
     */
    public function getPredefinedDateMessages()
    {
        return $this->predefinedDateMessages;
    }

    /**
     * Get the language code.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->langCode;
    }

    /**
     * Set the language code based on the language version provided in the config.
     */
    protected function setLanguage()
    {
        $langCode = explode(self::LANG_DELIMITER, \Config::LANG)[0];
        $this->langCode = strtolower($langCode);
    }

    /**
     * Load a proper i18n file for future use.
     */
    protected function loadI18n()
    {
        require_once __DIR__ . '/../i18n/' . \Config::LANG . '.php';

        $this->messageParts = isset($messageParts) ? $messageParts : null;
        $this->errorMessages = isset($errorMessages) ? $errorMessages : null;
        $this->predefinedDateMessages = isset($predefinedDateMessages) ? $predefinedDateMessages : [];
    }
}
