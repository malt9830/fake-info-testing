<?php

require_once 'src\FakeInfo.php';

use PHPUnit\Framework\TestCase;

class FakeInfoTest extends TestCase {
  // private FakeInfo $fakeInfo;

  // protected function setUp(): void {
  //   $this->fakeInfo = $this->createStub(FakeInfo::class);
  // }

  // protected function tearDown(): void {
  //   unset($this->fakeInfo);
  // }

  public function test_getCpr() {
    $fakeInfo = new FakeInfo();
    $exp = 10;

    $cpr = $fakeInfo->getCpr();

    $this->assertEquals($exp, strlen($cpr), 'They are the same length');
  }
}
