<?php

namespace HistoBot\Core;

class Response
{
    /**
     * Send a response.
     *
     * @param string $response
     * @param boolean $json Send response as JSON
     */
    public static function sendResponse($response, $json = true)
    {
        if ($json) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            echo self::getFormattedTextResponse($response);
        }
    }

    /**
     * Get the formatted response (italic in Slack's notation)
     *
     * @param string $text
     * @return string
     */
    protected static function getFormattedTextResponse($text)
    {
        return '_' . $text . '_';
    }
}
