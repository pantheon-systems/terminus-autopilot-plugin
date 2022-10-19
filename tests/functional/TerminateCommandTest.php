<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

/**
 * Class TerminateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class TerminateCommandTest extends TerminusTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\TerminateCommand::terminate()
     */
    public function testTerminateCommand()
    {
        $this->markTestSkipped();
    }
}
