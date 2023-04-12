<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class CprFullNameAndGenderTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getCprFullNameAndGender')->willReturn([
      'CPR' => '2601481203',
      'firstName' => 'Amanda M.',
      'lastName' => 'Carlsen',
      'gender' => 'female',
    ]);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if getCprFullNameAndGender returns an array
   */
  public function test_getCprFullNameAndGender_IsArray() {
    $data = $this->fakeInfo->getCprFullNameAndGender();

    $this->assertIsArray($data);
  }

  /**
   * Test if the returned array contains 4 values
   */
  public function test_getCprFullNameAndGender_Contains4Values() {
    $data = $this->fakeInfo->getCprFullNameAndGender();
    $exp = 4;

    $result = count($data);

    $this->assertEquals($exp, $result, 'Array contains 4 values');
  }

  /**
   * Test if the array contains the expected keys
   * 
   * @dataProvider provide_getCprFullNameAndGender_ExpectedKeys
   */
  public function test_getCprFullNameAndGender_ContainsExpectedKeys($key) {
    $data = $this->fakeInfo->getCprFullNameAndGender();

    $this->assertArrayHasKey($key, $data, 'Array contains key');
  }
  public static function provide_getCprFullNameAndGender_ExpectedKeys() {
    return [
      ['CPR'],
      ['firstName'],
      ['lastName'],
      ['gender'],
    ];
  }

  /**
   * Test if the array contains unexpected keys
   * 
   * @dataProvider provide_getCprFullNameAndGender_UnexpectedKeys
   */
  public function test_getCprFullNameAndGender_ContainsUnexpectedKeys($key) {
    $data = $this->fakeInfo->getCprFullNameAndGender();

    $this->assertArrayNotHasKey($key, $data, 'Array contains key');
  }
  public static function provide_getCprFullNameAndGender_UnexpectedKeys() {
    return [
      ['birthDate'],
      ['address'],
      ['phoneNumber'],
    ];
  }
}
