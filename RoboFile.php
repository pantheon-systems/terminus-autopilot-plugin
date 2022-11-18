<?php

use Robo\Result;
use Robo\Tasks;

/**
 * Automate common housekeeping tasks.
 *
 * Class RoboFile
 */
class RoboFile extends Tasks
{

    const CI_ORG = "0238f947-88b4-4b63-b594-343b0fb25641";

    const SITENAME = "terminus-autopilot-testing";

    private function getTerminusExecutable(): string
    {
        return __DIR__ . "/vendor/bin/terminus";
    }


    /**
     * Log in with given machine token
     * @command terminus:auth-login
     *
     *
     */
    public function terminusAuthLogin(): Result
    {
        $command = sprintf('%s auth:login', $this->getTerminusExecutable());
        $token = getenv('TERMINUS_TOKEN');
        if (!empty($token)) {
            $command .= sprintf(
                " --machine-token='%s'",
                $token
            );
        }
        $this->stopOnFail(true);
        return $this->_exec($command);
    }


    /**
     * Install drupal using a profile.
     *
     * @param string $profile 'demo_umami'
     *
     * @throws \Exception
     */
    public function siteInstall(string $profile = 'demo_umami'): Result
    {
        $login = $this->terminusAuthLogin();
        if ($login->getExitCode() !== 0) {
            $this->writeln("Unable to login");
            exit(1);
        }
        $command = sprintf(
            "%s drush %s.dev -- site:install --account-name=admin --site-name=%s --locale=en --yes  %s",
            $this->getTerminusExecutable(),
            self::SITENAME,
            self::SITENAME,
            $profile,
            self::CI_ORG
        );
        $this->stopOnFail(true);
        return $this->_exec($command);
    }

    /**
     * @command test:set-up
     *
     */
    public function testSetUp()
    {
        $createResult = $this->testCreateSite();
        if ($createResult->getExitCode() != 0) {
            throw new \Exception("Create Test Site failed");
        }
        $siteInstallResult = $this->siteInstall();
        if ($createResult->getExitCode() != 0) {
            throw new \Exception("Create Test Site install failed");
        }
        $this->setupTerminus();
        if (!file_exists(dirname(__FILE__) . '/vendor/pantheon-systems/terminus/vendor/autoload.php')) {
            throw new \Exception("Unable to load terminus' autoload file.");
        }
    }

    /**
     * @command test:setup-terminus
     *
     */
    public function setupTerminus()
    {
        $root = dirname(__FILE__);
        $terminusFolder = sprintf("%s/vendor/pantheon-systems/terminus", $root);
        shell_exec(sprintf("cd %s && composer install", $terminusFolder));
    }

    /**
     * @command test:tear-down
     *
     */
    public function testTearDown() : Result
    {
        return $this->testDeleteSite();
    }

    /**
     * Ensure test site exists.
     *
     * @command test:create-site
     * @alias
     * @return void
     */
    public function testCreateSite() : Result
    {
        $infoCommand = sprintf(
            "%s site:info %s --field=id",
            $this->getTerminusExecutable(),
            self::SITENAME,
        );
        $result = $this->_exec($infoCommand);
        if ($result->getExitCode() !== 0) {
            $createCommand = sprintf(
                "%s site:create %s %s drupal9 --org=%s",
                $this->getTerminusExecutable(),
                self::SITENAME,
                self::SITENAME,
                self::CI_ORG
            );
            $this->_exec($createCommand);
            $result = $this->_exec($infoCommand);
        }
        return $result;
    }

    /**
     * @command test:delete-site
     * @return void
     */
    public function testDeleteSite(): Result
    {
        $infoCommand = sprintf(
            "%s site:delete %s --yes",
            $this->getTerminusExecutable(),
            self::SITENAME,
        );
        return $this->_exec($infoCommand);
    }



    /**
     * @return \Robo\Result
     */
    public function resetDependencies(): Result
    {
        $response = $this->confirm(
            "Are you sure you want to delete the vendor folder and all dependencies installed by composer?"
        );
        if ($response == true) {
            $this->_exec("composer clear-cache");
            return $this->taskComposerInstall(realpath(__DIR__));
        }
    }
}
