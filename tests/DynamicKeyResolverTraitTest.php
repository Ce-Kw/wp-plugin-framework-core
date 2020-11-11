<?php

namespace CEKW\WpPluginFramework\Tests;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait
 */
class DynamicKeyResolverTraitTest extends MockeryTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testResolveKeyFromClassName($suffix, $className, $expectedResult, $delimiter)
    {
        $traitClassMock = $this->getMockForTrait(DynamicKeyResolverTrait::class, [], $className);
        $result = $traitClassMock->resolveKeyFromClassName($suffix, $delimiter);

        $this->assertEquals($expectedResult, $result);
    }

    public function dataProvider()
    {
        return [
            ['PostType', 'BookPostType', 'book', '-'],
            ['PostType', 'ContactPersonPostType', 'contact-person', '-'],
            ['PostType', 'MyPostTypeWithSuperLongKeyPostType', 'my-post-type-with-super-long-key', '-'],
            ['Taxonomy', 'GenreTaxonomy', 'genre', '-'],
            ['Taxonomy', 'OldLocationTaxonomy', 'old-location', '-'],
            ['Taxonomy', 'MyCustomTaxonomyWhichIsKindaLongTaxonomy', 'my-custom-taxonomy-which-is-kinda-long', '-'],
            ['Event', 'BookImportEvent', 'book_import', '_'],
            ['Event', 'MyEventThatGetsCalledAfterAnotherEvent', 'my_event_that_gets_called_after_another', '_'],
        ];
    }
}