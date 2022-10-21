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

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:deactivate %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );
    }
}
