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

        $this->setMockPayload([]);

        // Enable environment syncing.
        $output = $this->terminus(sprintf('site:autopilot:env-sync:enable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is enabled.', $output);

        // Disable environment syncing.
        $output = $this->terminus(sprintf('site:autopilot:env-sync:disable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is disabled.', $output);

        // Run 'site:autopilot:env-sync:enable' command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:enable %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );
        // Run 'site:autopilot:env-sync:disable' command for a non-existing site.
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:disable %s', $non_existing_site_name),
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

        //  Run 'site:autopilot:env-sync:enable' command for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:enable %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
        //  Run 'site:autopilot:env-sync:disable' command for a non-200 status from API.
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:disable %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
    }
}
