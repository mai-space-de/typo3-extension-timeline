<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Domain\Repository;

use Maispace\MaiTimeline\Domain\Model\Entry;
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
}
