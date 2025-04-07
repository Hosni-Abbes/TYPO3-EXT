<?php

namespace T3dev\Mask2blocks\Validation;

class ConfigurationValidator
{
    /**
     * @param $element
     * @return bool
     */
    public static function isValidElement($element): bool
    {
        // Implement validation logic for the element
        // Example: Check if required fields are present
        return isset($element['requiredField']);
    }

    /**
     * @param array $jsonConfigArray
     * 
     * @return bool
     */
    public static function isValidJsonConfigStructure(array $jsonConfigArray): bool
    {
        if (!array_key_exists('tables', $jsonConfigArray) || empty($jsonConfigArray['tables'])) return false;
        
        $tables = $jsonConfigArray['tables'];
        if (!array_key_exists('tt_content', $tables) || empty($tables['tt_content'])) return false;

        $ttcontentConfig = $tables['tt_content'];
        if (!array_key_exists('elements', $ttcontentConfig)
            || !array_key_exists('sql', $ttcontentConfig)
            || !array_key_exists('tca', $ttcontentConfig)
           ) return false;

        return true;
    }
}
