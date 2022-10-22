<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

/**
 * Class DestinationCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DestinationCommandTest extends CommandTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\DestinationCommand::destination()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::getDestination()
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::setDestination()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testDestinationSetCommand()
    {
        $this->assertCommandExists('site:autopilot:deployment-destination');

        // Get "deployment destination" setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
                'data' => ['deploymentDestination' => 'dev'],
            ],
            'settings',
            []
        );
        $output = $this->terminus(sprintf('site:autopilot:deployment-destination %s', $this->getSiteName()));
        $this->assertEquals('dev', $output);

        // Set a valid "deployment destination" setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
            ],
            'settings',
            [
                'json' => ['deploymentDestination' => 'test'],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(
            sprintf('site:autopilot:deployment-destination %s test', $this->getSiteName()),
            ['2>&1']
        );
        $this->assertStringContainsString('Autopilot deployment destination updated to test.', $output);

        // Set an invalid "destination" setting value.
        $output = $this->terminus(
            sprintf('site:autopilot:deployment-destination %s invalid_destination', $this->getSiteName()),
            ['2>&1']
        );
        $this->assertStringContainsString(
            'Autopilot deployment destination did not successfully update: "invalid_destination" is not a valid deployment destination value',
            $output
        );

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:deployment-destination %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        // Get "deployment destination" setting value for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            []
        );
        $output = $this->terminus(
            sprintf('site:autopilot:deployment-destination %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);

        // Set "deployment-destination" setting value for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            [
                'json' => ['deploymentDestination' => 'dev'],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(
            sprintf('site:autopilot:deployment-destination %s dev', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            'Autopilot deployment destination did not successfully update: Failed requesting Autopilot API: server error',
            $output
        );
    }
}
