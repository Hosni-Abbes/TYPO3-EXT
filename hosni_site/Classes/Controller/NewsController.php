<?php


namespace Hosni\HosniSite\Controller;

use GeorgRinger\News\Controller\NewsController as ControllerNewsController;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use GeorgRinger\News\Domain\Repository\TagRepository;
use GeorgRinger\News\Event\NewsListActionEvent;
use GeorgRinger\News\Pagination\QueryResultPaginator;
use GeorgRinger\News\Utility\Cache;
use GeorgRinger\News\Utility\ClassCacheManager;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller of news records
 */
class NewsController extends ControllerNewsController
{
    protected NewsRepository $newsRepository;

    protected CategoryRepository $categoryRepository;

    protected TagRepository $tagRepository;

    /** @var array */
    protected $ignoredSettingsForOverride = ['demandclass', 'orderbyallowed', 'selectedList'];

    /**
     * Original settings without any magic done by stdWrap and skipping empty values
     *
     * @var array
     */
    protected $originalSettings = [];

    public function __construct(
        NewsRepository $newsRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->newsRepository = $newsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Initializes the current action
     */
    protected function initializeAction(): void
    {
        GeneralUtility::makeInstance(ClassCacheManager::class)->reBuildSimple();
        $this->buildSettings();
        if (isset($this->settings['format'])) {
            $this->request = $this->request->withFormat($this->settings['format']);
        }
        // Only do this in Frontend Context
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            // We only want to set the tag once in one request, so we have to cache that statically if it has been done
            static $cacheTagsSet = false;

            /** @var $typoScriptFrontendController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(['tx_news']);
                $cacheTagsSet = true;
            }
        }
    }

    /**
     * Output a list view of news
     *
     * @param array|null $overwriteDemand
     */
    public function listAction(?array $overwriteDemand = null): ResponseInterface
    {
        $possibleRedirect = $this->forwardToDetailActionWhenRequested();
        if ($possibleRedirect) {
            return $possibleRedirect;
        }

        $demand = $this->createDemandObjectFromSettings($this->settings);
        $demand->setActionAndClass(__METHOD__, self::class);

        if ((int)($this->settings['disableOverrideDemand'] ?? 1) !== 1 && $overwriteDemand !== null) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }
        $newsRecords = $this->newsRepository->findDemanded($demand);
        DebugUtility::debug($newsRecords);die;
        $assignedValues = [
            'news' => $newsRecords,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'categories' => null,
            'tags' => null,
            'settings' => $this->settings,
        ];
        $categoriesList = $demand->getCategories();
        if (is_string($categoriesList)) {
            $categoriesList = GeneralUtility::trimExplode(',', $categoriesList);
        }
        if (!empty($categoriesList)) {
            $assignedValues['categories'] = $this->categoryRepository->findByIdList($categoriesList);
        }

        if ($demand->getTags() !== '') {
            $tagList = $demand->getTags();
            $tagList = GeneralUtility::trimExplode(',', $tagList);
            if (!empty($tagList)) {
                $assignedValues['tags'] = $this->tagRepository->findByIdList($tagList);
            }
        }

        $event = $this->eventDispatcher->dispatch(new NewsListActionEvent($this, $assignedValues, $this->request));
        $this->view->assignMultiple($event->getAssignedValues());

        // pagination
        if ((int)($this->settings['hidePagination'] ?? 0) === 0) {
            $paginationConfiguration = $this->settings['list']['paginate'] ?? [];
            $itemsPerPage = (int)(($paginationConfiguration['itemsPerPage'] ?? '') ?: 10);
            $maximumNumberOfLinks = (int)($paginationConfiguration['maximumNumberOfLinks'] ?? 0);

            $currentPage = max(1, $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1);
            $paginator = GeneralUtility::makeInstance(QueryResultPaginator::class, $event->getAssignedValues()['news'], $currentPage, $itemsPerPage, (int)($this->settings['limit'] ?? 0), (int)($this->settings['offset'] ?? 0));
            $paginationClass = $paginationConfiguration['class'] ?? SimplePagination::class;
            $pagination = $this->getPagination($paginationClass, $maximumNumberOfLinks, $paginator);

            $this->view->assign('pagination', [
                'currentPage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
            ]);
        }

        Cache::addPageCacheTagsByDemandObject($demand);
        return $this->htmlResponse();
    }



    

}
