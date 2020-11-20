<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\Taxonomy;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TstTaxonomy extends Taxonomy {
    function init()
    {

    }
}

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\Taxonomy
 */
class TaxonomyTest extends MockeryTestCase
{
    private Taxonomy $taxonomy;

    public function setUp(): void
    {
        $this->taxonomy = new TstTaxonomy();
    }

    public function testSetGetIsPublic(): void
    {
        $testValue = true;
        $this->taxonomy->setIsPublic($testValue);
        $this->assertSame($testValue, $this->taxonomy->getIsPublic());
    }
}