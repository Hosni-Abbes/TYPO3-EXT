<?php


namespace Hosni\HosniSite\Controller;

use Psr\Http\Message\ResponseInterface;
use Hosni\HosniSite\Domain\Model\Product;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportController extends BaseController
{
    /** @var ModuleTemplate $moduleTemplate */
    protected ModuleTemplate $moduleTemplate;

    /**
     * @return void
     */
    public function initializeAction():void
    {
        $moduleTemplateFactory = GeneralUtility::makeInstance(ModuleTemplateFactory::class);
        $this->moduleTemplate = $moduleTemplateFactory->create($this->request);
        
    }

    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $products = $this->productRepository->findAll();
        $assignedValues = [
            'products' => $products
        ];
        
        $this->moduleTemplate->assignMultiple($assignedValues);

        return $this->moduleTemplate->renderResponse('list');
    }

    /**
     * @return ResponseInterface
     */
    public function importAction(): ResponseInterface
    {
        
        // @todo: implement import products...


        /*$countProducts = 10;
        $products = $this->productRepository->findAll();        
        $this->moduleTemplate->assignMultiple([
            'products' => $products,
            'countProducts' => $countProducts
        ]);*/

        return $this->moduleTemplate->renderResponse('import');
    }

}
