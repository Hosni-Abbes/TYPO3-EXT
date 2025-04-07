<?php


namespace Hosni\HosniSite\Controller;

use Psr\Http\Message\ResponseInterface;
use Hosni\HosniSite\Domain\Model\Product;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller of products records
 */
class ProductController extends BaseController
{

    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        // Simple Select All Products
        //$products = $this->productRepository->findAll();

        // Select Only Products selected from BE - Using custom secelted Ordering
        $selectedProducts = !is_array($this->settings['products']) ? GeneralUtility::intExplode(',', $this->settings['products']) : $this->settings['products'];
        $orderBy = $this->settings['orderBy'];
        $products = $this->productRepository->findManyByUids($selectedProducts, $orderBy);

        //Ordering by field (BE selected order)
        /*$selectedProductsStringUids = $this->settings['products'];
        $products = $this->productRepository->findByInsertedBackendRecords($selectedProductsStringUids);*/
        
        $assignedValues = [
            'products' => $products,
            'settings' => $this->settings,
        ];
        $this->view->assignMultiple($assignedValues);
        
        return $this->htmlResponse();
    }

    /**
     * @param Product $product
     * @return ResponseInterface
     */
    public function createAction(Product $product):ResponseInterface
    {
        
        $product->setTitle($product->getTitle());
        $product->setDesc($product->getDesc());
        $this->productRepository->add($product);

        $uri = $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                'list',
                [],
                'Product',
                'HosniSite',
                'Pi1'
            );
        return $this->redirectToUri($uri);

    }

    /**
     * @param Product|null $product
     * @return ResponseInterface
     */
    public function formAction(Product $product = null): ResponseInterface
    {
        // DebugUtility::debug($this->request->getArguments());die;
        $action = $this->request->hasArgument('isEdit') ? 'update' : 'create';
        $this->view->assignMultiple([
            'product' => $product,
            'action' => $action
        ]);
        return $this->htmlResponse();
    }

    /**
     * @param Product $product
     * @return ResponseInterface
     */
    public function updateAction(Product $product): ResponseInterface
    {
        $product->setTitle($product->getTitle());
        $product->setDesc($product->getDesc());
        $this->productRepository->add($product);
        $this->em->persistAll();

        $uri = $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                'list',
                [],
                'Product',
                'HosniSite',
                'Pi1'
            );
        return $this->redirectToUri($uri);
    }

    /**
     * @param Product $product
     * @return ResponseInterface
     */
    public function deleteAction(Product $product): ResponseInterface
    {
        $this->productRepository->remove($product);
        
        $uri = $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                'list',
                [],
                'Product',
                'HosniSite',
                'Pi1'
            );

        return $this->redirectToUri($uri);
    }

}
