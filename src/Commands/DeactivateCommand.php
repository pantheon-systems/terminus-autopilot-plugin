<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

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
        // @todo: remove warning once API implemented and tested.
        $this->log()->warning('This command is not yet implemented or tested!');

        $site = $this->getSite($site_id);

        try {
            $this->getClient()->deactivate($site->id);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot did not successfully activate: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return;
        }

        $this->log()->success('Autopilot is deactivated.');
    }
}
