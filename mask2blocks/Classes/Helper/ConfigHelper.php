<?php
namespace T3dev\Mask2blocks\Helper;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use T3dev\Mask2blocks\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class MigrationController
 *
 * @package T3dev\Mask2blocks\Helper
 */
class ConfigHelper
{
    private const EXTENSION_NAME = 'mask2blocks';

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getPluginConfig(): mixed
    {
        return GeneralUtility::getContainer()
            ->get(ConfigurationService::class)
            ->setExtensionName(self::EXTENSION_NAME)
            ->getPluginConfig() ?? [];
    }

    /**
     * @param string $key
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getExtensionConfig(string $key): string
    {
        return GeneralUtility::getContainer()
            ->get(ConfigurationService::class)
            ->setExtensionName(self::EXTENSION_NAME)
            ->getExtensionConfig($key) ?? '';
    }
}