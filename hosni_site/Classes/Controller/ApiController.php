<?php


namespace Hosni\HosniSite\Controller;


use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use Hosni\HosniSite\Domain\Model\Product;
use Hosni\HosniSite\Domain\Repository\ProductRepository;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller of products records
 */
class ApiController extends ActionController
{
    
    public function __construct(
        protected ProductRepository $productRepository,
        protected PersistenceManager $em
    ) {}

    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $products = $this->productRepository->findAll();

        $result = [];

        foreach ($products as $product) {
            $result[] = [
                'title' => $product->getTitle(),
                'description' => $product->getDesc(),
            ];
        }
        

        $response = $this->responseFactory->createResponse()
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($result));
        
        return $response;
    }

}
