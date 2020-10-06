<?php

declare(strict_types=1);

namespace unitTest;

require "../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use DAL\DALPreset;
use functional\preset;
use functional\presetMethods;


class unitTest extends TestCase
{
    public function testDataRetrieve(): void
    {
        //Arrange
        $mockPresetDal = $this->createMock(DALPreset::class);
        $mockPresetDal->method('createPresetList')
            ->willReturn($presetList = ['test']);

        $presetMethods = new presetMethods($mockPresetDal);

        //Act
        $presets = $presetMethods->getPresetList('1');

        //Assert
        $this->assertNotEmpty($presets);
        $this->assertFalse(isset($presets["getPresetList"]));
    }

    public function testCheckInput(): void
    {
        //Arrange
        $MockPresetDAL = $this->createMock(DALPreset::class);

        //Act
        $preset = new preset("unitTest", "1", "1", $MockPresetDAL);

        //Assert
        $this->assertEquals("unitTest", $preset->name);
    }

    public function testEmptyInput(): void
    {
        //Arrange
        $MockPresetDAL = $this->createMock(DALPreset::class);
        $IPreset = new preset("", "1", "1", $MockPresetDAL);

        //Act
        $result = $IPreset->addPresetInfo();

        //Assert
        $this->assertNotEmpty($result["addPresetInfo"]);
    }

    public function testNoEditID(): void
    {
        //Arrange
        $MockPresetDAL = $this->createMock(DALPreset::class);
        $IPresetMethods = new presetMethods($MockPresetDAL);

        //Act
        $result = $IPresetMethods->editPresetInfo("", null, "0");

        //Assert
        $this->assertNotEmpty($result["editPresetInfo"]);
    }
}