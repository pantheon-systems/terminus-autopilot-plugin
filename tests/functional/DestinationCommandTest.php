<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class DestinationCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class DestinationCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

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
        $this->assertCommandExists('site:autopilot:destination');

        $this->setMockPayload([
            'data' => ['deploymentDestination' => 'dev'],
        ]);

        // Get "destination" setting value.
        $output = $this->terminus(sprintf('site:autopilot:destination %s', $this->getSiteName()));
        $this->assertEquals('dev', $output);

        // Set a valid "destination" setting value.
        $output = $this->terminus(sprintf('site:autopilot:destination %s test', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot destination updated to test.', $output);

        // Set an invalid "destination" setting value.
        $output = $this->terminus(
            sprintf('site:autopilot:destination %s invalid_destination', $this->getSiteName()),
            ['2>&1']
        );
        $this->assertStringContainsString(
            'Autopilot destination did not successfully update: "invalid_destination" is not a valid destination value',
            $output
        );

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:destination %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        $this->setMockPayload([
            'status_code' => 500,
            'status_code_reason' => 'server error',
        ]);

        // Get "destination" setting value for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:destination %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);

        // set "destination" setting value for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:destination %s dev', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            'Autopilot destination did not successfully update: Failed requesting Autopilot API: server error',
            $output
        );
    }
}
