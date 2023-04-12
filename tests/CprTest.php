<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class CprTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if CPR is string
   * 
   * @dataProvider provide_Cpr_IsString
   */
  public function test_Cpr_IsString($value, $exp) {
    $this->fakeInfo->method('getCpr')->willReturn($value);
    $cpr = $this->fakeInfo->getCpr();

    $result = is_string($cpr);

    $this->assertEquals($exp, $result, "CPR is $cpr");
  }
  public static function provide_Cpr_IsString() {
    return [
      ['0000000000', true],    // Valid lower/upper boundary
      ['000000000', true],     // Invalid lower boundary - 9 characters
      ['00000000000', true],   // Invalid upper boundary - 11 characters
      ['', true],              // Empty string
    ];
  }

  /**
   * Test if CPR is 10 characters
   * 
   * @dataProvider provide_Cpr_Is10Characters
   */
  public function test_Cpr_Is10Characters($value, $exp) {
    $this->fakeInfo->method('getCpr')->willReturn($value);
    $cpr = $this->fakeInfo->getCpr();

    $expLength = 10;

    $result = mb_strlen($cpr) === $expLength;

    $this->assertEquals($exp, $result, "CPR is $cpr");
  }
  public static function provide_Cpr_Is10Characters() {
    return [
      ['0000000000', true],     // Valid lower/upper boundary
      ['000000000', false],     // Invalid lower boundary - 9 characters
      ['00000000000', false],   // Invalid upper boundary - 11 characters
      ['', false],              // Empty string
    ];
  }

  /**
   * Test if valid dates will pass
   * 
   * @dataProvider provide_Cpr_ContainsValidDate
   */
  public function test_Cpr_ContainsValidDate($value) {
    $this->fakeInfo->method('getCpr')->willReturn($value);
    $cpr = $this->fakeInfo->getCpr();
    $date = substr($cpr, 0, 6);
    $dateArr = str_split($date, 2);
    $dateObj = DateTime::createFromFormat('d/m/y', "01/01/$dateArr[2]");

    $result = checkdate($dateArr[1], $dateArr[0], $dateObj->format('Y'));

    $this->assertTrue($result, "Contains real date: $date");
  }
  public static function provide_Cpr_ContainsValidDate() {
    return [
      ['0101000000'],   // Valid lower boundary
      ['0101010000'],   // Valid lower boundary + 1 year
      ['0201000000'],   // Valid lower boundary + 1 day
      ['0102000000'],   // Valid lower boundary + 1 month
      ['3112999999'],   // Valid upper boundary
      ['3112989999'],   // Valid upper boundary - 1 year
      ['3012999999'],   // Valid upper boundary - 1 day
      ['3011999999'],   // Valid upper boundary - 1 month
      ['0101700000'],   // Unix epoch
      ['2902160000'],   // 29th Feb on a leap year
    ];
  }

  /**
   * Test if invalid dates will fail
   * 
   * @dataProvider provide_Cpr_InvalidDates
   */
  public function test_Cpr_ContainsInvalidDate($value) {
    $this->fakeInfo->method('getCpr')->willReturn($value);
    $cpr = $this->fakeInfo->getCpr();
    $date = substr($cpr, 0, 6);
    $dateArr = str_split($date, 2);
    $dateObj = DateTime::createFromFormat('d/m/y', "01/01/$dateArr[2]");

    $result = checkdate($dateArr[1], $dateArr[0], $dateObj->format('Y'));

    $this->assertNotTrue($result, "Contains fake dake: $date");
  }
  public static function provide_Cpr_InvalidDates() {
    return [
      ['0000000000'],  // Invalid lower boundary
      ['0000010000'],  // Invalid lower boundary + 1 year
      ['0100000000'],  // Invalid lower boundary + 1 day
      ['0001000000'],  // Invalid lower boundary + 1 month
      ['3212990000'],  // Invalid upper boundary + 1 day
      ['3113990000'],  // Invalid upper boundary + 1 month
      ['2902170000'],  // 29th Feb not on a leap year
    ];
  }
}
