<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class FrequencySetCommandTest extends TerminusTestBase
{
    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:frequency');

        $r = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()), [], false);
        $this->assertStringContainsString('weekly', $r);
    }
}
