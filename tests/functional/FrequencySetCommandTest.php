<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;

final class FrequencySetCommandTest extends TerminusTestBase
{
    public function testFrequencySetCommand()
    {
        $this->assertCommandExists('site:autopilot:frequency');

        $mock = [
            'data' => ['updateFrequency' => 'WEEKLY'],
            'headers' => [],
            'status_code' => 200,
            'status_code_reason' => '',
        ];

        putenv(sprintf('TERMINUS_REQUEST_MOCK=%s', json_encode($mock)));

        $r = $this->terminus(sprintf('site:autopilot:frequency %s', $this->getSiteName()), [], false);
        $this->assertStringContainsString('daily', $r);
    }
}
