<?php
namespace T3dev\Mask2blocks\Service;

use Doctrine\DBAL\Exception;
use T3dev\Mask2blocks\Utility\SettingsUtility;
use T3dev\Mask2blocks\Validation\ConfigurationValidator;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Error\Exception as CoreException;

/**
 * MaskAnalyzerService Class
 */
class AnalyseService
{
    private const TT_CONTENT_TABLE = 'tt_content';
    private const CTYPE_MASK_ELEMENTS_PREFIX = 'mask_';
    protected array $extensionSettings;

    /**
     * @param ConnectionPool $connectionPool
     * @param JsonReaderService $jsonReaderService
     * @param SettingsUtility $settingsUtility
     * @param YamlWriterService $yamlWriterService
     */
    public function __construct(
        private readonly ConnectionPool $connectionPool,
        protected JsonReaderService $jsonReaderService,
        protected SettingsUtility $settingsUtility,
        protected YamlWriterService $yamlWriterService
    ) {
        $this->extensionSettings = $settingsUtility->getExtBackendSettings();
    }

    /**
     * Analyse DB to fetch all active mask elements.
     * By default, hidden records are not processed, which can be activated from EXT settings.
     *
     * @return array
     * @throws Exception
     */
    public function fetchMaskFields(): array
    {
        $enableHiddenElements = (int)$this->extensionSettings['enableHiddenElements'];
        
        $queryBuilder = $this->getDatabaseConnection();

        // KEEP for later use
       if ($enableHiddenElements) {
           $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
       }

        $queryBuilder
            ->select('uid', 'pid', 'CType')
            ->from(self::TT_CONTENT_TABLE)
            ->where(
                $queryBuilder->expr()->like(
                    'CType',
                    $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards(self::CTYPE_MASK_ELEMENTS_PREFIX) . '%')
                )
            );

        $result = $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative();

        // foreach($result as $row) {
        //     debug($row);die;
            $this->analyzeFileBasedConfigurations();
        // }

        // @todo: call to JsonReaderService and call to extractMaskFieldTitle method

        // @todo: sort mask elements based on the extracted title



        return $result;
    }

    /**
     * @return void
     */
    public function analyzeFileBasedConfigurations(): void
    {
        $configurations = [];
        
        $jsonContent = $this->jsonReaderService->loadMaskConfiguration();
        if(is_array($jsonContent) && !empty($jsonContent)) {
            $configurations['json'] = $jsonContent;
        }

        // Process the configurations as needed
        $this->processConfigurations($configurations);
    }

    /**
     * @param array $configurations
     * @return void
     * 
     * @throws CoreException
     */
    private function processConfigurations(array $configurations): void
    {
        // Check for json configuration
        if (!isset($configurations['json']) || empty($configurations['json'])) 
            throw new CoreException('No JSON configuration found.', 1422625075);
    
        $jsonConfig = (array)$configurations['json'];

        // Check if the json configuration has valid structure
        if (!ConfigurationValidator::isValidJsonConfigStructure($jsonConfig))
            throw new CoreException('Invalid JSON configuration.', 1444142481);

        // Loop on Mask elements and return an array containing all mapped elements readed from mask.json in order to transform into .yaml file
        $preparedElements = [];
        foreach ($jsonConfig['tables']['tt_content']['elements'] as $elementInfo) {
            // TCA array contain all fields configurations exist in mask
            $tcaArray = $jsonConfig['tables']['tt_content']['tca'];
            $elementColumns = isset($elementInfo['columns']) ? $elementInfo['columns'] : [];
            
            // Filter TCA array and return only matches TCA fields for each mask element
            $elementConfig = array_filter($tcaArray, function ($item) use ($elementColumns) {
                return in_array($item['fullKey'], $elementColumns) ;
            });

            // Generate array for yaml
            $preparedElements[] = $this->yamlWriterService->generateYamlConfigurations($elementInfo, $elementConfig);
        }
        // debug($preparedElements);die;

        // display messages for ready elements
        $this->preparedYamlElements($preparedElements);

        // DEBUG - testing writeFile service
        foreach ($preparedElements as $yamlElement) {
            $this->yamlWriterService->writeYamlFile($yamlElement);
        }
    }

    /**
     * @param array $preparedElements
     * 
     * @return void
     */
    private function preparedYamlElements(array $preparedElements): void
    {
        // Display messages 
        foreach ($preparedElements as $element) {
            echo "Element: " . $element['identifier'] . " is now ready for migration.";
            print_r($element);
        }
    }

    /**
     * @return void
     */
    private function transformElement($key, $element): void
    {
        // Example transformation: Add a new field to the element
        $element['transformed'] = true;

        // Log the transformation
        echo "Transformed element: $key\n";
        print_r($element);

        // Further processing can be added here, such as saving the transformed element
    }

    /**
     * @return void
     */
    public function mapFieldsToContentBlocks(): void
    {
        // Example: Define a mapping between MASK fields and content block fields
        $fieldMapping = [
            'mask_field1' => 'content_block_field1',
            'mask_field2' => 'content_block_field2',
            // Add more mappings as needed
        ];

        // Example: Iterate over the MASK fields and map them to content blocks
        foreach ($fieldMapping as $maskField => $contentBlockField) {
            echo "Mapping $maskField to $contentBlockField\n";

            // Implement logic to perform the mapping
            // For example, transform data from the MASK field to fit the content block field
            $this->transformField($maskField, $contentBlockField);
        }
    }

    /**
     * @return void
     */
    private function transformField($maskField, $contentBlockField): void
    {
        // Implement logic to transform and map the field data
        // Example: Retrieve data from the MASK field and store it in the content block field
        echo "Transforming data from $maskField to $contentBlockField\n";
        // Add transformation logic here
    }

    public function validateComplexConfigurations()
    {
        // Identify complex configurations like nested structures or inline elements
        // Plan for special handling during migration
    }

    /**
     * Instantiate DB connection.
     *
     * @return QueryBuilder
     */
    private function getDatabaseConnection(): QueryBuilder
    {
        return $this->connectionPool->getQueryBuilderForTable(self::TT_CONTENT_TABLE);
    }
}