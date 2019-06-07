<?php

namespace HistoBot\Wiki;

class WikiParser
{
    /**
     * @var string Content from Wikipedia that is to be parsed
     */
    protected $content;

    /**
     * @var string Parsed and formatted content
     */
    protected $formattedContent;

    /**
     * @var array Legitimate header line beginnings
     */
    protected $legitHeaderLineStart = [
        '== ',
    ];

    /**
     * @var array Legitimate line beginnings
     */
    protected $legitRegularLineStart = [
        '* [[',
        '** [',
        '* \'\'',
    ];


    public function __construct($content = null)
    {
        if ($content === null) {
            return;
        }

        $this->setContent($content);
        $this->formatContent();
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getFormattedContent()
    {
        if (!isset($this->formattedContent)) {
            $this->formatContent();
        }

        return $this->formattedContent;
    }

    /**
     * Determines if a line is a header.
     *
     * @param string $line
     * @return boolean
     */
    protected function isLegitHeader($line)
    {
        $head = substr($line, 0, 3);

        return in_array($head, $this->legitHeaderLineStart);
    }

    /**
     * @param string $line
     * @return boolean
     */
    protected function isLegitLine($line)
    {
        $head = substr($line, 0, 4);
        $tail = substr($line, -1);

        if (in_array($head, $this->legitRegularLineStart) &&
            $tail != ':' &&
            strlen($this->formatLine($line)) > 16) {
            return true;
        }

        return false;
    }

    /**
     * Format a header line - removes the wiki-notation header signs
     *
     * @param string $line Header line
     * @return string Formatted header line
     */
    protected function formatHeader($line)
    {
        // Header line format:
        // == Header text ==
        return trim($line, '= ');
    }

    /**
     * Get positions of start and end delimiters in a string.
     * If a $break value occurs between the $start and $end, the search is stopped
     * and performed recursively on substrings that were not searched yet.
     *
     * @param string $string Given string (haystack)
     * @param string $start
     * @param string $end
     * @param string $break String that cannot occur between $start and $end
     * @param integer $offset Offset for the haystack
     * @return array|boolean Array od two position values or false, if nothing was found
     */
    protected function getStartEndPositions($string, $start, $end, $break = null, $offset = 0)
    {
        $startPos = strpos($string, $start, $offset);
        $endPos = strpos($string, $end);

        if ($startPos === false || $endPos === false || $startPos > $endPos) {
            return false;
        }

        $breakPos = $break ? strpos($string, $break, $startPos) : false;
        $breakPos = $breakPos === false ? 0 : $breakPos;
        $breakConflict = $startPos < $breakPos && $breakPos < $endPos;

        if ($startPos > $endPos || $breakConflict) {
            $nextOffset = $breakPos ? $breakPos : $endPos;
            return $this->getStartEndPositions($string, $start, $end, $break, $nextOffset);
        }

        return [
            'start' => $startPos,
            'end' => $endPos,
        ];
    }

    /**
     * Find two substrings ($start and $end) in a given string, make sure that
     * they make a pair of such strings that are closest to each other, and remove
     * the content between them (along with the $end substring).
     *
     * @param string $string The given string (haystack)
     * @param string $start
     * @param string $end
     * @return string Formatted string
     */
    protected function deleteAllBetween($string, $start, $end, $break = null)
    {
        $delimiters = $this->getStartEndPositions($string, $start, $end, $break);
        $firstPart = substr($string, 0, $delimiters['start']);
        $endLen = strlen($end);
        $secondPart = substr($string, $delimiters['end'] + $endLen);

        if ($delimiters === false) {
            return $string;
        }

        $result = $firstPart . $secondPart;

        // Repeat this recursively to delete all occurences
        return $this->deleteAllBetween($result, $start, $end, $break);
    }

    /**
     * Format a given line (converts wikipedia notation for bot use).
     *
     * @param string $line
     * @return string Formatted line
     */
    protected function formatLine($line)
    {
        // Assumed line beginnings:
        //  * [[
        //  ** [[
        //  * ''
        //  ** ''
        //
        // Wikipedia notation:
        //  - Link format: [[link|text]] or [[text]]
        //  - Bold format: '''bold text'''
        //  - Italic format: ''Italic text''
        //
        // Unfortunately Slack attachments do not support formatting,
        // so we're forced to remove all of it.

        $line = trim($line, '* ');
        $chars = [
            'link'   => ['[', ']'],
            'italic' => ['\'\''],
            'bold'   => ['\'\'\''],
        ];
        // remove comments:
        $line = $this->deleteAllBetween($line, '<!--', '-->');
        // remove links in link tags, leave only text:
        $line = $this->deleteAllBetween($line, '[[', '|', ']]');
        // remove links:
        $line = str_replace($chars['link'], '', $line);
        // convert bold:
        $line = str_replace($chars['bold'], '', $line);
        // convert italics:
        $line = str_replace($chars['italic'], '', $line);

        return $line;
    }

    /**
     * Split content to separate lines.
     *
     * @param string $content
     * @return array Content lines
     */
    protected function splitContentToLines($content)
    {
        return explode("\n", $content);
    }

    /**
     * Perform content formatting.
     */
    protected function formatContent()
    {
        $contentLines = $this->splitContentToLines($this->content);

        foreach ($contentLines as $row => $data) {
            if ($this->isLegitHeader($data)) {
                $currentHeader = $this->formatHeader($data);
                $this->formattedContent[$currentHeader] = [];
            }

            if ($this->isLegitLine($data)) {
                $this->formattedContent[$currentHeader][] = $this->formatLine($data);
            }
        }
    }
}
