<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;
use Pantheon\Terminus\Exceptions\TerminusException;

/**
 * Class DeactivateCommand.
 */
class DeactivateCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Deactivate Autopilot for a given site ID or site name.
     *
     * @command site:autopilot:deactivate
     * @aliases ap-deactivate
     * @authorize
     * @filter-output
     *
     * @param string $site_id Site name
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function deactivate(string $site_id): void
    {
        $site = $this->getSiteById($site_id);

        try {
            $this->getClient()->deactivate($site->id);
        } catch (\Throwable $t) {
            throw new TerminusException(
                'Error deactivating Autopilot: {error_message}',
                ['error_message' => $t->getMessage()]
            );
        }

        $this->log()->success('Autopilot is deactivated.');
    }
}
