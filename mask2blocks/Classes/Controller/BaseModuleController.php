<?php
namespace T3dev\Mask2blocks\Controller;

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class BaseModuleController
 */
abstract class BaseModuleController extends ActionController
{
    protected ModuleTemplate $moduleTemplate;

    /**
     * @param ModuleTemplateFactory $moduleTemplateFactory
     */
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory
    ) {
    }

    /**
     * @return void
     */
    public function initializeAction(): void
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        parent::initializeAction();
    }
}
