<?php
declare(strict_types=1);

namespace T3dev\Mask2blocks\Service;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class ConfigurationService
 *
 * @package T3dev\Mask2blocks\Service
 */
class ConfigurationService
{
    private const TX_EXTENSION_PREFIX = 'tx_';
    protected string $extensionName;

    /**
     * ConfigurationService constructor.
     *
     * @param ConfigurationManager $configurationManager
     * @param ExtensionConfiguration $extensionConfiguration
     */
    public function __construct(
        protected ConfigurationManager $configurationManager,
        protected ExtensionConfiguration $extensionConfiguration
    )
    {}

    /**
     * @param string $extensionName
     *
     * @return self
     */
    public function setExtensionName(string $extensionName): self
    {
        $this->extensionName = preg_replace('/[^a-z0-9]/', '', mb_strtolower($extensionName, 'utf-8'));

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getPluginConfig(string $key = 'settings'): mixed
    {
        $config = $this->getFullTSConfig();
        $txExtensionName = $this->getTxExtensionName();

        $config = $config['plugin.'][$txExtensionName . '.'] ?? [];
        $value = $config[$key . '.'] ?? $config[$key] ?? null;

        if ($value && is_array($value)) {
            return GeneralUtility::removeDotsFromTS($value);
        }

        return $value ?? null;
    }

    /**
     * @param string $key
     * @return string
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function getExtensionConfig(string $key): string
    {
        $txExtensionName = $this->getExtensionName();

        $config = $this->extensionConfiguration->get($txExtensionName, $key);

        return $config ?? '';
    }

    /**
     * @return array
     */
    public function getFullTSConfig(): array
    {
        try {
            return $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
        } catch (\Throwable $ex) {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function getTxExtensionName(): string
    {
        return self::TX_EXTENSION_PREFIX . $this->extensionName;
    }

    /**
     * @return string
     */
    protected function getExtensionName(): string
    {
        return $this->extensionName;
    }
}
