<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FullNameGenderAndBirthDateTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getFullNameGenderAndBirthDate')->willReturn([
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
   * Test if getFullNameGenderAndBirthDate returns an array
   */
  public function test_getFullNameGenderAndBirthDate_IsArray() {
    $data = $this->fakeInfo->getFullNameGenderAndBirthDate();

    $this->assertIsArray($data);
  }

  /**
   * Test if the returned array contains 4 values
   */
  public function test_getFullNameGenderAndBirthDate_Contains3Values() {
    $data = $this->fakeInfo->getFullNameGenderAndBirthDate();
    $exp = 4;

    $result = count($data);

    $this->assertEquals($exp, $result, 'Array contains 4 values');
  }

  /**
   * Test if the array contains the expected keys
   * 
   * @dataProvider provide_getFullNameGenderAndBirthDate_ExpectedKeys
   */
  public function test_getFullNameGenderAndBirthDate_ContainsExpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFullNameGenderAndBirthDate();

    $this->assertArrayHasKey($key, $fakePerson, 'Array contains key');
  }
  public static function provide_getFullNameGenderAndBirthDate_ExpectedKeys() {
    return [
      ['firstName'],
      ['lastName'],
      ['gender'],
      ['birthDate'],
    ];
  }

  /**
   * Test if the array contains unexpected keys
   * @dataProvider provide_getFullNameGenderAndBirthDate_UnexpectedKeys
   */
  public function test_getFullNameGenderAndBirthDate_ContainsUnexpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFullNameGenderAndBirthDate();

    $this->assertArrayNotHasKey($key, $fakePerson, 'Array contains key');
  }
  public static function provide_getFullNameGenderAndBirthDate_UnexpectedKeys() {
    return [
      ['CPR'],
      ['address'],
      ['phoneNumber'],
    ];
  }
}
