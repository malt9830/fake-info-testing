<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class CprFullNameGenderAndBirthDateTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getCprFullNameGenderAndBirthDate')->willReturn([
      'CPR' => '2601481203',
      'firstName' => 'Amanda M.',
      'lastName' => 'Carlsen',
      'gender' => 'female',
      'birthDate' => '1948-01-26',
    ]);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if getCprFullNameGenderAndBirthDate returns an array
   */
  public function test_getCprFullNameGenderAndBirthDate_IsArray() {
    $data = $this->fakeInfo->getCprFullNameGenderAndBirthDate();

    $this->assertIsArray($data);
  }

  /**
   * Test if the returned array contains 5 values
   */
  public function test_getCprFullNameGenderAndBirthDate_Contains5Values() {
    $data = $this->fakeInfo->getCprFullNameGenderAndBirthDate();
    $exp = 5;

    $result = count($data);

    $this->assertEquals($exp, $result, 'Array contains 5 values');
  }

  /**
   * Test if the array contains the expected keys
   * 
   * @dataProvider provide_getCprFullNameGenderAndBirthDate_ExpectedKeys
   */
  public function test_getCprFullNameGenderAndBirthDate_ContainsExpectedKeys($key) {
    $data = $this->fakeInfo->getCprFullNameGenderAndBirthDate();

    $this->assertArrayHasKey($key, $data, 'Array contains key');
  }
  public static function provide_getCprFullNameGenderAndBirthDate_ExpectedKeys() {
    return [
      ['CPR'],
      ['firstName'],
      ['lastName'],
      ['gender'],
      ['birthDate'],
    ];
  }

  /**
   * Test if the array contains unexpected keys
   * 
   * @dataProvider provide_getCprFullNameGenderAndBirthDate_UnexpectedKeys
   */
  public function test_getCprFullNameGenderAndBirthDate_ContainsUnexpectedKeys($key) {
    $data = $this->fakeInfo->getCprFullNameGenderAndBirthDate();

    $this->assertArrayNotHasKey($key, $data, 'Array contains key');
  }
  public static function provide_getCprFullNameGenderAndBirthDate_UnexpectedKeys() {
    return [
      ['address'],
      ['phoneNumber'],
    ];
  }
}
