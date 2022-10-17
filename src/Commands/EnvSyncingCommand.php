<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class EnvSyncingCommand.
 */
class EnvSyncingCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Command to enable environment syncing.
     *
     * @command autopilot:env-sync:enable
     * @aliases ap-env-sync-en
     * @authorize
     * @filter-output
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function enableEnvSyncing(string $site_id): void
    {
        $site = $this->getSite($site_id);

        try {
            $this->getClient()->setEnvSyncing($site_id, true);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot environment syncing did not successfully enable: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return;
        }

        $this->log()->success('Autopilot environment syncing is enabled.');
    }

    /**
     * Command to disable environment syncing.
     *
     * @command autopilot:env-sync:disable
     * @aliases ap-env-sync-dis
     * @authorize
     * @filter-output
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function disableEnvSyncing(string $site_id): void
    {
        $site = $this->getSite($site_id);

        try {
            $this->getClient()->setEnvSyncing($site_id, false);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot environment syncing did not successfully disable: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return;
        }

        $this->log()->success('Autopilot environment syncing is disabled.');
    }
}
