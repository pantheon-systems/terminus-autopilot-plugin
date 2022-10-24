<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

/**
 * Class EnvSyncingCommandTest.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
final class EnvSyncingCommandTest extends CommandTestBase
{
    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::enable()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::setEnvSyncing()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testEnableEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:enable');

        // Enable environment syncing.
        $this->setRequestMockPayload(
            ['status_code' => 200],
            'settings',
            [
                'json' => ['cloneContent' => ['enabled' => true]],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(sprintf('site:autopilot:env-sync:enable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is enabled.', $output);

        // Run the command for a non-existing site.
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

        // Run the command for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            [
                'json' => ['cloneContent' => ['enabled' => true]],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:enable %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
    }

    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::disable()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::setEnvSyncing()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testDisableEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync:disable');

        // Disable environment syncing.
        $this->setRequestMockPayload(
            ['status_code' => 200],
            'settings',
            [
                'json' => ['cloneContent' => ['enabled' => false]],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(sprintf('site:autopilot:env-sync:disable %s', $this->getSiteName()), ['2>&1']);
        $this->assertStringContainsString('Autopilot environment syncing is disabled.', $output);

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:disable %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        // Run the command for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            [
                'json' => ['cloneContent' => ['enabled' => false]],
                'method' => 'POST',
            ]
        );
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync:disable %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
    }

    /**
     * @test
     *
     * @covers \Pantheon\TerminusAutopilot\Commands\EnvSyncingCommand::get()
     *
     * @see \Pantheon\TerminusAutopilot\AutopilotApi\Client::getEnvSyncing()
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    public function testGetEnvSyncingCommand()
    {
        $this->assertCommandExists('site:autopilot:env-sync');

        // Get "enabled" environment syncing setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
                'data' => ['cloneContent' => ['enabled' => true]],
            ],
            'settings',
            []
        );
        $output = $this->terminus(sprintf('site:autopilot:env-sync %s', $this->getSiteName()));
        $this->assertEquals('enabled', $output);

        // Get "disabled" environment syncing setting value.
        $this->setRequestMockPayload(
            [
                'status_code' => 200,
                'data' => ['cloneContent' => ['enabled' => false]],
            ],
            'settings',
            []
        );
        $output = $this->terminus(sprintf('site:autopilot:env-sync %s', $this->getSiteName()));
        $this->assertEquals('disabled', $output);

        // Run the command for a non-existing site.
        $non_existing_site_name = 'some-non-existing-site-12345';
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync %s', $non_existing_site_name),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString(
            sprintf('Could not locate a site your user may access identified by %s', $non_existing_site_name),
            $output
        );

        // Run the command for a non-200 status from API.
        $this->setRequestMockPayload(
            [
                'status_code' => 500,
                'status_code_reason' => 'server error',
            ],
            'settings',
            []
        );
        $output = $this->terminus(
            sprintf('site:autopilot:env-sync %s', $this->getSiteName()),
            ['2>&1'],
            false
        );
        $this->assertStringContainsString('Failed requesting Autopilot API: server error', $output);
    }
}
