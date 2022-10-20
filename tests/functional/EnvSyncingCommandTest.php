<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class EnvSyncingCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class EnvSyncingCommandTest extends TerminusTestBase
{
    use MockPayloadAwareTrait;

    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::enable()
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::disable()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::setEnvSyncing()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');
        $this->assertCommandExists('site:autopilot:env-sync:disable');

        $this->setMockPayload([
            'data' => [
                'cloneContent' => ['enabled' => true],
            ],
            'status_code' => 200,
        ]);

        // Enable environment syncing.
        $output = $this->terminus(sprintf('site:autopilot:env-sync:enable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is enabled.', $output);

        // Disable environment syncing.
        $output = $this->terminus(sprintf('site:autopilot:env-sync:disable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is disabled.', $output);
    }
}
