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

  public function test_getCpr() {
    // $this->fakeInfo->method('getCpr')->willReturn('0101011234');

    $exp = 10;
    $cpr = $this->fakeInfo->getCpr();

    $this->assertEquals($exp, strlen($cpr), 'They are the same length');
  }
}
