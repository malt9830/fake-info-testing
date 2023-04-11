<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakeInfoTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    // $this->fakeInfo = $this->createStub(FakeInfo::class);
    $this->fakeInfo = new FakeInfo;
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  // Tests for CPR
  public function test_Cpr_IsString() {
    $person = $this->fakeInfo->getFakePerson();
    $cpr = $person['CPR'];

    $this->assertIsString($cpr, 'CPR is string');
  }

  public function test_Cpr_Is10Characters() {
    $person = $this->fakeInfo->getFakePerson();
    $cpr = $person['CPR'];
    $exp = 10;

    $length = mb_strlen($cpr);

    $this->assertEquals($exp, $length, 'CPR is 10 characters');
  }

  public function test_Cpr_ContainsBirthDate() {
    $person = $this->fakeInfo->getFakePerson();
    $cpr = $person['CPR'];
    $date = DateTime::createFromFormat('dmy', substr($cpr, 0, 6));
    // $dateString = $date->format('d-m-Y');
    // $dobArr = str_split($dateString, 2);
    // $dobArr = explode('-', $dateString);

    // $date = date_parse_from_format('dmy', $dobString);

    // $result = checkdate($dobArr[1], $dobArr[0], $dobArr[2]);
    $result = checkdate($date->format('m'), $date->format('d'), $date->format('Y'));


    $this->assertTrue($result, 'CPR contains a valid date');
  }

  // Tests for firstName and lastName
  public function test_FirstName_IsString() {
    $person = $this->fakeInfo->getFakePerson();;
    $firstName = $person['firstName'];

    $this->assertIsString($firstName, 'First name is string');
  }

  public function test_LastName_IsString() {
    $person = $this->fakeInfo->getFakePerson();;
    $lastName = $person['lastName'];

    $this->assertIsString($lastName, 'Last name is string');
  }

  // Tests for gender
  public function test_Gender_IsString() {
    $person = $this->fakeInfo->getFakePerson();;
    $gender = $person['gender'];

    $this->assertIsString($gender, 'Gender is string');
  }

  public function test_Gender_IsBinary() {
    $person = $this->fakeInfo->getFakePerson();
    $gender = $person['gender'];

    $result = in_array($gender, ['male', 'female']);

    $this->assertTrue($result, 'Gender is male or female');
  }

  // Tests for birthDate
  public function test_BirthDate_IsString() {
    $person = $this->fakeInfo->getFakePerson();
    $birthDate = $person['birthDate'];

    $this->assertIsString($birthDate, 'Birth date is string');
  }

  public function test_BirthDate_IsValid() {
    $person = $this->fakeInfo->getFakePerson();
    $birthDate = $person['birthDate'];
    $dobArr = explode("-", $birthDate);

    $result = checkdate($dobArr[1], $dobArr[2], $dobArr[0]);

    $this->assertTrue($result, 'Birth date is valid');
  }

  // Tests for address
  public function test_Address_IsArray() {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];

    $this->assertIsArray($address, 'Address is array');
  }

  public function test_Address_Contains6Values() {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];
    $exp = 6;

    $result = count($address);

    $this->assertEquals($exp, $result, 'Address contains 6 values');
  }

  /**
   * @dataProvider provideAddressKeys
   */
  public function test_Address_ContainsKey($key) {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];

    $this->assertArrayHasKey($key, $address, "Address contains key: $key");
  }
  public static function provideAddressKeys() {
    return [
      ['street'],
      ['number'],
      ['floor'],
      ['door'],
      ['postal_code'],
      ['town_name'],
    ];
  }

  /**
   * @dataProvider provideAddressNonKeys
   */
  public function test_Address_ContainsNotKey($key) {
    $person = $this->fakeInfo->getFakePerson();
    $address = $person['address'];

    $this->assertArrayNotHasKey($key, $address, "Address does not contain key: $key");
  }
  public static function provideAddressNonKeys() {
    return [
      ['Street'],     // Actual key but capitalised
      ['postalCode'], // Actual key but in camel case
      ['townname'],   // Actual key without underscore
      ['country'],    // Potential key
    ];
  }

  public function test_Address_StreetIsString() {
    $person = $this->fakeInfo->getFakePerson();
    $street = $person['address']['street'];

    $this->assertIsString($street, 'Street is string');
  }

  public function test_Address_StreetIs40Characters() {
    $person = $this->fakeInfo->getFakePerson();
    $street = $person['address']['street'];
    $exp = 40;

    $length = mb_strlen($street);

    $this->assertEquals($exp, $length, 'Street is 40 characters');
  }

  public function test_Address_NumberIsString() {
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];

    $this->assertIsString($number, 'Number is string');
  }

  public function test_Address_NumberIsGreaterThan0() {
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];
    $exp = 1;

    $isolatedNumber = intval(preg_replace('/[\D]/', '', $number));

    $this->assertGreaterThan($exp, $isolatedNumber, 'Number is greater than 0');
  }

  public function test_Address_NumberIsLessThan1000() {
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];
    $exp = 1000;

    $isolatedNumber = intval(preg_replace('/[\D]/', '', $number));

    $this->assertLessThan($exp, $isolatedNumber, 'Number is greater than 0');
  }

  public function test_Address_FloorIsStringOrInt() {
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];

    $result = is_int($floor) || is_string($floor);

    $this->assertTrue($result, 'Floor is string or int');
  }

  public function test_Address_FloorIsGreaterThan0() {
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];
    $exp = 0;

    $isolatedNumber = preg_replace('/st/', 1, $floor);

    $this->assertGreaterThan($exp, $isolatedNumber, 'Floor is greater than 0');
  }

  public function test_Address_FloorIsLessThan100() {
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];
    $exp = 100;

    $isolatedNumber = preg_replace('/st/', 99, $floor);

    $this->assertLessThan($exp, $isolatedNumber, 'Floor is greater than 0');
  }

  public function test_Address_DoorIsStringOrInt() {
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];

    $result = is_int($door) || is_string($door);

    $this->assertTrue($result, 'Door is string or int');
  }

  public function test_Address_DoorMatchesPattern() {
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    // th | mf | tv | 1 - 50 | a to å followed optionally by 1 to 3 digits optionally preceeded by a hyphen
    $pattern = '/^(th|mf|tv|([1-9]|[1-4][0-9]|50)|[a-zæøå](-?[0-9]{1,3})?)$/';

    $this->assertMatchesRegularExpression($pattern, $door, 'Door is ' . $door);
  }

  public function test_Address_DoorIs1OrMoreCharacters() {
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    $exp = 1;

    $length = mb_strlen($door);

    $this->assertGreaterThanOrEqual($exp, $length, 'Door is 1 or more characters');
  }

  public function test_Address_DoorIs5OrLessCharacters() {
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    $exp = 5;

    $length = mb_strlen($door);

    $this->assertLessThanOrEqual($exp, $length, 'Door is 5 or less characters');
  }

  public function test_Address_TownIsString() {
    $person = $this->fakeInfo->getFakePerson();
    $town = $person['address']['town_name'];

    $this->assertIsString($town, 'Town is string');
  }

  public function test_Address_ZipIsString() {
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];

    $this->assertIsString($zip, 'Zip is string');
  }

  public function test_Address_ZipIs4Characters() {
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];
    $exp = 4;

    $result = mb_strlen($zip);

    $this->assertEquals($exp, $result, 'Zip is string');
  }

  public function test_Address_ZipIs4Digits() {
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];
    $pattern = '/^[0-9]{4}$/';

    $this->assertMatchesRegularExpression($pattern, $zip, 'Zip is string');
  }

  // Tests for phone number
  public function test_Phone_IsString() {
    $person = $this->fakeInfo->getFakePerson();
    $phone = $person['phoneNumber'];

    $this->assertIsString($phone, 'Phone is string');
  }

  public function test_Phone_Is8Characters() {
    $person = $this->fakeInfo->getFakePerson();
    $phone = $person['phoneNumber'];
    $exp = 8;

    $length = mb_strlen($phone);

    $this->assertEquals($exp, $length, 'Phone is 8 characters');
  }

  public function test_Phone_HasCorrectPrefix() {
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
    $result = in_array(substr($phone, 0, 2), $prefixes) || in_array(substr($phone, 0, 3), $prefixes);

    $this->assertTrue($result, 'Phone has a correct prefix');
  }
}
