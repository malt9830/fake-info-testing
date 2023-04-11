<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonLastNameTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if last name is string

   * @dataProvider provide_LastName_IsString
   */
  public function test_LastName_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['lastName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $lastName = $person['lastName'];

    $result = is_string($lastName);

    $this->assertEquals($exp, $result, "$lastName is string");
  }
  public static function provide_LastName_IsString() {
    return [
      ['I. Jensen'],                      // Contains space, period
      ['Alexander-Arnold'],               // Contains hyphen
      ["Nyong'o"],                        // Contains apostrophe
      ['A'],                              // Valid lower boundary
      ['Ji'],                             // Valid lower boundary + 1
      ['Wolfeschlegelsteinhausenberger'], // Valid upper boundary
      ['Wolfschlegelsteinhausenberger'],  // Valid upper boundary - 1
      [true, false],                      // Bool
      [0, false],                         // Zero
      [1, false],                         // Integer
    ];
  }

  /**
   * Test if last name is 1 or more characters
   * 
   * @dataProvider provide_LastName_Is1OrMoreCharacters
   */
  public function test_LastName_Is1OrMoreCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['lastName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $lastName = $person['lastName'];
    $min = 1;

    $length = $min <= mb_strlen($lastName);

    $this->assertEquals($exp, $length, "$lastName is 1 or more characters");
  }
  public static function provide_LastName_Is1OrMoreCharacters() {
    return [
      ['I. Jensen'],                       // Contains space, period
      ['Alexander-Arnold'],                // Contains hyphen
      ["Nyong'o"],                         // Contains apostrophe
      ['A'],                               // Valid lower boundary
      ['Ji'],                              // Valid lower boundary + 1
      ['Wolfeschlegelsteinhausenberger'],  // Valid upper boundary
      ['Wolfschlegelsteinhausenberger'],   // Valid upper boundary - 1
      ['Wolfenschlegelsteinhausenberger'], // Invalid upper boundary
      ['', false],                         // Invalid lower boundary | empty string
    ];
  }

  /**
   * Test if last name is 30 or less characters
   * 
   * @dataProvider provide_LastName_Is30OrLessCharacters
   */
  public function test_LastName_Is30OrLessCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['lastName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $lastName = $person['lastName'];
    $max = 30;

    $result = mb_strlen($lastName) <= $max;

    $this->assertEquals($exp, $result, "$lastName is 30 or less characters");
  }
  public static function provide_LastName_Is30OrLessCharacters() {
    return [
      ['I. Jensen'],                       // Contains space, period
      ['Alexander-Arnold'],                // Contains hyphen
      ["Nyong'o"],                         // Contains apostrophe
      ['A'],                               // Valid lower boundary
      ['Ji'],                              // Valid lower boundary + 1
      ['Wolfeschlegelsteinhausenberger'],  // Valid upper boundary
      ['Wolfschlegelsteinhausenberger'],   // Valid upper boundary - 1
      [''],                                // Invalid lower boundary | empty string
      ['Wolfenschlegelsteinhausenberger', false], // Invalid upper boundary
    ];
  }

  /**
   * Test if last name contains only allowed characters
   * 
   * @dataProvider provide_LastName_MatchesPattern
   */
  public function test_FakePerson_LastName_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['lastName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $lastName = $person['lastName'];
    $pattern = "/^[A-Za-zÀ-ÖØ-öø-ÿ'-. ]{1,30}+$/";

    $result = preg_match($pattern, $lastName);

    $this->assertEquals($exp, $result, "$lastName matches pattern");
  }
  public static function provide_LastName_MatchesPattern() {
    return [
      ['I. Jensen'],                               // Contains space, period
      ['Alexander-Arnold'],                        // Contains hyphen
      ["Nyong'o"],                                 // Contains apostrophe
      ['A'],                                       // Valid lower boundary
      ['Ji'],                                      // Valid lower boundary + 1
      ['Wolfeschlegelsteinhausenberger'],          // Valid upper boundary
      ['Wolfschlegelsteinhausenberger'],           // Valid upper boundary - 1
      ['Mbapp€', false],                           // Contains euro
      ['X Æ A-12', false],                         // Contains numbers
      [true, false],                               // Bool
      [0, false],                                  // Zero
      [1, false],                                  // Integer
      ['', false],                                 // Invalid lower boundary | empty string
      ['Wolfenschlegelsteinhausenberger', false],  // Invalid upper boundary
    ];
  }
}
