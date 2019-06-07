<?php

namespace HistoBot;

require_once __DIR__ . '/histobot/config.php';

use HistoBot\Core as Core;
use HistoBot\Wiki as Wiki;
use HistoBot\Slack as Slack;

\Config::setEnvironment();

// Validate request and message:
$slack = new Slack\SlackClient();
$slack->validateRequest();

$textParser = new Wiki\TextParser();
if (!$slack->isRequestValid() ||
    !$textParser->isMessageValid()) {
    return false;
}

// Init message creation:
$year = $textParser->getYear();
$messageConstructor = new Slack\MessageConstructor($year, $slack->getSlackCommand());

// Check if there's a predefined answer for the given date or
// should the relevant data be requested from Wikipedia.
// Then, prepare the answer contents:
if ($messageConstructor->isPredefined($year)) {
    $formattedContent = $messageConstructor->getContent($year);
} else {
    $wikonnekt = new Wiki\Wikonnekt($year);
    $content = $wikonnekt->getContentFromWiki();

    if (!$content) {
        return false;
    }

    $wikiParser = new Wiki\WikiParser($content);
    $formattedContent = $wikiParser->getFormattedContent();
}

// Construct and send the response:
$messageConstructor->setContent($formattedContent);
$response = $messageConstructor->getResponse();
Core\Response::sendResponse($response);

exit();
