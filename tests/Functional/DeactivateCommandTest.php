<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

/**
 * Class DeactivateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DeactivateCommandTest extends CommandTestBase
{
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

        $this->setRequestMockPayload(
            ['status_code' => 200],
            'terminate',
            ['method' => 'DELETE']
        );

        $output = $this->terminus(sprintf('site:autopilot:deactivate %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot is deactivated.', $output);
    }
}
