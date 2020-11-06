<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\PostType
 */
class PostTypeTest extends MockeryTestCase
{
    private PostType $postType;

    public function setUp(): void
    {
        $this->postType = new PostType();
    }

    public function testSetGetIsPublic(): void
    {
        $testValue = true;
        $this->postType->setIsPublic($testValue);
        $this->assertSame($testValue, $this->postType->getIsPublic());
    }

    public function testSetKey(): void
    {
        $testValue = '';
        $this->assertSame($testValue, $this->postType->getKey());
    }

}