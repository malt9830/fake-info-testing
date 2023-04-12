<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FullNameAndGenderTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getFullNameAndGender')->willReturn([
      'firstName' => 'Amanda M.',
      'lastName' => 'Carlsen',
      'gender' => 'female',
    ]);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if getFullNameAndGender returns an array
   */
  public function test_getFullNameAndGender_IsArray() {
    $data = $this->fakeInfo->getFullNameAndGender();

    $this->assertIsArray($data);
  }

  /**
   * Test if the returned array contains 3 values
   */
  public function test_getFullNameAndGender_Contains3Values() {
    $data = $this->fakeInfo->getFullNameAndGender();
    $exp = 3;

    $result = count($data);

    $this->assertEquals($exp, $result, 'Array contains 3 values');
  }

  /**
   * Test if the array contains the expected keys
   * 
   * @dataProvider provide_getFullNameAndGender_ExpectedKeys
   */
  public function test_getFullNameAndGender_ContainsExpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFullNameAndGender();

    $this->assertArrayHasKey($key, $fakePerson, 'Array contains key');
  }
  public static function provide_getFullNameAndGender_ExpectedKeys() {
    return [
      ['firstName'],
      ['lastName'],
      ['gender'],
    ];
  }

  /**
   * Test if the array contains unexpected keys
   * 
   * @dataProvider provide_getFullNameAndGender_UnexpectedKeys
   */
  public function test_getFullNameAndGender_ContainsUnexpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFullNameAndGender();

    $this->assertArrayNotHasKey($key, $fakePerson, 'Array contains key');
  }
  public static function provide_getFullNameAndGender_UnexpectedKeys() {
    return [
      ['CPR'],
      ['birthDate'],
      ['address'],
      ['phoneNumber'],
    ];
  }
}
