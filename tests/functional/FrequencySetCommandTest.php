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
    }
}
