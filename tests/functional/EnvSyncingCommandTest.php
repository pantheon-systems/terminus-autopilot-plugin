<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

/**
 * Class EnvSyncingCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class EnvSyncingCommandTest extends TerminusTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::enable()
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::disable()
     */
    public function testEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');
        $this->assertCommandExists('site:autopilot:env-sync:disable');

        // @todo: add more scenarios.
    }
}
