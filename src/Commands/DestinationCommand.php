<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class DestinationSetCommand.
 */
class DestinationCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Get or set Autopilot destination environment.
     *
     * @command site:autopilot:destination
     * @aliases ap-destination
     * @authorize
     * @filter-output
     *
     * @usage <site_id> Get Autopilot destination environment
     * @usage <site_id> <destination> Set Autopilot destination environment
     *
     * @param string $site_id Site name
     * @param string|null $destination The destination environment.
     *   Available options: dev, test, live.
     *
     * @return string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function destination(string $site_id, string $destination = null): ?string
    {
        $site = $this->getSite($site_id);

        if (null === $destination) {
            return $this->getClient()->getDestination($site->id);
        }

        try {
            $this->getClient()->setDestination($site->id, $destination);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot destination did not successfully update: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return null;
        }

        $this->log()->success(
            'Autopilot destination updated to {destination}.',
            ['destination' => $destination]
        );

        return null;
    }
}
