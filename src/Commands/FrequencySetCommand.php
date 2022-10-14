<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Request\RequestAwareTrait;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\Autopilot\AutopilotAwareTrait;

/**
 * Autopilot status check.
 */
class FrequencySetCommand extends AutopilotCommandBase implements RequestAwareInterface
{

    use AutopilotAwareTrait;
    use RequestAwareTrait;
    use SiteAwareTrait;

    /**
     * Set Autopilot run frequency for a specific site.
     *
     * @authorize
     * @filter-output
     *
     * @command autopilot:frequency:set
     * @aliases ap-frequency-set
     *
     * @param string $site_id Long form site ID.
     * @param string $frequency Frequency for Terminus to run.
     *  Available options: MANUAL, MONTHLY, WEEKLY, DAILY.
     * @param array $options
     *
     * @return mixed
     * @throws \Pantheon\Terminus\Exceptions\TerminusException|\GuzzleHttp\Exception\GuzzleException
     */
    public function autopilotFrequencySet(
        string $site_id,
        string $frequency,
        array $options = ['debug' => false]
    ) {

        // Allow frequency to be case-insensitive for user friendliness.
        $frequency = strtoupper($frequency);

        // The API will not automatically reject invalid frequencies, so
        // it's best to validate here.
        if (!in_array($frequency, ['MANUAL', 'MONTHLY', 'WEEKLY', 'DAILY'])) {
            $error_message = sprintf('%s is not a valid frequency.', $frequency);
            $this->log()->error($error_message . ' Valid options are: MANUAL, MONTHLY, WEEKLY, or DAILY.');
            return false;
        }

        $url = sprintf('%s/sites/%s/vrt/settings', $this->apiClient->baseUri, $site_id);
        $session = $this->request()->session()->get('session');
        $request_body = ["updateFrequency" => $frequency];

        $request_options = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $session,
            ],
            'json' => $request_body,
            'method' => 'POST',
            'verify' => false, // @todo Remove post-EA, once service is using trusted cert
        ];

        $result = $this->request()->request($url, $request_options);
        if ($result->isError()) {
            $default_error_message = 'Autopilot frequency did not successfully update.';
            $reason = sprintf('Reason: %s', trim($result->getData()));
            $this->log()->error($default_error_message . ' ' . $reason);

            return false;
        } else {
            $this->log()->success("Autopilot frequency updated to $frequency.");

            return true;
        }
    }
}
