<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class ActivateCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class ActivateCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\ActivateCommand::activate()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::activate()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testActivateCommand()
    {
        $this->assertCommandExists('site:autopilot:activate');

        $this->setMockPayload([]);

        $output = $this->terminus(sprintf('site:autopilot:activate %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot is activated.', $output);
    }
}
