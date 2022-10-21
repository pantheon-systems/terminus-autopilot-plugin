<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

/**
 * Class DeactivateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DeactivateCommandTest extends TerminusTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\DeactivateCommand::deactivate()
     */
    public function testDeactivateCommand()
    {
        $this->assertCommandExists('site:autopilot:deactivate');
    }
}
