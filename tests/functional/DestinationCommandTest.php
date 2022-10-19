<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

/**
 * Class DestinationCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DestinationCommandTest extends TerminusTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\DestinationCommand::destination()
     */
    public function testDestinationSetCommand()
    {
        $this->assertCommandExists('site:autopilot:destination');
    }
}
