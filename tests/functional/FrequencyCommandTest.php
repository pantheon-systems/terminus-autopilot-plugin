<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class FrequencyCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class FrequencyCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

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

        $this->setMockPayload([
            'data' => ['updateFrequency' => 'WEEKLY'],
            'status_code' => 200,
        ]);

        // Get "frequency" setting value.
        $output = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()));
        $this->assertEquals('weekly', $output);

        // Set a valid "frequency" setting value.
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

        $this->setMockPayload([
            'data' => null,
            'status_code' => 500,
            'status_code_reason' => 'server error',
        ]);

        // Get "frequency" setting value for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);

        // set "frequency" setting value for a non-200 status from API.
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
