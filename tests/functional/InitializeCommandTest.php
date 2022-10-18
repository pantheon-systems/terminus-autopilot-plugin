<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class InitializeCommandTest extends TerminusTestBase
{
    public function testInitializeCommand()
    {
        $this->assertCommandExists('site:autopilot:destination');
    }
}
