<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getFakePerson')->willReturn([
      'CPR' => '2601481203',
      'firstName' => 'Amanda M.',
      'lastName' => 'Carlsen',
      'gender' => 'female',
      'birthDate' => '1948-01-26',
      'address' => [
        'street' => 'gUISlfePhQåku woYxGMaYjtrYGtqÆKABMøJØxIc',
        'number' => '430',
        'floor' => '38',
        'door' => 'th',
        'postal_code' => '4200',
        'town_name' => 'Slagelse'
      ],
      'phoneNumber' => '58701222',
    ]);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Checks if getFakePerson returns array
   */
  public function test_getFakePerson_IsArray() {
    $person = $this->fakeInfo->getFakePerson();

    $this->assertIsArray($person, 'Is array');
  }

  /**
   * Checks if getFakePerson contains 7 values
   */
  public function test_getFakePerson_Contains7Values() {
    $person = $this->fakeInfo->getFakePerson();
    $exp = 7;

    $result = count($person);

    $this->assertEquals($exp, $result, 'Contains 7 values');
  }

  /**
   * Check if getFakePerson contains expected keys
   * 
   * @dataProvider provide_getFakePerson_ContainsExpectedKeys
   */
  public function test_getFakePerson_ContainsExpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFakePerson();

    $this->assertArrayHasKey($key, $fakePerson, 'Array contains key');
  }
  public static function provide_getFakePerson_ContainsExpectedKeys() {
    return [
      ['CPR'],
      ['firstName'],
      ['lastName'],
      ['gender'],
      ['birthDate'],
      ['address'],
      ['phoneNumber'],
    ];
  }

  /**
   * Check if getFakePerson contains unexpected keys
   * 
   * @dataProvider provide_getFakePerson_UnexpectedKeys
   */
  public function test_getFakePerson_ContainsUnexpectedKeys($key) {
    $fakePerson = $this->fakeInfo->getFakePerson();

    $this->assertArrayNotHasKey($key, $fakePerson, 'Array does not contain key');
  }
  public static function provide_getFakePerson_UnexpectedKeys() {
    return [
      ['cpr'],          // Uncapitalised real key
      ['middleName'],   // Potential but inexistent key
      [0],              // Zero
      [1],              // Integer
      [true],           // Boolean - true
      [false]           // Boolean - false
    ];
  }

  /**
   * Check if getFakePerson addresses contains expected keys
   * 
   * @dataProvider provide_Address_ExpectedKeys
   */
  public function test_Address_ContainsExpectedKeys($key) {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];

    $this->assertArrayHasKey($key, $address, "Address contains key: $key");
  }
  public static function provide_Address_ExpectedKeys() {
    return [
      ['street'],
      ['number'],
      ['floor'],
      ['door'],
      ['postal_code'],
      ['town_name']
    ];
  }

  /**
   * Check if getFakePerson addresses contains unexpected keys
   * 
   * @dataProvider provide_Address_UnexpectedNonKeys
   */
  public function test_Address_ContainsUnexpectedKeys($key) {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];

    $this->assertArrayNotHasKey($key, $address, "Address does not contain key: $key");
  }
  public static function provide_Address_UnexpectedNonKeys() {
    return [
      ['Street'],     // Actual key but capitalised
      ['postalCode'], // Actual key but in camel case
      ['townname'],   // Actual key without underscore
      ['country'],    // Potential key
    ];
  }
}
