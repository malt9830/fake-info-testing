<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonPhoneTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if phone number is string
   * 
   * @dataProvider provide_Phone_IsString
   */
  public function test_Phone_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['phoneNumber' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $phone = $person['phoneNumber'];

    $result = is_string($phone);

    $this->assertEquals($exp, $result, 'Phone is string');
  }
  public static function provide_Phone_IsString() {
    return [
      ['20202020'],       // Valid data type
      [20202020, false],  // Integer
      [0, false],         // Zero
      [true, false],      // Bool
    ];
  }

  /**
   * Test if phone number is 8 digits
   * 
   * @dataProvider provide_Is8Characters
   */
  public function test_Phone_Is8Characters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['phoneNumber' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $phone = $person['phoneNumber'];
    $length = 8;

    $result = mb_strlen($phone) === $length;

    $this->assertEquals($exp, $result, 'Phone is 8 characters');
  }
  public static function provide_Is8Characters() {
    return [
      ['20202020'],           // Valid lower/upper boundary
      ['2020202', false],     // Invalid lower boundary: 7 chars
      ['202020202', false],   // Invalid upper boundary: 9 chars
    ];
  }

  /**
   * Test if phone number has correct prefix
   * 
   * @dataProvider provide_Phone_HasCorrectPrefix
   */
  public function test_Phone_HasCorrectPrefix($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['phoneNumber' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $phone = $person['phoneNumber'];
    $prefixes = [
      '2', '30', '31', '40', '41', '42', '50', '51', '52', '53', '60', '61', '71', '81', '91', '92', '93', '342',
      '344', '345', '346', '347', '348', '349', '356', '357', '359', '362', '365', '366', '389', '398', '431',
      '441', '462', '466', '468', '472', '474', '476', '478', '485', '486', '488', '489', '493', '494', '495',
      '496', '498', '499', '542', '543', '545', '551', '552', '556', '571', '572', '573', '574', '577', '579',
      '584', '586', '587', '589', '597', '598', '627', '629', '641', '649', '658', '662', '663', '664', '665',
      '667', '692', '693', '694', '697', '771', '772', '782', '783', '785', '786', '788', '789', '826', '827', '829'
    ];

    // Either the 2 first or 3 first digits of the phone number exists in array
    $result = in_array(substr($phone, 0, 1), $prefixes) || in_array(substr($phone, 0, 2), $prefixes) || in_array(substr($phone, 0, 3), $prefixes);

    $this->assertEquals($exp, $result, 'Phone has a correct prefix');
  }
  public static function provide_Phone_HasCorrectPrefix() {
    return [
      ['20202020'],         // 1 digit prefix
      ['30202020'],         // 2 digit prefix
      ['43102020'],         // 3 digit prefix
      ['12341234', false],  // Invalid prefix
      ['00000000', false],  // Zeroes
    ];
  }
}
