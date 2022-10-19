<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class DestinationSetCommandTest extends TerminusTestBase
{
    public function testDestinationSetCommand()
    {
        $this->assertCommandExists('site:autopilot:destination');
    }
}
