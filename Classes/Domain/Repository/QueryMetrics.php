<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Domain\Repository;

/**
 * Value object capturing performance metrics for a single query iteration pass.
 *
 * Tracks iteration count, execution time in microseconds, and peak memory delta.
 * Used by QueryCachingValidator to assert that repeated result traversals
 * do not cause additional database round-trips.
 */
final class QueryMetrics
{
    public function __construct(
        private readonly int $iterationCount,
        private readonly float $executionTimeUs,
        private readonly int $memoryDeltaBytes,
    ) {}

    public function getIterationCount(): int
    {
        return $this->iterationCount;
    }

    public function getExecutionTimeUs(): float
    {
        return $this->executionTimeUs;
    }

    public function getMemoryDeltaBytes(): int
    {
        return $this->memoryDeltaBytes;
    }

    public function isWithinTimeBudget(float $maxUs): bool
    {
        return $this->executionTimeUs <= $maxUs;
    }

    public function isWithinMemoryBudget(int $maxBytes): bool
    {
        return $this->memoryDeltaBytes <= $maxBytes;
    }
}
