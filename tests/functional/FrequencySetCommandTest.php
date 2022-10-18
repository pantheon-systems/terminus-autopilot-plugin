<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

final class FrequencySetCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:frequency');

        $this->setMockPayload([
            'data' => ['updateFrequency' => 'WEEKLY'],
            'status_code' => 200,
        ]);

        $output = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()));
        $this->assertEquals('weekly', $output);

        $output = $this->terminus(sprintf('site:autopilot:frequency %s daily', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot frequency updated to daily.', $output);

        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s invalid_frequency', $this->getSiteName()),
            ['2>&1']
        );
        $this->assertStringContainsString(
            'Autopilot frequency did not successfully update: "invalid_frequency" is not a valid frequency value',
            $output
        );

        $this->setMockPayload([
            'data' => null,
            'status_code' => 404,
            'status_code_reason' => 'not found',
        ]);

        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: not found', $output);

        $output = $this->terminus(
            sprintf('site:autopilot:frequency %s manual', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            'Autopilot frequency did not successfully update: Failed requesting Autopilot API: not found',
            $output
        );
    }
}
