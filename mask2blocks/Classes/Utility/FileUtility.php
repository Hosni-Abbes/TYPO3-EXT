<?php
namespace T3dev\Mask2blocks\Utility;

use T3dev\Mask2blocks\Exception\InvalidPathException;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Class FileUtility
 *
 * @package T3dev\Mask2blocks\Utility
 */
class FileUtility 
{
    /**
     * @param string $filePathConfig
     * @return string
     * 
     * @throws InvalidPathException
     */
    public static function validateFilePath(string $filePathConfig): string
    {
        $filePath = self::getFilePath($filePathConfig);
        if ($filePath === '' && isset($filePathConfig) && $filePathConfig !== '') {
            throw new InvalidPathException('Invalid file path. The value "' . $filePathConfig . '" was given.', 1639220370);
        }

        return $filePath;
    }

    /**
     * @param string $filePathConfig
     * 
     * @return string
     */
    private static function getFilePath(string $filePathConfig): string
    {
        if (($filePathConfig ?? '') === '') {
            return '';
        }

        return GeneralUtility::getFileAbsFileName($filePathConfig);
    }
}