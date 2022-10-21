<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

/**
 * Class FrequencyCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class FrequencyCommandTest extends CommandTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\FrequencyCommand::frequency()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::getFrequency()
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::setFrequency()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:frequency');

        // Get "frequency" setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
                'data' => ['updateFrequency' => 'WEEKLY'],
            ],
            'settings',
            []
        );
        $output = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()));
        $this->assertEquals('weekly', $output);

        // Set a valid "frequency" setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
            ],
            'settings',
            [
                'json' => ['updateFrequency' => 'MONTHLY'],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(sprintf('site:autopilot:frequency %s monthly', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot frequency updated to monthly.', $output);

        // Set an invalid "frequency" setting value.
        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s invalid_frequency', $this->getSiteName()),
            ['2>&1']
        );
        $this->assertStringContainsString(
            'Autopilot frequency did not successfully update: "invalid_frequency" is not a valid frequency value',
            $output
        );

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        // Get "frequency" setting value for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            []
        );
        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);

        // Set "frequency" setting value for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            [
                'json' => ['updateFrequency' => 'MANUAL'],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s manual', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            'Autopilot frequency did not successfully update: Failed requesting Autopilot API: server error',
            $output
        );
    }
}
