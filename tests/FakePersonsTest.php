<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonsTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
    // $this->fakeInfo = new FakeInfo;
    $this->fakeInfo->method('getFakePersons')->willReturnCallback(function (int $amount = 2) {
      if ($amount < 2) {
        $amount = 2;
      }
      if ($amount > 200) {
        $amount = 200;
      }
      $bulkInfo = array();

      for ($index = 0; $index < $amount; $index++) {
        $fakeInfo = [
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
        ];

        array_push($bulkInfo, $fakeInfo);
      }

      return $bulkInfo;
    });
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if getFakePersons returns array
   */
  public function test_getFakePersons_IsArray() {
    $result = $this->fakeInfo->getFakePersons(2);

    $this->assertIsArray($result, 'Is array');
  }

  /**
   * Test if getFakePersons returns expected amount
   * 
   * @dataProvider provideFakePersonsAmount
   */
  public function test_getFakePersons_ReturnsCorrectAmount(int $amount, int $exp) {
    $fakePersons = $this->fakeInfo->getFakePersons($amount);

    $result = count($fakePersons);

    $this->assertEquals($exp, $result, 'Returns expected amount');
  }
  public static function provideFakePersonsAmount() {
    return [
      [2, 2],      // Valid lower boundaries
      [3, 3],      // 3 value approach
      [100, 100],  // Middle value
      [99, 99],    // 3 value approach
      [200, 200],  // Valid upper boundaries
      [1, 2],      // Invalid lower boundary - rounds up
      [201, 200],  // Invalid upper boundary - rounds down
      [0, 2],      // Zero - rounds up
      [-1, 2],     // Negative - rounds up
      // [1.5],   // Non-integer: float - lower | throws TypeError as expected
    ];
  }

  /**
   * Test if getFakePerson throws type error
   * 
   * @dataProvider provideFakePersonsExceptions
   */
  public function test_getFakePersonsExpectsInteger($amount) {
    $this->expectException(TypeError::class);

    $this->fakeInfo->getFakePersons($amount);
  }
  public static function provideFakePersonsExceptions() {
    return [
      ['A'],      // Non-integer: string
      [[1]],      // Non-integer: array
      // [200.5], // Non-integer: float - upper | does NOT throw TypeError
    ];
  }
}
