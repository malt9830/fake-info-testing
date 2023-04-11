<?php

require_once 'src/FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakePersonFirstNameTest extends TestCase {
  private FakeInfo $fakeInfo;

  protected function setUp(): void {
    $this->fakeInfo = $this->createStub(FakeInfo::class);
  }

  protected function tearDown(): void {
    unset($this->fakeInfo);
  }

  /**
   * Test if first name is string

   * @dataProvider provide_FirstName_IsString
   */
  public function test_FirstName_IsString($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['firstName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $firstName = $person['firstName'];

    $result = is_string($firstName);

    $this->assertEquals($exp, $result, "$firstName is string");
  }
  public static function provide_FirstName_IsString() {
    return [
      ['Amanda M.'],                      // Contains space, period
      ['Anne-Lise'],                      // Contains hyphen
      ["N'Golo"],                         // Contains apostrophe
      ['J'],                              // Valid lower boundary
      ['Bo'],                             // Valid lower boundary + 1
      ['Rene Karl Wilhelm Johann Josef'], // Valid upper boundary
      ['Rene Karl Wilhelm Johann Jose'],  // Valid upper boundary - 1
      [true, false],                      // Bool
      [0, false],                         // Zero
      [1, false],                         // Integer
    ];
  }

  /**
   * Test if first name is 1 or more characters
   * 
   * @dataProvider provide_FirstName_Is1OrMoreCharacters
   */
  public function test_FirstName_Is1OrMoreCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['firstName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $firstName = $person['firstName'];
    $min = 1;

    $length = $min <= mb_strlen($firstName);

    $this->assertEquals($exp, $length, "$firstName is 1 or more characters");
  }
  public static function provide_FirstName_Is1OrMoreCharacters() {
    return [
      ['Amanda M.'],                       // Contains space, period
      ['Anne-Lise'],                       // Contains hyphen
      ["N'Golo"],                          // Contains apostrophe
      ['J'],                               // Valid lower boundary
      ['Bo'],                              // Valid lower boundary + 1
      ['Rene Karl Wilhelm Johann Josef'],  // Valid upper boundary
      ['Rene Karl Wilhelm Johann Jose'],   // Valid upper boundary - 1
      ['Aliaune Damala Bouga Time Bongo'], // Invalid upper boundary
      ['', false],                         // Invalid lower boundary | empty string
    ];
  }

  /**
   * Test if first name is 30 or less characters
   * 
   * @dataProvider provide_FirstName_Is30OrLessCharacters
   */
  public function test_FirstName_Is30OrLessCharacters($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['firstName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $firstName = $person['firstName'];
    $max = 30;

    $result = mb_strlen($firstName) <= $max;

    $this->assertEquals($exp, $result, "$firstName is 30 or less characters");
  }
  public static function provide_FirstName_Is30OrLessCharacters() {
    return [
      ['Amanda M.'],                       // Contains space, period
      ['Anne-Lise'],                       // Contains hyphen
      ["N'Golo"],                          // Contains apostrophe
      ['J'],                               // Valid lower boundary
      ['Bo'],                              // Valid lower boundary + 1
      ['Rene Karl Wilhelm Johann Josef'],  // Valid upper boundary
      ['Rene Karl Wilhelm Johann Jose'],   // Valid upper boundary - 1
      [''],                                // Invalid lower boundary | empty string
      ['Aliaune Damala Bouga Time Bongo', false], // Invalid upper boundary
    ];
  }

  /**
   * Test if first name contains only allowed characters
   * 
   * @dataProvider provide_FirstName_MatchesPattern
   */
  public function test_FakePerson_FirstName_MatchesPattern($value, bool $exp = true) {
    $this->fakeInfo->method('getFakePerson')->willReturn(['firstName' => $value]);
    $person = $this->fakeInfo->getFakePerson();
    $firstName = $person['firstName'];
    $pattern = "/^[A-Za-zÀ-ÖØ-öø-ÿ'-. ]{1,30}+$/";

    $result = preg_match($pattern, $firstName);

    $this->assertEquals($exp, $result, "$firstName matches pattern");
  }
  public static function provide_FirstName_MatchesPattern() {
    return [
      ['Amanda M.'],                               // Contains space, period
      ['Anne-Lise'],                               // Contains hyphen
      ["N'Golo"],                                  // Contains apostrophe
      ['J'],                                       // Valid lower boundary
      ['Bo'],                                      // Valid lower boundary + 1
      ['Rene Karl Wilhelm Johann Josef'],          // Valid upper boundary
      ['Rene Karl Wilhelm Johann Jose'],           // Valid upper boundary - 1
      ['Ana$ta$ia', false],                        // Contains dollar
      ['X Æ A-12', false],                         // Contains numbers
      [true, false],                               // Bool
      [0, false],                                  // Zero
      [1, false],                                  // Integer
      ['', false],                                 // Invalid lower boundary | empty string
      ['Aliaune Damala Bouga Time Bongo', false],  // Invalid upper boundary
    ];
  }
}
