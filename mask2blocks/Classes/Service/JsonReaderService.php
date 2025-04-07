<?php
declare(strict_types=1);

namespace T3dev\Mask2blocks\Service;

use T3dev\Mask2blocks\Exception\InvalidPathException;
use T3dev\Mask2blocks\Utility\SettingsUtility;
use T3dev\Mask2blocks\Utility\FileUtility;
use TYPO3\CMS\Core\Exception;


/**
 * Class JsonReaderService
 *
 * @package T3dev\Mask2blocks\Service
 */
class JsonReaderService
{
    protected array $extensionSettings;

    public function __construct(
        protected SettingsUtility $settingsUtility
    ) {
        $this->extensionSettings = $settingsUtility->getExtBackendSettings();
    }
    /**
     * @return array|string
     * 
     * @throws InvalidPathException
     * @throws Exception
     */
    public function loadMaskConfiguration(): array|string
    {
        try {
            // Get the maskJsonPath from backend's settings module > extension configuration
            $maskJsonPath = FileUtility::validateFilePath($this->extensionSettings['maskJsonPath']);

            // Check if the json file is exist in directory
            if (!file_exists($maskJsonPath)) {
                throw new InvalidPathException(
                    'Mask configuration file not found in this path : ' . $this->extensionSettings['maskJsonPath'],
                    1609402021
                );
            }
            
            // Reurn a decoded mask.json file
            return json_decode(file_get_contents($maskJsonPath), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function extractMaskFieldTitle(): string
    {
        return 'FieldTitle';
    }
}