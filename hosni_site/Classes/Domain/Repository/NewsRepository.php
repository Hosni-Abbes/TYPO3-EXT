<?php
namespace Hosni\HosniSite\Domain\Repository;

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Domain\Repository\NewsRepository as RepositoryNewsRepository;
use GeorgRinger\News\Utility\ConstraintHelper;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\NotInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * News repository with all the callable functionality
 */
class NewsRepository extends RepositoryNewsRepository {

    /**
     * Returns an array of constraints created from a given demand object.
     *
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @return (AndInterface|ComparisonInterface|ConstraintInterface|NotInterface|OrInterface|null)[]
     *
     * @psalm-return array<string, (AndInterface | ComparisonInterface | ConstraintInterface | NotInterface | OrInterface | null)>
     */
    protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand): array
    {
        $constraints = [];

        
        if ($demand->getIsFocus() == 0 || $demand->getIsFocus() == 1 ) {
            $constraints['isFocus'] = $query->equals('is_focus', $demand->getIsFocus());
        }

        if ($demand->getCategories() && $demand->getCategories() !== '0') {
            $constraints['categories'] = $this->createCategoryConstraint(
                $query,
                $demand->getCategories(),
                $demand->getCategoryConjunction(),
                $demand->getIncludeSubCategories()
            );
        }

        if ($demand->getAuthor()) {
            $constraints['author'] = $query->equals('author', $demand->getAuthor());
        }

        if ($demand->getTypes()) {
            $constraints['types'] = $query->in('type', $demand->getTypes());
        }

        // archived
        $currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
        if ($demand->getArchiveRestriction() === 'archived') {
            $constraints['archived'] = $query->logicalAnd(
                $query->lessThan('archive', $currentTimestamp),
                $query->greaterThan('archive', 0)
            );
        } elseif ($demand->getArchiveRestriction() === 'active') {
            $constraints['active'] = $query->logicalOr(
                $query->greaterThanOrEqual('archive', $currentTimestamp),
                $query->equals('archive', 0)
            );
        }

        // Time restriction greater than or equal
        $timeRestrictionField = $demand->getDateField();
        $timeRestrictionField = (empty($timeRestrictionField)) ? 'datetime' : $timeRestrictionField;

        if ($demand->getTimeRestriction()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionLow($demand->getTimeRestriction());

            $constraints['timeRestrictionGreater'] = $query->greaterThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // Time restriction less than or equal
        if ($demand->getTimeRestrictionHigh()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionHigh($demand->getTimeRestrictionHigh());

            $constraints['timeRestrictionLess'] = $query->lessThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // top news
        if ($demand->getTopNewsRestriction() == 1) {
            $constraints['topNews1'] = $query->equals('istopnews', 1);
        } elseif ($demand->getTopNewsRestriction() == 2) {
            $constraints['topNews2'] = $query->equals('istopnews', 0);
        }

        // storage page
        if ($demand->getStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints['pid'] = $query->in('pid', $pidList);
        }

        // month & year OR year only
        if ($demand->getYear() > 0) {
            if (!$demand->getDateField()) {
                throw new \InvalidArgumentException('No Datefield is set, therefore no Datemenu is possible!');
            }
            if ($demand->getMonth() > 0) {
                if ($demand->getDay() > 0) {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                    $end = mktime(23, 59, 59, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                } else {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
                    $end = mktime(23, 59, 59, $demand->getMonth() + 1, 0, $demand->getYear());
                }
            } else {
                $begin = mktime(0, 0, 0, 1, 1, $demand->getYear());
                $end = mktime(23, 59, 59, 12, 31, $demand->getYear());
            }
            $dateConstraints = [
                $query->greaterThanOrEqual($demand->getDateField(), $begin),
                $query->lessThanOrEqual($demand->getDateField(), $end),
            ];
            $constraints['datetime'] = $query->logicalAnd(...$dateConstraints);
        }

        // Tags
        $tags = $demand->getTags();
        if ($tags && is_string($tags)) {
            $tagList = explode(',', $tags);

            $subConstraints = [];
            foreach ($tagList as $singleTag) {
                $subConstraints[] = $query->contains('tags', $singleTag);
            }
            $constraints['tags'] = $query->logicalOr(...$subConstraints);
        }

        // Search
        $searchConstraints = $this->getSearchConstraints($query, $demand);
        if (!empty($searchConstraints)) {
            $constraints['search'] = $query->logicalAnd(...$searchConstraints);
        }

        // Exclude already displayed
        if ($demand->getExcludeAlreadyDisplayedNews() && isset($GLOBALS['EXT']['news']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
            $constraints['excludeAlreadyDisplayedNews'] = $query->logicalNot(
                $query->in(
                    'uid',
                    $GLOBALS['EXT']['news']['alreadyDisplayed']
                )
            );
        }

        // Hide id list
        $hideIdList = $demand->getHideIdList();
        if ($hideIdList) {
            $constraints['hideIdInList'] = $query->logicalNot(
                $query->in(
                    'uid',
                    GeneralUtility::intExplode(',', $hideIdList, true)
                )
            );
        }

        // Id list
        $idList = $demand->getIdList();
        if ($idList) {
            $constraints['idList'] = $query->in('uid', GeneralUtility::intExplode(',', $idList, true));
        }

        // Clean not used constraints
        foreach ($constraints as $key => $value) {
            if ($value === null) {
                unset($constraints[$key]);
            }
        }

        return $constraints;
    }


    /**
     * @param string $table table name
     */
    protected function getQueryBuilder(string $table): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }

}
