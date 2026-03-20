<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Controller;

use Maispace\MaiTimeline\Domain\Model\TimelineEntry;
use Maispace\MaiTimeline\Domain\Repository\TimelineEntryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Controller for the Timeline plugin.
 *
 * Provides three views:
 *  - index:   vertical timeline (all entries, newest first)
 *  - compact: compact list view
 *  - show:    single-entry detail view
 *
 * Respects FlexForm settings: orderDirection, limit, categoryUid.
 */
class TimelineController extends ActionController
{
    public function __construct(
        private readonly TimelineEntryRepository $timelineEntryRepository
    ) {}

    /**
     * Vertical timeline view – all entries ordered by configured direction.
     */
    public function indexAction(): ResponseInterface
    {
        $entries = $this->fetchEntries();
        $this->view->assign('entries', $entries);
        return $this->htmlResponse();
    }

    /**
     * Compact list view – all entries ordered by configured direction.
     */
    public function compactAction(): ResponseInterface
    {
        $entries = $this->fetchEntries();
        $this->view->assign('entries', $entries);
        return $this->htmlResponse();
    }

    /**
     * Single-entry detail view.
     */
    public function showAction(TimelineEntry $timelineEntry): ResponseInterface
    {
        $this->view->assign('timelineEntry', $timelineEntry);
        return $this->htmlResponse();
    }

    /**
     * Fetch entries applying FlexForm settings (orderDirection, limit, categoryUid).
     *
     * @return iterable<TimelineEntry>
     */
    private function fetchEntries(): iterable
    {
        $orderDirection = strtoupper((string)($this->settings['orderDirection'] ?? 'DESC'));
        $limit = max(0, (int)($this->settings['limit'] ?? 0));
        $categoryUid = (int)($this->settings['categoryUid'] ?? 0);

        if ($categoryUid > 0) {
            $query = $this->timelineEntryRepository->createQuery();
            $query->matching($query->contains('categories', $categoryUid));
            $query->setOrderings(['year' => $orderDirection === 'ASC'
                ? QueryInterface::ORDER_ASCENDING
                : QueryInterface::ORDER_DESCENDING,
            ]);
            if ($limit > 0) {
                $query->setLimit($limit);
            }
            return $query->execute();
        }

        $query = $this->timelineEntryRepository->createQuery();
        $query->setOrderings(['year' => $orderDirection === 'ASC'
            ? QueryInterface::ORDER_ASCENDING
            : QueryInterface::ORDER_DESCENDING,
        ]);
        if ($limit > 0) {
            $query->setLimit($limit);
        }
        return $query->execute();
    }
}
