<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonGenderTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if gender is string
   * 
   * @dataProvider provide_Gender_IsString
   */
  public function test_Gender_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['gender' => $value]);
    $person = $this->fakeInfo->getFakePerson();;
    $gender = $person['gender'];

    $result = is_string($gender);

    $this->assertEquals($exp, $result, "$gender is string");
  }
  public static function provide_Gender_IsString() {
    return [
      ['male'],       // valid option
      ['female'],     // valid option
      [1, false],     // invalid: integer
      [true, false],  // invalid: bool
    ];
  }

  /**
   * Test if gender is male or female
   * 
   * @dataProvider provide_Gender_IsMoF
   */
  public function test_Gender_IsMoF($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['gender' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $gender = $person['gender'];

    $result = in_array($gender, ['male', 'female']);

    $this->assertEquals($exp, $result, "Gender is $gender");
  }

  public static function provide_Gender_IsMoF() {
    return [
      ['male'],         // valid option
      ['female'],       // valid option
      ['Male', false],  // invalid: capitalisation of valid option
      ['mail', false]   // invalid: mispelling
    ];
  }
}
