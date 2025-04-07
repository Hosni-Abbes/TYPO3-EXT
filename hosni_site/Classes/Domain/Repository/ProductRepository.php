<?php
namespace Hosni\HosniSite\Domain\Repository;

use GeorgRinger\News\Domain\Model\News;
use Hosni\HosniSite\Domain\Model\Product;
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
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * Product repository
 */
class ProductRepository extends Repository {

 
    /**
     * Returns all objects of this repository.
     *
     * @return QueryResultInterface|array
     * @phpstan-return QueryResultInterface|iterable<T>
     */
    public function findAll()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $this->addTableToCacheTags($query);
        return $query->execute();
    }

    /**
     * @param int $uid
     * @return Product|null
     */
   public function findByUid($uid): ?Product
   {
       $query = $this->createQuery();
       $query->getQuerySettings()->setRespectStoragePage(false);
       $query->getQuerySettings()->setRespectSysLanguage(false);

       return $query->matching(
           $query->equals('uid', $uid)
       )->execute()->getFirst();
   }

    /**
     * @param array $uids
     * @param string $orderBy
     * 
     * @return QueryResult|null
     */
    public function findManyByUids(array $uids, string $orderBy): ?QueryResult
    {
        if(empty($uids) ) return null;

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        $ordering = $orderBy == 'title' ?
         [$orderBy => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]
         : [$orderBy => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];
        $query->setOrderings($ordering);


        // $query->setOffset(10);
        // $query->setLimit(20);

        return $query->matching($query->in('uid', $uids))->execute();
    }

    /**
     * @param string $uids
     * @return array
     */
    public function findByInsertedBackendRecords(string $uids): array
    {
        if(empty($uids) ) return null;
        
        $query = $this->createQuery();
        $sql = 'select uid';
        $sql .= ' from tx_hosnisite_domain_model_product';
        $sql .= ' where uid IN ('.$uids.')';
        $sql .= ' order by FIELD(uid, '.$uids.')';
        
        return $query->statement($sql)->execute(true);
    }
}
