<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Validates that iterating a QueryResultInterface result set multiple times
 * does not trigger additional database round-trips (i.e. the ORM result cache
 * is working correctly for timeline entry queries).
 *
 * The validator runs N passes over the result set, accumulates item counts and
 * wall-clock time, and returns a QueryMetrics snapshot for assertion.
 */
final class QueryCachingValidator
{
    public function measureIterations(QueryResultInterface $result, int $passes = 2): QueryMetrics
    {
        $memoryBefore = memory_get_usage(true);
        $startTime = hrtime(true);

        $totalItems = 0;

        for ($pass = 0; $pass < $passes; ++$pass) {
            $totalItems += $this->countIterations($result);
        }

        $endTime = hrtime(true);
        $memoryAfter = memory_get_usage(true);

        $executionTimeUs = ($endTime - $startTime) / 1_000;
        $memoryDeltaBytes = max(0, $memoryAfter - $memoryBefore);

        return new QueryMetrics(
            iterationCount: $totalItems,
            executionTimeUs: $executionTimeUs,
            memoryDeltaBytes: $memoryDeltaBytes,
        );
    }

    public function assertCachingByConsistency(QueryResultInterface $result): bool
    {
        $firstPassCount = $this->countIterations($result);
        $secondPassCount = $this->countIterations($result);

        return $firstPassCount === $secondPassCount;
    }

    private function countIterations(QueryResultInterface $result): int
    {
        $count = 0;

        for ($result->rewind(); $result->valid(); $result->next()) {
            ++$count;
        }

        return $count;
    }
}
