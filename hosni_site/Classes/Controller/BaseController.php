<?php


namespace Hosni\HosniSite\Controller;


use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Hosni\HosniSite\Domain\Repository\ProductRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Mvc\Request;


abstract class BaseController extends ActionController
{    
    public function __construct(
        protected ProductRepository $productRepository,
        protected PersistenceManager $em,
    ) {}

    /**
     * @return void
     */
    public function initializeAction():void
    {

        if($this->request->hasArgument('product')) {
            /** @var Request $product */
            $product = $this->request->getArgument('product');

            $this->request = $this->request->withArgument('product', $product);
            $this->arguments->getArgument('product')
                    ->getPropertyMappingConfiguration()
                    ->allowAllProperties(
                        'title', 'desc'
                    );
        }
    }

}
