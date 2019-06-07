<?php

namespace HistoBot\Wiki;

use HistoBot;
use HistoBot\Core as Core;
use HistoBot\Slack as Slack;

class TextParser
{
    const GITHUB_LINK = 'github.com';

    /**
     * @var string Slack slash command text
     */
    protected $msgText;

    /**
     * @var array Whitelist of GitHub repositories
     */
    protected $githubRepoWhitelist;

    /**
     * @var string GitHub organization url
     */
    protected $githubUrl;

    /**
     * @var string Extracted repository name
     */
    protected $repoName;

    /**
     * @var string Extracted year
     */
    protected $year;

    /**
     * @var integer Number of PR links in the message
     */
    protected $linkCount;

    /**
     * @var object Language module
     */
    protected $i18n;

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
        $this->msgText = !empty($_POST['text']) ? strtolower($_POST['text']) : null;
        $this->githubUrl = self::GITHUB_LINK . '/' . \Config::GITHUB_ORGANIZATION;
        $this->githubRepoWhitelist = \Config::getGithubRepos();
        $this->linkCount = $this->getValidLinkInMessageCount();

        if ($this->linkCount > 0) {
            $this->extractRepoAndYear();
        }

        $this->validateMessage();
    }

    /**
     * Determine if the message format is valid:
     * - contains only one PR link
     * - the repo name is whitelisted
     * - the year number is valid
     *
     * Trigger a response if the message is not valid.
     */
    protected function validateMessage()
    {
        if ($this->linkCount < 1) {
            $this->setError(\Config::ERROR_NO_REPO);
        } elseif ($this->linkCount > 1) {
            $this->setError(\Config::ERROR_TOO_MANY_LINKS);
        } elseif (!$this->isRepoValid()) {
            $this->setError(\Config::ERROR_BAD_REPO);
        } elseif ($this->getYear() === null) {
            $this->setError(\Config::ERROR_NO_YEAR);
        }

        if ($this->isError) {
            Core\Response::sendResponse($this->i18n->getErrorMessage($this->errorType), false);
        }
    }

    /**
     * @return boolean
     */
    public function isMessageValid()
    {
        return !$this->isError;
    }

    /**
     * Get the year number.
     *
     * @return mixed The year number or null, if the year is not valid
     */
    public function getYear()
    {
        if ($this->isYearValid()) {
            return $this->year;
        }

        return null;
    }

    /**
     * Determine if the repository name is whitelisted.
     *
     * @return boolean
     */
    protected function isRepoValid()
    {
        return in_array($this->repoName, $this->githubRepoWhitelist);
    }

    /**
     * Get the number of PR links for a given repo in the message.
     *
     * @param string $repo Repository name
     * @return integer
     */
    protected function getRepoLinkCount($repo)
    {
        $link = $this->githubUrl . '/' . $repo;

        return substr_count($this->msgText, $link);
    }

    /**
     * Get the total number of valid PR links in the message.
     *
     * @return integer
     */
    protected function getValidLinkInMessageCount()
    {
        $reposCount = array_map([$this, 'getRepoLinkCount'], $this->githubRepoWhitelist);

        return array_sum($reposCount);
    }

    /**
     * Parse the message text in order to extract the repo name and year number.
     */
    protected function extractRepoAndYear()
    {
        $msgHalfs = explode($this->githubUrl, $this->msgText);
        $urlParts = explode('/', $msgHalfs[1]);

        $this->repoName = $urlParts[1];
        $this->year = (int)$urlParts[3];
    }

    /**
     * @return boolean
     */
    protected function isYearValid()
    {
        return ($this->year > 0);
    }

    /**
     * @param $string Error type (use config consts)
     */
    protected function setError($type)
    {
        $this->isError = true;
        $this->errorType = $type;
    }
}
