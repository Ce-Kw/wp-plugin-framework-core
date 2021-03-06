<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\MetaBox;
use CEKW\WpPluginFramework\Core\ContentType\PostType;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as M;

class TstPosttype extends PostType {
    function init()
    {

    }
}

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\PostType
 * @covers \CEKW\WpPluginFramework\Core\ContentType\LabelInfo
 * @covers \CEKW\WpPluginFramework\Core\AbstractExtenderBridge
 */
class PostTypeTest extends MockeryTestCase
{
    private PostType $postType;

    public function setUp(): void
    {
        $this->postType = new TstPosttype();
    }

    public function testSetGetIsPublic(): void
    {
        $testValue = true;
        $this->postType->setIsPublic($testValue);
        $this->assertSame($testValue, $this->postType->getIsPublic());
    }

    public function testSetKey(): void
    {
        $testValue = 'tst-posttype';
        $this->assertSame($testValue, $this->postType->getKey());
    }

    public function testGetArgs(): void
    {
        $this->assertArrayHasKey('public',$this->postType->getArgs());
        $this->assertArrayHasKey('supports',$this->postType->getArgs());
        $this->assertArrayHasKey('labels',$this->postType->getArgs());
    }

    public function testSetGetLabelInfo():void{
        $testLabelName = 'Labelname';
        $this->postType->setLabelName($testLabelName);

        $this->assertSame($testLabelName, $this->postType->getLabelName());
    }

    public function testUsingChainedExtends():void{
        $testIsPublic = true;
        $this->postType
            ->setLabelName('')
            ->setIsPublic(true);

        $this->assertSame($testIsPublic, $this->postType->getIsPublic());
    }

}