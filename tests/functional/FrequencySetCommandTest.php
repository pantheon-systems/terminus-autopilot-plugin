<?php

namespace Pantheon\TerminusHello\Model;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class FrequencySetCommandTest extends TerminusTestBase
{
    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');
    }
}
