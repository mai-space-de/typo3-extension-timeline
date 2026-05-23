<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\ContentElements;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Structural smoke tests for the maispace_timeline content-element rendering.
 *
 * These tests verify that the template and partial files required for the
 * maispace_timeline CType exist, are non-empty, and carry the expected Fluid
 * HTML-namespace declaration — exactly the same integrity contract that
 * EXT:mai_theme enforces for its own content elements.
 *
 * They do NOT test rendered output; they catch file-missing or file-empty
 * regressions early in the CI pipeline without needing a full TYPO3 bootstrap.
 */
final class TimelineTemplateTest extends TestCase
{
    private const TEMPLATE_DIR = __DIR__ . '/../../../Resources/Private/Templates/ContentElements';
    private const PARTIAL_DIR = __DIR__ . '/../../../Resources/Private/Partials';

    // ──────────────────────────────────────────────────────────────────────────
    // Template file tests
    // ──────────────────────────────────────────────────────────────────────────

    #[Test]
    public function timelineTemplateFileExists(): void
    {
        self::assertFileExists(
            self::TEMPLATE_DIR . '/Timeline.html',
            'ContentElements/Timeline.html is missing. '
            . 'The maispace_timeline CType requires this template to render.',
        );
    }

    #[Test]
    public function timelineTemplateFileIsNotEmpty(): void
    {
        $path = self::TEMPLATE_DIR . '/Timeline.html';
        if (!file_exists($path)) {
            self::markTestSkipped('Timeline.html does not exist — covered by timelineTemplateFileExists.');
        }

        self::assertNotEmpty(
            trim((string)file_get_contents($path)),
            'ContentElements/Timeline.html must not be an empty file.',
        );
    }

    #[Test]
    public function timelineTemplateHasFluidNamespaceDeclaration(): void
    {
        $path = self::TEMPLATE_DIR . '/Timeline.html';
        if (!file_exists($path)) {
            self::markTestSkipped('Timeline.html does not exist — covered by timelineTemplateFileExists.');
        }

        self::assertStringContainsString(
            'data-namespace-typo3-fluid="true"',
            (string)file_get_contents($path),
            'ContentElements/Timeline.html must include the Fluid HTML-namespace attribute '
            . '`data-namespace-typo3-fluid="true"` for correct TYPO3 Fluid rendering.',
        );
    }

    #[Test]
    public function timelineTemplatePassesProcessedItemsToPartial(): void
    {
        $path = self::TEMPLATE_DIR . '/Timeline.html';
        if (!file_exists($path)) {
            self::markTestSkipped('Timeline.html does not exist — covered by timelineTemplateFileExists.');
        }

        $content = (string)file_get_contents($path);

        // The template must pass {items} (populated by DatabaseQueryProcessor)
        // to the Organism/Timeline partial — NOT the raw DB field value.
        self::assertStringContainsString(
            'items: items',
            $content,
            'ContentElements/Timeline.html must pass the DataProcessor variable {items} '
            . 'to the partial, not data.tx_maitimeline_items (which is a raw DB count).',
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Partial file tests
    // ──────────────────────────────────────────────────────────────────────────

    #[Test]
    public function timelineOrganismPartialExists(): void
    {
        self::assertFileExists(
            self::PARTIAL_DIR . '/Organism/Timeline.html',
            'Partials/Organism/Timeline.html is missing. '
            . 'The Timeline.html template renders via this partial.',
        );
    }

    #[Test]
    public function timelineItemPartialExists(): void
    {
        self::assertFileExists(
            self::PARTIAL_DIR . '/Items/TimelineItem.html',
            'Partials/Items/TimelineItem.html is missing. '
            . 'The Organism/Timeline partial renders individual items via this partial.',
        );
    }

    #[Test]
    public function timelineOrganismPartialHasFluidNamespaceDeclaration(): void
    {
        $path = self::PARTIAL_DIR . '/Organism/Timeline.html';
        if (!file_exists($path)) {
            self::markTestSkipped('Organism/Timeline.html does not exist.');
        }

        self::assertStringContainsString(
            'data-namespace-typo3-fluid="true"',
            (string)file_get_contents($path),
            'Partials/Organism/Timeline.html must include the Fluid HTML-namespace attribute.',
        );
    }

    #[Test]
    public function timelineItemPartialHasFluidNamespaceDeclaration(): void
    {
        $path = self::PARTIAL_DIR . '/Items/TimelineItem.html';
        if (!file_exists($path)) {
            self::markTestSkipped('Items/TimelineItem.html does not exist.');
        }

        self::assertStringContainsString(
            'data-namespace-typo3-fluid="true"',
            (string)file_get_contents($path),
            'Partials/Items/TimelineItem.html must include the Fluid HTML-namespace attribute.',
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TypoScript configuration tests
    // ──────────────────────────────────────────────────────────────────────────

    #[Test]
    public function maispaceTimelineTypoScriptFileExists(): void
    {
        self::assertFileExists(
            __DIR__ . '/../../../Configuration/TypoScript/ContentElements/MaispaceTimeline.typoscript',
            'Configuration/TypoScript/ContentElements/MaispaceTimeline.typoscript is missing. '
            . 'This file provides the tt_content.maispace_timeline rendering configuration.',
        );
    }

    #[Test]
    public function maispaceTimelineTypoScriptConfiguresNewTable(): void
    {
        $path = __DIR__ . '/../../../Configuration/TypoScript/ContentElements/MaispaceTimeline.typoscript';
        if (!file_exists($path)) {
            self::markTestSkipped('MaispaceTimeline.typoscript does not exist.');
        }

        $content = (string)file_get_contents($path);

        // The TypoScript must query the NEW tx_maitimeline_item table,
        // NOT the legacy tx_maitheme_timeline_item from mai_theme.
        self::assertStringContainsString(
            'tx_maitimeline_item',
            $content,
            'MaispaceTimeline.typoscript must configure DatabaseQueryProcessor '
            . 'to load items from tx_maitimeline_item (the mai_timeline table), '
            . 'not the legacy tx_maitheme_timeline_item table from mai_theme.',
        );

        self::assertStringNotContainsString(
            'tx_maitheme_timeline_item',
            $content,
            'MaispaceTimeline.typoscript must NOT reference the legacy '
            . 'tx_maitheme_timeline_item table from mai_theme.',
        );
    }
}
