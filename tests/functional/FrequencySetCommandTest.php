<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class FrequencySetCommandTest extends TerminusTestBase
{
    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');
    }
}
