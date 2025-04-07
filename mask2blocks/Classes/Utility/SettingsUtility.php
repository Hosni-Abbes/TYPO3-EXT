<?php
namespace T3dev\Mask2blocks\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class SettingsUtility
 *
 * @package T3dev\Mask2blocks\Utility
 */
class SettingsUtility 
{
    /**
     * @var array<string, string>
     */
    protected array $mask2blocksExtensionConfiguration;

    public function __construct(
        protected ConfigurationManagerInterface $configurationManagerInterface,
        array $mask2blocksExtensionConfiguration
    ) {
        $this->mask2blocksExtensionConfiguration = $mask2blocksExtensionConfiguration;
    }

    /**
     * @return
     */
    public function getExtBackendSettings(): array
    {
        return $this->mask2blocksExtensionConfiguration;
    }

    /**
     * If we need to get settings from tx_mask2blocks typoscript - (can be removed)
     * 
     * @return array|null
     */
    public function getExtTyposcriptSettings(): ?array
    {
        $setup = $this->configurationManagerInterface->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        $result = null;
        if (isset($setup['plugin.']['tx_mask2blocks.']['settings.'])) {
            $result = GeneralUtility::removeDotsFromTS($setup['plugin.']['tx_mask2blocks.']['settings.']);
        }

        return $result;
    }

}