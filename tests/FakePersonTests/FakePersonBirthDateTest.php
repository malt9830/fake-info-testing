<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonBirthDateTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if birth date is string
   * 
   * @dataProvider provide_BirthDate_IsString
   */
  public function test_BirthDate_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['birthDate' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $birthDate = $person['birthDate'];

    $result = is_string($birthDate);

    $this->assertEquals($exp, $result, "$birthDate is string");
  }
  public static function provide_BirthDate_IsString() {
    return [
      ['2000-01-01'],    // Valid date
      [''],              // Valid but empty string
      [20000101, false], // Integer
      [20000101, false], // Bool
    ];
  }

  /**
   * Test if birth date is right format
   * 
   * @dataProvider provide_BirthDate_IsValidFormat
   */
  public function test_BirthDate_IsValidFormat($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['birthDate' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $birthDate = $person['birthDate'];
    $dateObj = DateTime::createFromFormat('Y-m-d', $birthDate);

    $result = is_object($dateObj);

    $this->assertEquals($exp, $result, "$birthDate is valid format");
  }
  public static function provide_BirthDate_IsValidFormat() {
    return [
      ['2000-01-30'],           // Valid format:   Y-m-d
      ['30-01-2000', false],    // Invalid format: m-d-Y
      ['01-30-2000', false],    // Invalid format: d-m-Y
      ['30-2000-01', false],    // Invalid format: d-Y-m
      ['01-2000-30', false],    // Invalid format: m-Y-d
      // ['2000-30-01', false],    // Invalid format: Y-d-m | weird round up
    ];
  }

  /**
   * Test if birth date is valid
   * 
   * @dataProvider provide_BirthDate_IsValid
   */
  public function test_BirthDate_IsValid($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['birthDate' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $birthDate = $person['birthDate'];
    $dobArr = explode("-", $birthDate);

    $result = checkdate($dobArr[1], $dobArr[2], $dobArr[0]);

    $this->assertEquals($exp, $result, "$birthDate is valid");
  }
  public static function provide_BirthDate_IsValid() {
    return [
      ['1000-01-01'],         // Valid lower boundary
      ['1001-01-01'],         // Valid lower boundary + 1 year
      ['1000-02-01'],         // Valid lower boundary + 1 month
      ['1000-01-02'],         // Valid lower boundary + 1 day
      ['9999-12-31'],         // Valid upper boundary
      ['9998-12-31'],         // Valid upper boundary - 1 year
      ['9999-11-30'],         // Valid upper boundary - 1 month
      ['9999-12-30'],         // Valid upper boundary - 1 day
      ['1970-01-01'],         // Unix epoch
      ['1970-01-02'],         // Unix epoch - day after
      ['1969-12-31'],         // Unix epoch - day prior
      ['2016-02-28'],         // 28th Feb on a leap year
      ['2016-02-29'],         // 29th Feb on a leap year
      ['2017-02-28'],         // 28th Feb on a non-leap year
      ['2017-02-29', false],  // 29th Feb on a non-leap year
      ['2000-12-32', false],  // 32nd day in 31-day month
      ['2000-11-31', false],  // 31st day in 30-day month
      ['2000-13-01', false],  // 13th month
      ['2000-00-01', false],  // 0th month
      ['2000-01-00', false],  // 0th day
    ];
  }
}
