<?php
namespace T3dev\Mask2blocks\Controller;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseInterface;
use T3dev\Mask2blocks\Helper\ConfigHelper;
use T3dev\Mask2blocks\Service\AnalyseService;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class MigrationController extends BaseModuleController
{
    public function __construct(
        protected AnalyseService        $analyseService,
        protected ModuleTemplateFactory $moduleTemplateFactory
    )
    {
        parent::__construct($moduleTemplateFactory);
    }

    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function indexAction(): ResponseInterface
    {
        // @todo: for security reasons checks if the user role is admin

        // $showHidden = ConfigHelper::getExtensionConfig('showHidden');

        // Analyse DB Schema and fetch mask elements based on active/both status
        $fields = $this->analyseService->fetchMaskFields();

        // Implement logic here for custom events...

        // Pass params to view
        $this->moduleTemplate->assignMultiple([
            'fields' => $fields
        ]);

        return $this->moduleTemplate->renderResponse();
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    public function prepareAction(): ResponseInterface
    {
        // this method will collect all needed information for the migration process.
        // @todo: better solution is to make this function called through JS every time the user check/uncheck a mask element.

        /**
         * STEPS :
         *
         * When the user gets the first time on the module, a JSON should be created with mask field uid and status (whether the checkbox is checked or not).
         * when the user perform any action, checks/unchecks a checkbox, the status of the field in question is updated.
         * set the JSON in html, this comes handy when the user moves to the overview action, but he wants to go back.
         *
         * NOTE : JSON output should be saved somehow, in a way if the user exists the module and comes back, the same checkboxes should be rechecked again :
         * Better way to search a way to save the state using JS
         */

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(
            json_encode(['result' => 'test'], JSON_THROW_ON_ERROR),
        );

        return $response;
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    public function overviewAction(): ResponseInterface
    {
        // @todo: this function comes handy if the user wants to rollback at the last moment and make some changes.
        $this->moduleTemplate->assignMultiple([
            'overview' => 'overview'
        ]);
        return $this->htmlResponse();
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    public function migrateAction(): ResponseInterface
    {
        // @todo: call to YamlWriterService to add content block configuration, the path should be also specified by the user where he wants to save yaml configuration.
        return $this->redirect('Index');
    }
}