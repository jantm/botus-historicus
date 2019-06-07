<?php

namespace HistoBot\Slack;

use HistoBot;
use HistoBot\Core as Core;
use HistoBot\Wiki as Wiki;

class MessageConstructor
{
    // Slack attachment colors:
    const COLOR_MESSAGE = '#e4f1fe';
    const COLOR_FOOTER  = '#fff0c0';

    /**
     * @var string Slack slash command reposnse type ('ephemeral'/'in_channel')
     */
    const RESPONSE_TYPE = 'in_channel';

    /**
     * @var object Language module
     */
    protected $i18n;

    /**
     * @var array Content received from Wikipedia
     */
    protected $wikipediaContent;

    /**
     * @var integer Index of the answering person
     */
    protected $answeringPersonId;

    /**
     * @var array Message parts from i18n
     */
    protected $messageParts;

    /**
     * @var array Predefined messages for specific dates
     */
    protected $predefinedDateMessages;

    /**
     * @var boolean Short response version flag
     */
    protected $short = false;

    /**
     * @var array Data needed to construct a link
     */
    protected $linkParts = [
        'lang' => '',
        'year' => '',
    ];

    /**
     * @param integer $year
     * @param string|null $command Slack command
     */
    public function __construct($year, $command = null)
    {
        $this->i18n = Core\I18n::getInstance();
        $this->messageParts = $this->i18n->getMessageParts();
        $this->linkParts['lang'] = $this->i18n->getLang();
        $this->linkParts['year'] = $year;

        if ($command) {
            $this->short = ($command === \Config::SLACK_COMMAND_SHORT);
        }

        $this->predefinedDateMessages = $this->i18n->getPredefinedDateMessages();

        $this->drawPersonId();
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->wikipediaContent = $content;
    }

    /**
     * Get the response structure.
     *
     * @return array
     */
    public function getResponse()
    {
        return [
            'response_type' => self::RESPONSE_TYPE,
            'text'          => $this->getHeader(),
            'attachments'   => $this->getAttachments(),
        ];
    }

    /**
     * Determines if a given year has a predefined message.
     *
     * @param integer $year
     * @return boolean
     */
    public function isPredefined($year)
    {
        return in_array($year, array_keys($this->predefinedDateMessages));
    }

    /**
     * Adjusts the data format for the Message Construstor class.
     */
    public function getContent($year)
    {
        $key = array_keys($this->predefinedDateMessages[$year])[0];

        return [
            $key => [
                $this->predefinedDateMessages[$year][$key],
            ],
        ];
    }

    /**
     * Get the response header.
     *
     * @return string
     */
    protected function getHeader()
    {
        $who     = $this->getPersonName();
        $asked   = $this->drawArrayElement($this->messageParts['asked']);
        $answers = $this->drawArrayElement($this->messageParts['answers']);

        return '*_' . $who .', ' . $asked . ' ' . $this->linkParts['year'] . ', ' . $answers . ':_*';
    }

    /**
     * Select a random answering person ID.
     */
    protected function drawPersonId()
    {
        $this->answeringPersonId = array_rand($this->messageParts['who']);
    }

    /**
     * Get the answering person name.
     *
     * @return string
     */
    protected function getPersonName()
    {
        return $this->messageParts['who'][$this->answeringPersonId]['name'];
    }

    /**
     * Get the answering person avatar url.
     *
     * @return string
     */
    protected function getPersonAvatarUrl()
    {
        return \Config::BOT_DIR_URL . '/img/' . $this->messageParts['who'][$this->answeringPersonId]['img'];
    }

    /**
     * Get a randomly selected array element.
     *
     * @param array $array
     * @return array Random array element
     */
    protected function drawArrayElement($array)
    {
        return $array[array_rand($array)];
    }

    /**
     * Get the footer attachment.
     *
     * @return array
     */
    protected function getFooterAttachment()
    {
        $link = Wiki\LinkConstructor::getLink('wikiLink', $this->linkParts);
        $text = $this->drawArrayElement($this->messageParts['attachmentFooter']['text']) . ' ' . $link;

        return [
            'color'       => self::COLOR_FOOTER,
            'title'       => $this->messageParts['attachmentFooter']['title'],
            'author_name' => $this->getPersonName(),
            'author_icon' => $this->getPersonAvatarUrl(),
            'text'        => $text,
            'footer'      => \Config::BOT_NAME,
            'footer_icon' => \Config::BOT_DIR_URL . '/img/avatar.jpg',
        ];
    }

    /**
     * Get slack response attachments (containing the actual history facts
     * and and an optional bonus).
     *
     * @return array
     */
    protected function getAttachments()
    {
        $attachments = $this->getRandomFacts();

        foreach ($attachments as $i => $attachment) {
            $attachments[$i]['color'] = self::COLOR_MESSAGE;
        }

        if ($this->short) {
            return [$attachments[0]];
        }

        if (rand(0, 1)) {
            $attachments[] = $this->getFooterAttachment();
        }

        return $attachments;
    }

    /**
     * Get an array of the randomly selected history facts (one fact per section)
     * based on the data received from Wikipedia.
     *
     * @return array
     */
    protected function getRandomFacts()
    {
        $facts = [];

        foreach ($this->wikipediaContent as $header => $rows) {
            if (count($rows)) {
                $facts[] = [
                    'title' => $header . ':',
                    'text'  => $this->drawArrayElement($rows),
                ];
            }
        }

        return $facts;
    }
}
