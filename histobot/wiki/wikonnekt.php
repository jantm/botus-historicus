<?php

namespace HistoBot\Wiki;

use HistoBot\Core as Core;

class Wikonnekt
{
    /**
     * @var string Timezone name; used to compare a given date with the current one
     */
    const TIMEZONE = 'Europe/London';

    /**
     * @var integer ID (in the wiki content structure) of a page that doesn't exist
     */
    const NONEXISTENT_PAGE_ID = -1;

    /**
     * @var string curl response header
     */
    const RESPONSE_HEADER = 'Content-Type: application/json';

    // Curl request params
    const CURL_HTTP_HEADER = 'Accept: application/json';
    const CURL_SSL_VERIFY_PEER = false;
    const CURL_RETURN_TRANSFER = true;

    /**
     * @var object Language module
     */
    protected $i18n;

    /**
     * @var string Wikiepdia API URL. We'll be getting data from there
     */
    protected $endpoint;

    /**
     * @var array Params required for constructing the wiki endpoint link
     */
    protected $endpointParts = [
        'lang' => '',
        'year' => '',
    ];


    public function __construct($year)
    {
        $this->i18n = Core\I18n::getInstance();
        $this->endpointParts['lang'] = $this->i18n->getLang();
        $this->endpointParts['year'] = $year;
        $this->setEndpoint();
    }

    /**
     * Retrieve the content from Wikipedia and extract it from its data structures.
     *
     * @return string Content from Wikipedia.
     */
    public function getContentFromWiki()
    {
        // extracting the actual content from this crazy wiki structures
        $json = json_decode($this->getDataFromWiki());
        $pageArray = get_object_vars($json->query->pages);
        $pageId = array_keys($pageArray)[0];

        if ($pageId == self::NONEXISTENT_PAGE_ID) {
            date_default_timezone_set(self::TIMEZONE);

            if ($this->endpointParts['year'] > date('Y')) {
                Core\Response::sendResponse($this->i18n->getErrorMessage(\Config::ERROR_NO_FUTURE), false);
            } else {
                Core\Response::sendResponse($this->i18n->getErrorMessage(\Config::ERROR_NO_PAGE), false);
            }

            return null;
        }

        $content = get_object_vars($pageArray[$pageId]->revisions[0])['*'];
        return $content;
    }

    /**
     * Set the Wikipedia page address.
     */
    protected function setEndpoint()
    {
        $this->endpoint = LinkConstructor::getLink('endpoint', $this->endpointParts);
    }

    /**
     * Connect to Wikipedia and retrieve page data via API using
     * the $this->endpoint address.
     *
     * @return string
     */
    protected function getDataFromWiki()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [self::CURL_HTTP_HEADER]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, self::CURL_SSL_VERIFY_PEER);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, self::CURL_RETURN_TRANSFER);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);

        $result = curl_exec($ch);

        curl_close($ch);

        header(self::RESPONSE_HEADER);

        return $result;
    }
}
