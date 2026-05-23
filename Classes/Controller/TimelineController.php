<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Controller;

use Maispace\MaiTimeline\Domain\Repository\EntryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Timeline controller for the mai_timeline_list plugin.
 *
 * Renders a list of timeline entries (tx_maitimeline_entry) filtered by
 * category and limited by count. FlexForm settings:
 * - settings.storagePid: Storage page UID for timeline records
 * - settings.categoryUid: Category UID filter (0 = all categories)
 * - settings.limit: Maximum number of entries (0 = no limit)
 */
final class TimelineController extends ActionController
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
    ) {}

    /**
     * List action — renders timeline entries from tx_maitimeline_entry.
     *
     * Applies storage PID, category filter, and limit from FlexForm settings.
     */
    public function listAction(): ResponseInterface
    {
        $storagePid = (int) ($this->settings['storagePid'] ?? 0);
        $categoryUid = (int) ($this->settings['categoryUid'] ?? 0);
        $limit = (int) ($this->settings['limit'] ?? 0);

        if ($storagePid > 0) {
            $this->entryRepository->setDefaultQuerySettings(
                $this->entryRepository->createQuery()->getQuerySettings()->setStoragePageIds([$storagePid]),
            );
        }

        if ($categoryUid > 0) {
            $entries = $this->entryRepository->findByCategoryUid($categoryUid, $limit);
        } else {
            $entries = $this->entryRepository->findAllWithLimit($limit);
        }

        $this->view->assign('entries', $entries);

        return $this->htmlResponse();
    }
}
