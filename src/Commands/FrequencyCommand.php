<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;
use Pantheon\Terminus\Exceptions\TerminusException;

/**
 * Class FrequencyCommand.
 */
class FrequencyCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Get or set Autopilot run frequency with which autopilot update
     * cycles are run. Valid options are: manual, monthly, weekly, daily.
     *
     * @command site:autopilot:frequency
     * @aliases ap-frequency
     * @authorize
     * @filter-output
     *
     * @usage <site_id> Get Autopilot run frequency
     * @usage <site_id> <frequency> Set Autopilot run frequency
     *
     * @param string $site_id Site name
     * @param string|null $frequency Autopilot run frequency.
     *   Available options: manual, monthly, weekly, daily.
     *
     * @return string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function frequency(string $site_id, string $frequency = null): ?string
    {
        $site = $this->getSite($site_id);

        if (null === $frequency) {
            return $this->getClient()->getFrequency($site->id);
        }

        try {
            $this->getClient()->setFrequency($site->id, $frequency);
        } catch (\Throwable $t) {
            throw new TerminusException(
                'Error updating frequency: {error_message}',
                ['error_message' => $t->getMessage()]
            );
        }

        $this->log()->success(
            'Autopilot frequency updated to {frequency}.',
            ['frequency' => $frequency]
        );

        return null;
    }
}
