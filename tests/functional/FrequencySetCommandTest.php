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

        $payload = [
            'data' => ['updateFrequency' => 'WEEKLY'],
            'headers' => [],
            'status_code' => 200,
            'status_code_reason' => '',
        ];
        $this->setMockPayload($payload);

        $r = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()), [], false);
        $this->assertStringContainsString('daily', $r);
    }
}
