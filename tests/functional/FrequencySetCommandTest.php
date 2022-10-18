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
        $result = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()), []);
        $this->assertStringContainsString('weekly', $result);

        $this->setMockPayload([
            'data' => null,
            'status_code' => 404,
            'status_code_reason' => 'not found'
        ]);
        $result = $this->terminus(
            sprintf('site:autopilot:frequency %s', $this->getSiteName()),
            [],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: not found', $result);
    }
}
