<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;

/**
 * @category   Laminas
 * @package    Laminas_Barcode
 * @subpackage UnitTests
 * @group      Laminas_Barcode
 */
class Ean2Test extends TestCommon
{
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Ean2($options);
    }

    public function testType()
    {
        $this->assertSame('ean2', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(9, $this->object->getChecksum('43'));
    }

    public function testSetText()
    {
        $this->object->setText('43');
        $this->assertSame('43', $this->object->getRawText());
        $this->assertSame('43', $this->object->getText());
        $this->assertSame('43', $this->object->getTextToDisplay());
    }

    public function testSetTextWithout2Characters()
    {
        $this->object->setText('5');
        $this->assertSame('5', $this->object->getRawText());
        $this->assertSame('05', $this->object->getText());
        $this->assertSame('05', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('43');
        $this->object->setWithChecksum(false);
        $this->assertSame('43', $this->object->getRawText());
        $this->assertSame('43', $this->object->getText());
        $this->assertSame('43', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 43 ');
        $this->assertSame('43', $this->object->getRawText());
        $this->assertSame('43', $this->object->getText());
        $this->assertSame('43', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('43');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('43', $this->object->getRawText());
        $this->assertSame('43', $this->object->getText());
        $this->assertSame('43', $this->object->getTextToDisplay());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('43');
        $this->assertTrue($this->object->checkParams());
    }


    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('43');
        $this->assertEquals(41, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(21, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('43');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Ean2_43_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('43');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean2_43_border_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('43');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean2_43_oriented_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('43');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean2_43_border_oriented_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('43');
        $this->assertEquals(62, $this->object->getHeight(true));
    }
}
