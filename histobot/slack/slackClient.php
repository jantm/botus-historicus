<?php

namespace HistoBot\Slack;

use HistoBot;
use HistoBot\Core as Core;

class SlackClient
{
    const VERSION_NUMBER = 'v0';
    const SLACK_SIGNATURE_HEADER = 'HTTP_X_SLACK_SIGNATURE';
    const SLACK_REQUEST_TIMESTAMP_HEADER = 'HTTP_X_SLACK_REQUEST_TIMESTAMP';

    /**
     * @var object Language module
     */
    protected $i18n;

    /**
     * @var string Slack slash command - used to determine the response length
     */
    protected $command = '';

    /**
     * @var string Slack slash command message text
     */
    protected $text = '';

    /**
     * @var boolean Error flag
     */
    protected $isError = false;

    /**
     * @var string Error type - used as index for i18n error messages
     */
    protected $errorType;


    public function __construct()
    {
        $this->i18n = Core\I18n::getInstance();
        $this->requestValid = true;

        if (isset($_POST)) {
            $this->command = $_POST['command'];
            $this->text    = $_POST['text'];
        }

        $this->slackSignature = $_SERVER[self::SLACK_SIGNATURE_HEADER];
        $this->slackRequestTimestamp = $_SERVER[self::SLACK_REQUEST_TIMESTAMP_HEADER];
        $this->localSignature = $this->getLocalSignature();
    }

    /**
     * Determine if the request is valid:
     * - check the slack signature validity
     * - check the basic message validity
     */
    public function validateRequest()
    {
        if (!\Config::LOCAL_TESTING && !$this->isSignatureValid()) {
            $this->isError = true;
            $this->errorType = \Config::ERROR_INVALID_SIGNATURE;
        }

        if (!$this->isTextValid()) {
            $this->isError = true;
            $this->errorType = \Config::ERROR_NO_TEXT;
        }

        if ($this->error) {
            Core\Response::sendResponse($this->i18n->getErrorResponse($this->errorType), false);
        }
    }

    /**
     * @return boolean
     */
    public function isRequestValid()
    {
        return !$this->isError;
    }

    /**
     * @return string
     */
    public function getSlackCommand()
    {
        return $this->command;
    }

    /**
     * Prepare and return the local signature (to be compared with the one provided by Slack).
     *
     * @return string
     */
    protected function getLocalSignature()
    {
        $signatureContents = [self::VERSION_NUMBER, $this->slackRequestTimestamp, http_build_query($_POST)];
        $signatureBasestring = implode(':', $signatureContents);
        $hash = hash_hmac('sha256', $signatureBasestring, \Config::SIGNING_SECRET);

        return self::VERSION_NUMBER . '=' . $hash;
    }

    /**
     * Compare signatures to determine whether the request was valid.
     *
     * @return boolean
     */
    protected function isSignatureValid()
    {
        return $this->localSignature === $this->slackSignature;
    }

    /**
     * @return boolean
     */
    protected function isTextValid()
    {
        return !empty($this->text);
    }
}
