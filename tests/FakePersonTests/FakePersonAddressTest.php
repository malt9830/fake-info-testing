<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonAddressTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if street is string

   * @dataProvider provide_Address_Street_IsString
   */
  public function test_Address_Street_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['street' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $street = $person['address']['street'];

    $result = is_string($street);

    $this->assertEquals($exp, $result, "$street is string");
  }
  public static function provide_Address_Street_IsString() {
    return [
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJ'],         // All upper case
      ['abcdefghijabcdefghijabcdefghijabcdefghij'],         // All lower case
      ['ABCDEFGHIJABCDEFGHIJabcdefghijabcdefghij'],         // Mixed case
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHI '],         // Space at end
      [true, false],                                        // Bool
      [0, false],                                           // Zero
      [40, false],                                          // Integer
    ];
  }

  /**
   * Test if street is 40 characters

   * @dataProvider provide_Address_Street_Is40Characters
   */
  public function test_Address_Street_Is40Characters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['street' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $street = $person['address']['street'];
    $length = 40;

    $result = mb_strlen($street) === $length;

    $this->assertEquals($exp, $result, "$street is 40 characters");
  }
  public static function provide_Address_Street_Is40Characters() {
    return [
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJ'],         // All upper case
      ['abcdefghijabcdefghijabcdefghijabcdefghij'],         // All lower case
      ['ABCDEFGHIJABCDEFGHIJabcdefghijabcdefghij'],         // Mixed case
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHI '],         // Space at end
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHI', false],   // Invalid lower boundary
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJK', false], // Invalid upper boundary
    ];
  }

  /**
   * Test if street is 40 characters

   * @dataProvider provide_Address_Street_MatchesPattern
   */
  public function test_Address_Street_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['street' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $street = $person['address']['street'];
    $pattern = "/^[A-Za-zÆØÅæøå ]{40}+$/u";

    $result = preg_match($pattern, $street);

    $this->assertEquals($exp, $result, "$street matches pattern");
  }
  public static function provide_Address_Street_MatchesPattern() {
    return [
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJ'],         // All upper case
      ['abcdefghijabcdefghijabcdefghijabcdefghij'],         // All lower case
      ['ABCDEFGHIJABCDEFGHIJabcdefghijabcdefghij'],         // Mixed case
      ['ABCDEFGÆØÅABCDEFGÆØÅabcdefgæøåabcdefgæøå'],         // Contains æ, ø, å
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHI '],         // Space at end
      ['1234567890123456789012345678901234567890', false],  // Invalid: contains numbers
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHI', false],   // Invalid lower boundary
      ['ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJK', false], // Invalid upper boundary
    ];
  }

  /**
   * Test if number is string

   * @dataProvider provide_Address_Number_IsString
   */
  public function test_Address_Number_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['number' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];

    $result = is_string($number);

    $this->assertEquals($exp, $result, 'Number is string');
  }
  public static function provide_Address_Number_IsString() {
    return [
      ['1A'],
      ['10A'],
      ['100A'],
      ['1'],
      ['2'],
      ['500'],
      ['998'],
      ['999'],
      ['1000'],
      ['0'],
      [0, false],      // Zero
      [1, false],      // Integer
      [500.5, false],  // Float
      [-500, false],   // Negative
      [true, false],   // Bool
    ];
  }

  /**
   * Test if number is greater than 0

   * @dataProvider provide_Address_Number_IsGreaterThan0
   */
  public function test_Address_Number_IsGreaterThan0($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['number' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];
    $min = 1;

    $result = $min <= intval(preg_replace('/[\D]/', '', $number));

    $this->assertEquals($exp, $result, "$number is greater than 0");
  }
  public static function provide_Address_Number_IsGreaterThan0() {
    return [
      ['1A'],             // Valid lower boundary with letter
      ['10A'],            // 2 Digit with letter
      ['100A'],           // 3 Digit with letter
      ['1'],              // Valid lower boundary
      ['2'],              // Valid lower boundary +
      ['10'],             // 2 Digit without letter
      ['100'],            // 3 Digit without letter
      ['500'],            // Middle value
      ['998'],            // Valid upper boundary -
      ['999'],            // Valid upper boundary
      ['0', false],       // Invalid lower boundary
      ['1000'],           // Invalid upper boundary
    ];
  }

  /**
   * Test if number is is less than 1000

   * @dataProvider provide_Address_Number_IsLessThan1000
   */
  public function test_Address_Number_IsLessThan1000($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['number' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];
    $max = 999;

    $result = intval(preg_replace('/[\D]/', '', $number)) <= $max;

    $this->assertEquals($exp, $result, "$number is less than 1000");
  }
  public static function provide_Address_Number_IsLessThan1000() {
    return [
      ['1A'],             // Valid lower boundary with letter
      ['10A'],            // 2 Digit with letter
      ['100A'],           // 3 Digit with letter
      ['1'],              // Valid lower boundary
      ['2'],              // Valid lower boundary +
      ['10'],             // 2 Digit without letter
      ['100'],            // 3 Digit without letter
      ['500'],            // Middle value
      ['998'],            // Valid upper boundary -
      ['999'],            // Valid upper boundary
      ['0'],              // Invalid lower boundary
      ['1000', false],    // Invalid upper boundary
    ];
  }

  /**
   * Test if number matches pattern

   * @dataProvider provide_Address_Number_MatchesPattern
   */
  public function test_Address_Number_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['number' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $number = $person['address']['number'];
    $pattern = "/^([1-9]|[1-9][0-9]|[1-9][0-9][0-9])([A-Z])?$/";

    $result = preg_match($pattern, $number);

    $this->assertEquals($exp, $result, "$number matches pattern");
  }
  public static function provide_Address_Number_MatchesPattern() {
    return [
      ['1A'],             // Valid lower boundary with letter
      ['1B'],             // Valid lower boundary with letter +
      ['10A'],            // 2 Digit with letter
      ['100A'],           // 3 Digit with letter
      ['1'],              // Valid lower boundary
      ['2'],              // Valid lower boundary +
      ['10'],             // 2 Digit without letter
      ['100'],            // 3 Digit without letter
      ['500'],            // Middle value
      ['998'],            // Valid upper boundary -
      ['999'],            // Valid upper boundary
      ['999Y'],           // Valid upper boundary with letter -
      ['999Z'],           // Valid upper boundary with letter
      ['0', false],       // Invalid lower boundary
      ['1000', false],    // Invalid upper boundary
      ['500.5', false],   // Invalid: float
      ['-500', false],    // Invalid: negative
      ['1Æ', false],      // Invalid: non A-Z character
      ['10OO', false],    // Invalid: multiple letters
      ['1@', false],      // Invalid: at sign
    ];
  }

  /**
   * Test if floor is string or int

   * @dataProvider provide_Address_Floor_IsStringOrInt
   */
  public function test_Address_Floor_IsStringOrInt($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['floor' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];

    $result = is_int($floor) || is_string($floor);

    $this->assertEquals($exp, $result, "$floor is string or int");
  }
  public static function provide_Address_Floor_IsStringOrInt() {
    return [
      ['st'],         // Valid string
      [1],            // Valid lower boundary
      [2],            // Valid lower boundary +
      [50],           // Middle value
      [98],           // Valid upper boundary -
      [99],           // Valid upper boundary
      [true, false],  // Bool
    ];
  }

  /**
   * Test if floor is greater than 0

   * @dataProvider provide_Address_Floor_IsGreaterThan0
   */
  public function test_Address_Floor_IsGreaterThan0($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['floor' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];
    $min = 1;

    $result = $min <= preg_replace('/st/', 1, $floor);

    $this->assertEquals($exp, $result, "$floor is greater than 0");
  }
  public static function provide_Address_Floor_IsGreaterThan0() {
    return [
      ['st'],       // Valid string
      [1],          // Valid lower boundary
      [2],          // Valid lower boundary +
      [50],         // Middle value
      [98],         // Valid upper boundary -
      [99],         // Valid upper boundary
      [0, false],   // Invalid lower boundary / zero
      [100],        // Invalid upper boundary
    ];
  }

  /**
   * Test if floor is less than 100

   * @dataProvider provide_Address_Floor_IsLessThan100
   */
  public function test_Address_Floor_IsLessThan100($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['floor' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];
    $max = 99;

    $result = preg_replace('/st/', 99, $floor) <= $max;

    $this->assertEquals($exp, $result, "$floor is less than 100");
  }
  public static function provide_Address_Floor_IsLessThan100() {
    return [
      ['st'],       // Valid string
      [1],          // Valid lower boundary
      [2],          // Valid lower boundary +
      [50],         // Middle value
      [98],         // Valid upper boundary -
      [99],         // Valid upper boundary
      [0],          // Invalid lower boundary / zero
      [100, false], // Invalid upper boundary
    ];
  }

  /**
   * Test if floor matches pattern

   * @dataProvider provide_Address_Floor_MatchesPattern
   */
  public function test_Address_Floor_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['floor' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $floor = $person['address']['floor'];
    $pattern = "/^(st|[1-9]|[1-9][0-9])$/";

    $result = preg_match($pattern, $floor);

    $this->assertEquals($exp, $result, "$floor matches pattern");
  }
  public static function provide_Address_Floor_MatchesPattern() {
    return [
      ['st'],         // Valid string
      [1],            // Valid lower boundary
      [2],            // Valid lower boundary +
      [50],           // Middle value
      [98],           // Valid upper boundary -
      [99],           // Valid upper boundary
      [0, false],     // Invalid lower boundary / zero
      [-5, false],    // Invalid upper boundary
      [50.5, false],  // Invalid upper boundary
      ['fl', false],  // Invalid string
      ['', false],    // Empty string
    ];
  }

  /**
   * Test if door is string or int

   * @dataProvider provide_Address_Door_IsStringOrInt
   */
  public function test_Address_Door_IsStringOrInt($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['door' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];

    $result = is_int($door) || is_string($door);

    $this->assertEquals($exp, $result, "$door is string or int");
  }
  public static function provide_Address_Door_IsStringOrInt() {
    return [
      ['th'],                // Valid string: th
      ['mf'],                // Valid string: mf
      ['tv'],                // Valid string: tv
      ['a'],                 // Valid string: letter lower boundary
      ['z'],                 // Valid string: letter upper boundary
      ['a1'],                // Valid string: letter + 1 digit
      ['a11'],               // Valid string: letter + 2 digits
      ['a111'],              // Valid string: letter + 3 digits
      ['a-1'],               // Valid string: letter + 1 digi with hyphen
      ['a-11'],              // Valid string: letter + 2 digits with hyphen
      ['a-111'],             // Valid string: letter + 3 digits with hyphen
      [1],                   // Valid lower boundary
      [2],                   // Valid lower boundary +
      [25],                  // Middle
      [49],                  // Valid upper boundary -
      [50],                  // Valid upper boundary
      [25.5, false],         // Invalid data type: Float
      [true, false],         // Invalid data type: Bool
    ];
  }

  /**
   * Test if door is 10 or more characters

   * @dataProvider provide_Address_Door_Is1OrMoreCharacters
   */
  public function test_Address_Door_Is1OrMoreCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['door' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    $min = 1;

    $result = $min <= mb_strlen($door);

    $this->assertGreaterThanOrEqual($exp, $result, "$door is 1 or more characters");
  }
  public static function provide_Address_Door_Is1OrMoreCharacters() {
    return [
      ['th'],                // Valid string: th
      ['mf'],                // Valid string: mf
      ['tv'],                // Valid string: tv
      ['a'],                 // Valid string: letter lower boundary
      ['z'],                 // Valid string: letter upper boundary
      ['a1'],                // Valid string: letter + 1 digit
      ['a11'],               // Valid string: letter + 2 digits
      ['a111'],              // Valid string: letter + 3 digits
      ['a-1'],               // Valid string: letter + 1 digi with hyphen
      ['a-11'],              // Valid string: letter + 2 digits with hyphen
      ['a-111'],             // Valid string: letter + 3 digits with hyphen
      [1],                   // Valid lower boundary
      [2],                   // Valid lower boundary +
      [25],                  // Middle
      [49],                  // Valid upper boundary -
      [50],                  // Valid upper boundary
      ['', false],           // Empty string
    ];
  }

  /**
   * Test if door is 50 or less characters

   * @dataProvider provide_Address_Door_Is5OrLessCharacters
   */
  public function test_Address_Door_Is5OrLessCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['door' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    $max = 5;

    $result = mb_strlen($door) <= $max;

    $this->assertEquals($exp, $result, "$door is 5 or less characters");
  }
  public static function provide_Address_Door_Is5OrLessCharacters() {
    return [
      ['th'],                // Valid string: th
      ['mf'],                // Valid string: mf
      ['tv'],                // Valid string: tv
      ['a'],                 // Valid string: letter lower boundary
      ['z'],                 // Valid string: letter upper boundary
      ['a1'],                // Valid string: letter + 1 digit
      ['a11'],               // Valid string: letter + 2 digits
      ['a111'],              // Valid string: letter + 3 digits
      ['a-1'],               // Valid string: letter + 1 digi with hyphen
      ['a-11'],              // Valid string: letter + 2 digits with hyphen
      ['a-111'],             // Valid string: letter + 3 digits with hyphen
      [1],                   // Valid lower boundary
      [2],                   // Valid lower boundary +
      [25],                  // Middle
      [49],                  // Valid upper boundary -
      [50],                  // Valid upper boundary
      ['aa-555', false],     // Invalid upper boundary
    ];
  }

  /**
   * Test if door matches pattern

   * @dataProvider provide_Address_Door_MatchesPattern
   */
  public function test_Address_Door_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['door' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $door = $person['address']['door'];
    // th | mf | tv | 1 - 50 | a to å followed optionally by 1 to 3 digits optionally preceeded by a hyphen
    $pattern = '/^(th|mf|tv|([1-9]|[1-4][0-9]|50)|[a-zæøå](-?([1-9]|[1-9][0-9]|[1-9][0-9][0-9]))?)$/';

    $result = preg_match($pattern, $door);

    $this->assertEquals($exp, $door, "$door matches pattern");
  }
  public static function provide_Address_Door_MatchesPattern() {
    return [
      ['th'],                // Valid string: th
      ['mf'],                // Valid string: mf
      ['tv'],                // Valid string: tv
      ['a'],                 // Valid string: letter lower boundary
      ['å'],                 // Valid string: letter upper boundary / non a-z
      ['a1'],                // Valid string: letter + 1 digit
      ['a11'],               // Valid string: letter + 2 digits
      ['a111'],              // Valid string: letter + 3 digits
      ['a-1'],               // Valid string: letter + 1 digi with hyphen
      ['a-11'],              // Valid string: letter + 2 digits with hyphen
      ['a-111'],             // Valid string: letter + 3 digits with hyphen
      [1],                   // Valid lower boundary
      [2],                   // Valid lower boundary +
      [25],                  // Middle
      [49],                  // Valid upper boundary -
      [50],                  // Valid upper boundary
      [0, false],            // Zero
      // [25.5, false],         // Invalid data type: Float
      ['', false],           // Empty string
      // ['aa-111', false],     // Double letter
    ];
  }

  // public function test_Address_TownIsString() {
  //   $person = $this->fakeInfo->getFakePerson();
  //   $town = $person['address']['town_name'];

  //   $this->assertIsString($town, 'Town is string');
  // }


  /**
   * Test if zip is string

   * @dataProvider provide_Address_Zip_IsString
   */
  public function test_Address_Zip_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['postal_code' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];

    $result = is_string($zip);

    $this->assertEquals($exp, $zip, "$zip is string");
  }
  public static function provide_Address_Zip_IsString() {
    return [
      ['8380'],
    ];
  }

  /**
   * Test if zip is 4 characters

   * @dataProvider provide_Address_Zip_Is4Characters
   */
  public function test_Address_Zip_Is4Characters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['postal_code' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];
    $exp = 4;

    $result = mb_strlen($zip);

    $this->assertEquals($exp, $result, "$zip is 4 characters");
  }
  public static function provide_Address_Zip_Is4Characters() {
    return [
      ['8380'],
    ];
  }

  /**
   * Test if zip matches pattern

   * @dataProvider provide_Address_Zip_Is4Digits
   */
  public function test_Address_Zip_Is4Digits($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['address' => ['postal_code' => $value]]);
    $person = $this->fakeInfo->getFakePerson();
    $zip = $person['address']['postal_code'];
    $pattern = '/^[0-9]{4}$/';

    $result = preg_match($pattern, $zip);

    $this->assertEquals($exp, $result, "$zip matches pattern");
  }
  public static function provide_Address_Zip_Is4Digits() {
    return [
      ['8380'],
    ];
  }
}
