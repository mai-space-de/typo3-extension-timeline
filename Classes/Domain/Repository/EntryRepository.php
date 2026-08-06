<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Domain\Repository;

use Maispace\MaiTimeline\Domain\Model\Entry;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for timeline entry records (tx_maitimeline_entry).
 *
 * Provides filtering by category and ordering by date descending
 * (most recent events first).
 *
 * @extends Repository<Entry>
 */
class EntryRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * Find entries filtered by category UID.
     *
     * @param int $categoryUid Category UID to filter by
     * @param int $limit Maximum number of results (0 = no limit)
     * @return QueryResultInterface<Entry>
     */
    public function findByCategoryUid(int $categoryUid, int $limit = 0): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($categoryUid > 0) {
            $constraints[] = $query->contains('categories', $categoryUid);
        }

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * Find all entries with optional limit.
     *
     * @param int $limit Maximum number of results (0 = no limit)
     * @return QueryResultInterface<Entry>
     */
    public function findAllWithLimit(int $limit = 0): QueryResultInterface
    {
        $query = $this->createQuery();

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * Create QueryBuilder for paginated queries.
     *
     * Use this method with QueryBuilderPaginator for efficient pagination.
     *
     * @param int $storagePid Storage page ID filter (0 = no filter)
     * @param int $categoryUid Category UID filter (0 = no filter)
     * @return QueryBuilder
     */
    public function createQueryBuilderForPagination(int $storagePid = 0, int $categoryUid = 0): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_maitimeline_entry');

        $queryBuilder
            ->select('*')
            ->from('tx_maitimeline_entry')
            ->orderBy('date', 'DESC');

        if ($storagePid > 0) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($storagePid, \PDO::PARAM_INT))
                );
        }

        if ($categoryUid > 0) {
            // Category filter via mm table
            $queryBuilder
                ->leftJoin(
                    'tx_maitimeline_entry',
                    'sys_category_record_mm',
                    'mm',
                    $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->quoteIdentifier('tx_maitimeline_entry.uid'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->createNamedParameter($categoryUid, \PDO::PARAM_INT))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('tx_maitimeline_entry'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories'))
                );
        }

        return $queryBuilder;
    }
}
