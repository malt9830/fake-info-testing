<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo->method('getAddress')->willReturn([
      'address' => [
        'street' => 'gUISlfePhQåku woYxGMaYjtrYGtqÆKABMøJØxIc',
        'number' => '430',
        'floor' => '38',
        'door' => 'th',
        'postal_code' => '4200',
        'town_name' => 'Slagelse'
      ]
    ]);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if getAddress returns an array
   */
  public function test_getAddress_IsArray() {
    $data = $this->fakeInfo->getAddress();

    $this->assertIsArray($data, 'Is array');
  }

  /**
   * Test if getAddress returns an array with an array
   */
  public function test_getAddress_IsArrayInArray() {
    $data = $this->fakeInfo->getAddress();

    $this->assertIsArray($data['address'], 'Array contains array');
  }

  /**
   * Test if the returned array contains 6 values
   */
  public function test_getAddress_Contains6Values() {
    $data = $this->fakeInfo->getAddress();
    $exp = 6;

    $result = count($data['address']);

    $this->assertEquals($exp, $result, 'Array contains 6 values');
  }

  /**
   * Test if the array contains the expected keys
   * 
   * @dataProvider provide_getAddress_ExpectedKeys
   */
  public function test_getAddress_ContainsExpectedKeys($key) {
    $data = $this->fakeInfo->getAddress();

    $this->assertArrayHasKey($key, $data['address'], 'Array contains key');
  }
  public static function provide_getAddress_ExpectedKeys() {
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
   * Test if the array contains unexpected keys
   * 
   * @dataProvider provide_getAddress_UnexpectedKeys
   */
  public function test_getAddress_ContainsUnexpectedKeys($key) {
    $data = $this->fakeInfo->getAddress();

    $this->assertArrayNotHasKey($key, $data['address'], 'Array contains key');
  }
  public static function provide_getAddress_UnexpectedKeys() {
    return [
      ['birthDate'],
      ['address'],
      ['phoneNumber'],
    ];
  }
}
