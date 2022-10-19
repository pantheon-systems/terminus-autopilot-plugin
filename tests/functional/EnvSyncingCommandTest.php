<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class EnvSyncingCommandTest extends TerminusTestBase
{
    public function testEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');
        $this->assertCommandExists('site:autopilot:env-sync:disable');
    }
}
