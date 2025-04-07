<?php
declare(strict_types=1);

namespace Hosni\HosniSite\Middleware;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RedirectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $client,
        private Context $context,
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // If user access to specific API page.
        if ($request->getRequestTarget() === '/api') {
            // Get FE user FrontendUserAuthentication
            $frontendUser = $request->getAttribute('frontend.user');
            // check if fe user is connected
            $isUserLoggedIn = $this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn');
            if(!$isUserLoggedIn) {
                $response = $this->responseFactory->createResponse()
                    ->withStatus(403, 'Forbidden')
                    ->withHeader('Content-Type', 'text/html; charset=utf-8');
                $response->getBody()->write('Must be logged in!');
                // return Forbidden response
                return $response;
            }

            // Return to some path
            // $url = GeneralUtility::locationHeaderUrl((string)'/page');
            // /** @var RedirectResponse $response */
            // $response = GeneralUtility::makeInstance(RedirectResponse::class, $url);

            // Return json products
            $products = $this->getProducts();
            $response = $this->responseFactory->createResponse()
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json; charset=utf-8');
            $response->getBody()->write(json_encode($products));
            

            return $response;
        }

        return $handler->handle($request);
    }


    /**
     * @return array
     */
    private function getProducts(): array
    {
        $table = 'tx_hosnisite_domain_model_product';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

        return $queryBuilder
            ->select('uid', 'pid', 'crdate', 'starttime', 'endtime', 'title', 'desc')
            ->from($table)
            // ->orderBy('title')
            ->orderBy('crdate', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
