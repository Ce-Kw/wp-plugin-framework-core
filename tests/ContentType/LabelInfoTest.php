<?php

namespace CEKW\WpPluginFramework\Tests\ContentType;

use CEKW\WpPluginFramework\Core\ContentType\LabelInfo;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \CEKW\WpPluginFramework\Core\ContentType\LabelInfo
 */
class LabelInfoTest extends MockeryTestCase
{
    private LabelInfo $labelInfo;

    public function setUp(): void
    {
        $this->labelInfo = new LabelInfo();
    }

    public function testSetGetLabelName(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelName($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelName());
    }

    public function testSetGetLabelSingularName(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelSingularName($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelSingularName());
    }

    public function testSetGetLabelAddNew(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelAddNew($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelAddNew());
    }

    public function testSetGetLabelAddNewItem(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelAddNewItem($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelAddNewItem());
    }

    public function testSetGetLabelEditItem(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelEditItem($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelEditItem());
    }

    public function testSetGetLabelNewItem(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelNewItem($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelNewItem());
    }

    public function testSetGetLabelViewItem(): void
    {
        $testValue = 'ok';
        $this->labelInfo->setLabelViewItem($testValue);
        $this->assertSame($testValue, $this->labelInfo->getLabelViewItem());
    }

    public function testGetLabelArgsShouldNotReturnEmptyValues(){
        $testName = 'ok';
        $this->assertSame($this->labelInfo->getLabelArgs(),[]);
        $this->labelInfo->setLabelName($testName);
        $this->assertSame($this->labelInfo->getLabelArgs(),[
            'name'=>$testName
        ]);
    }
}