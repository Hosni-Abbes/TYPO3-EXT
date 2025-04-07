<?php
declare(strict_types=1);

namespace T3dev\Mask2blocks\Service;

use T3dev\Mask2blocks\Exception\InvalidPathException;
use T3dev\Mask2blocks\Utility\SettingsUtility;
use T3dev\Mask2blocks\Utility\FileUtility;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlWriterService
 *
 * @package T3dev\Mask2blocks\Service
 */
class YamlWriterService
{
    protected array $extensionSettings;

    /**
     * @param SettingsUtility $settingsUtility
     */
    public function __construct(
        protected SettingsUtility $settingsUtility
    ) {
        $this->extensionSettings = $settingsUtility->getExtBackendSettings();
    }

    /**
     * @param array $elementInfos
     * @param array $elementConfig
     * 
     * @return array
     */
    public function generateYamlConfigurations(array $elementInfos, array $elementConfig): array
    {
        // Element's fields configuration
        foreach ($elementConfig as $key => $value) {
            $index = array_search($key, $elementInfos['columns']);
            $fields[str_replace('tx_mask_', '', $key)] = [
                'type' => isset($value['config']['type']) ? $value['config']['type'] : '',
                'label' => isset($elementInfos['labels'][$index]) ? $elementInfos['labels'][$index] : '',
                'description' => isset($elementInfos['descriptions'][$index]) ? $elementInfos['descriptions'][$index] : '',
                'config' => [
                    'nullable' => isset($value['config']['nullable']) ? $value['config']['nullable'] : '',
                    'required' => isset($value['config']['required']) ? $value['config']['required'] : '',
                ],
            ];
        }

        // Element mapped
        $yamlElement = [
            'identifier' => $elementInfos['key'],
            'title' => ucfirst(str_replace('_', ' ', $elementInfos['key'])),
            'description' => isset($elementInfos['description']) ? $elementInfos['description'] : '',
            'icon' => isset($elementInfos['icon']) ? $elementInfos['icon'] : '',
            'fields' => $fields
        ];
        
        return $yamlElement;
    }

    /**
     * @param array $yamlElement
     * @return void
     * 
     * @throws InvalidPathException
     */
    public function writeYamlFile(array $yamlElement): void
    {
        // Convert PHP array to YAML string
        $yaml = Yaml::dump($yamlElement, 2, 4);

        // Get contentBlocks path to register yaml files
        $filePath = FileUtility::validateFilePath($this->extensionSettings['contentBlocksYamlPath']);
        
        // Check if the filePath exists
        if (!file_exists($filePath)) {
            throw new InvalidPathException(
                'Content blocks configuration folder not found: ' . $this->extensionSettings['contentBlocksYamlPath'],
                1496928632
            );
        }
        $yamlFilePath = $filePath . $yamlElement['identifier'] . '.yaml';
        file_put_contents($yamlFilePath, $yaml);
    }

    public function migrateToFluidTemplates(): void
    {
        // Example MASK template paths (this should be replaced with actual paths)
        $maskTemplatePaths = [
            'path/to/mask/template1.html',
            'path/to/mask/template2.html',
            // Add more template paths as needed
        ];

        foreach ($maskTemplatePaths as $templatePath) {
            // Read the MASK template content
            $maskTemplateContent = file_get_contents($templatePath);

            // Convert MASK template to Fluid template
            $fluidTemplateContent = $this->convertToFluid($maskTemplateContent);

            // Define the path for the new Fluid template
            $fluidTemplatePath = str_replace('path/to/mask', 'path/to/fluid', $templatePath);

            // Save the Fluid template
            file_put_contents($fluidTemplatePath, $fluidTemplateContent);
        }
    }

    private function convertToFluid(string $maskTemplateContent): string {
        // Implement the conversion logic from MASK to Fluid template
        // This is a placeholder for actual conversion logic
        $fluidTemplateContent = $maskTemplateContent; // Replace with actual conversion

        // Example: Replace MASK-specific syntax with Fluid syntax
        // $fluidTemplateContent = str_replace('MASK_SYNTAX', 'FLUID_SYNTAX', $fluidTemplateContent);

        return $fluidTemplateContent;
    }
}
