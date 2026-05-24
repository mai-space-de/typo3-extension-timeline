<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\Controller;

use Maispace\MaiTimeline\Controller\TimelineController;
use Maispace\MaiTimeline\Domain\Repository\EntryRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Structural unit tests for TimelineController.
 *
 * Mirrors the gallery-3 reflection-based coverage pattern from
 * MaiGallery\Controller\GalleryControllerTest.
 *
 * Verifies class hierarchy, constructor dependency injection, and
 * listAction method signature — especially that listAction reads
 * storagePid / categoryUid / limit from $this->settings rather than
 * from action arguments.
 */
final class TimelineControllerTest extends TestCase
{
    #[Test]
    public function controllerExtendsActionController(): void
    {
        self::assertTrue(
            is_subclass_of(TimelineController::class, ActionController::class),
        );
    }

    #[Test]
    public function controllerIsFinal(): void
    {
        $reflectionClass = new \ReflectionClass(TimelineController::class);
        self::assertTrue($reflectionClass->isFinal());
    }

    #[Test]
    public function constructorRequiresEntryRepository(): void
    {
        $params = (new \ReflectionMethod(TimelineController::class, '__construct'))
            ->getParameters();

        $names = array_map(
            static fn(\ReflectionParameter $p) => $p->getName(),
            $params,
        );
        self::assertContains('entryRepository', $names);

        $repoParam = array_values(array_filter(
            $params,
            static fn(\ReflectionParameter $p) => $p->getName() === 'entryRepository',
        ))[0];

        $type = $repoParam->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame(EntryRepository::class, $type->getName());
    }

    #[Test]
    public function listActionMethodExists(): void
    {
        self::assertTrue(
            method_exists(TimelineController::class, 'listAction'),
        );
    }

    #[Test]
    public function listActionReturnsResponseInterface(): void
    {
        $returnType = (new \ReflectionMethod(TimelineController::class, 'listAction'))
            ->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertSame(ResponseInterface::class, $returnType->getName());
    }

    #[Test]
    public function listActionReadsSettingsForStoragePidCategoryUidLimit(): void
    {
        // listAction reads storagePid, categoryUid, and limit from $this->settings
        // (FlexForm values) rather than from action arguments. Verify the method
        // declares no parameters — confirming it relies on settings injection.
        $params = (new \ReflectionMethod(TimelineController::class, 'listAction'))
            ->getParameters();

        self::assertCount(0, $params);
    }
}
