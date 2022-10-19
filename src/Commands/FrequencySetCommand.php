<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class FrequencySetCommand.
 */
class FrequencySetCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Get or set Autopilot run frequency.
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
     *   Available options: manual, monthly, weekly.
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
            return $this->getClient()->getFrequency($site_id);
        }

        try {
            $this->getClient()->setFrequency($site->id, $frequency);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot frequency did not successfully update: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return null;
        }

        $this->log()->success(
            'Autopilot frequency updated to {frequency}.',
            ['frequency' => $frequency]
        );

        return null;
    }
}
