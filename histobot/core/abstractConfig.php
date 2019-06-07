<?php

namespace HistoBot\Core;

abstract class AbstractConfig
{
    // Error types (also used as keys in i18n files):
    const ERROR_INVALID_SIGNATURE = 'invalidSignature';
    const ERROR_BAD_REPO = 'badRepo';
    const ERROR_NO_REPO = 'noRepo';
    const ERROR_NO_YEAR = 'noYear';
    const ERROR_NO_TEXT = 'noText';
    const ERROR_TOO_MANY_LINKS = 'tooManyLinks';
    const ERROR_NO_PAGE = 'noPage';
    const ERROR_NO_FUTURE = 'noFuture';

    /**
     * Get the GitHub repos provided in the config.
     *
     * @return array
     */
    public static function getGithubRepos()
    {
        return static::$githubRepos;
    }

    /**
     * Set the fake POST data for local testing purposes.
     * (See the LOCAL_TESTING option in the config)
     */
    public static function setLocalPostData()
    {
        $_POST['text'] = static::LOCAL_POST_TEXT;
        $_POST['command'] = static::LOCAL_POST_COMMAND;
    }

    /**
     * Determine whether the bot is actually live or in test mode.
     */
    public static function setEnvironment()
    {
        if (static::LOCAL_TESTING) {
            self::setLocalPostData();
        } else {
            error_reporting(0);
        }
    }
}
