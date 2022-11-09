<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

/**
 * Class ActivateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class ActivateCommandTest extends CommandTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\ActivateCommand::activate()
     *
     * @see    \Pantheon\TerminusAutopilot\AutopilotApi\Client::activate()
     * @see    \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testActivateCommand()
    {
        $this->assertCommandExists('site:autopilot:activate');

        $this->setRequestMockPayload(
            ['status_code' => 200],
            'initialize',
            [
                'json' => [
                    'id' => '',
                    'workspaceId' => '',
                    'settings' => (object) [],
                    'skip' => false,
                ],
                'method' => 'POST',
            ]
        );

        $output = $this->terminus(sprintf('site:autopilot:activate %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot is activated.', $output);

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:activate %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'initialize',
            [
                'json' => [
                    'id' => '',
                    'workspaceId' => '',
                    'settings' => (object) [],
                    'skip' => false,
                ],
                'method' => 'POST',
            ]
        );

        // Run the command for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:activate %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
    }
}
