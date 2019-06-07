<?php

namespace HistoBot\Wiki;

class LinkConstructor
{
    /**
     * Link schemes; data will be inserted in [[placeholder]]
     */
    protected static $linkSchemes = [
        'endpoint' => 'https://[[lang]].wikipedia.org/w/api.php?action=query&titles=[[year]]&format=json&prop=revisions&rvprop=content',
        'wikiLink' => 'https://[[lang]].wikipedia.org/wiki/[[year]]',
    ];


    /**
     * Create a placeholder pattern.
     *
     * @param string $string
     * @return string Generated placeholder regex
     */
    protected function createPlaceholderPattern($string)
    {
        return '/\[\[' . $string . '\]\]/';
    }

    /**
     * Get patterns for link parts.
     *
     * @param array $linkParts
     * @return array Generated patterns
     */
    protected function getPatterns($linkParts)
    {
        return array_map('self::createPlaceholderPattern', array_keys($linkParts));
    }

    /**
     * Get a link with placeholders filled with given data.
     *
     * @param string $linkScheme Link scheme key
     * @param array $linkParts Link parts (placeholder data)
     * @return string
     */
    public static function getLink($linkScheme, $linkParts)
    {
        $patterns = self::getPatterns($linkParts);
        $replacements = array_values($linkParts);

        return preg_replace($patterns, $replacements, self::$linkSchemes[$linkScheme]);
    }
}
