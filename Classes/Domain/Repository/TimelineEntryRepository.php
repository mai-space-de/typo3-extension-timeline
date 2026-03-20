<?php

declare(strict_types=1);

namespace Maispace\Timeline\Domain\Repository;

use Maispace\Timeline\Domain\Model\TimelineEntry;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for TimelineEntry records, sorted by year descending by default.
 *
 * @extends Repository<TimelineEntry>
 */
class TimelineEntryRepository extends Repository
{
    /**
     * Default ordering: newest year first.
     */
    protected $defaultOrderings = [
        'year' => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * Find all entries ordered by year ascending (oldest first).
     *
     * @return QueryResultInterface<TimelineEntry>
     */
    public function findAllOrderedByYearAscending(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setOrderings(['year' => QueryInterface::ORDER_ASCENDING]);
        return $query->execute();
    }

    /**
     * Find all entries ordered by year descending (newest first).
     *
     * @return QueryResultInterface<TimelineEntry>
     */
    public function findAllOrderedByYearDescending(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setOrderings(['year' => QueryInterface::ORDER_DESCENDING]);
        return $query->execute();
    }

    /**
     * Find entries by category uid.
     *
     * @return QueryResultInterface<TimelineEntry>
     */
    public function findByCategory(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid)
        );
        $query->setOrderings(['year' => QueryInterface::ORDER_DESCENDING]);
        return $query->execute();
    }
}
