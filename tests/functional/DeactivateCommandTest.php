<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class DeactivateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DeactivateCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\DeactivateCommand::deactivate()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::deactivate()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testDeactivateCommand()
    {
        $this->assertCommandExists('site:autopilot:deactivate');

        $this->setMockPayload([]);

        $output = $this->terminus(sprintf('site:autopilot:deactivate %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot is deactivated.', $output);
    }
}
