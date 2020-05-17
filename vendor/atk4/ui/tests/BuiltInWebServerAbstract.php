<?php

namespace atk4\ui\tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Process\Process;

abstract class BuiltInWebServerAbstract extends TestCase
{
    protected static $process;

    protected static $host = '127.0.0.1';
    protected static $port = 9687;

    /** @var bool set the app->call_exit in demo */
    protected static $app_def_call_exit = true;

    /** @var bool set the app->caught_exception in demo */
    protected static $app_def_caught_exception = true;

    protected static $webserver_root = 'demos/';

    public static function setUpBeforeClass()
    {
        if (!file_exists($coverage = self::getPackagePath('coverage'))) {
            mkdir($coverage, 0777, true);
        }

        if (!file_exists($demosCoverage = self::getPackagePath('demos', 'coverage.php'))) {
            file_put_contents(
                $demosCoverage,
                file_get_contents(self::getPackagePath('tools', 'coverage.php'))
            );
        }

        // The command to spin up the server
        self::$process = Process::fromShellCommandline('php -S ' . self::$host . ':' . self::$port . ' -t ' . self::getPackagePath());

        // Disabling the output, otherwise the process might hang after too much output
        self::$process->disableOutput();
        // Actually execute the command and start the process

        self::$process->start();

        sleep(1);
    }

    public static function tearDownAfterClass()
    {
        if (file_exists($file = self::getPackagePath('demos', 'coverage.php'))) {
            unlink($file);
        }
    }

    /**
     * Generates absolute file or directory path based on package root directory
     * Returns absolute path to package root durectory if no arguments
     *
     * @param string $directory
     * @param string $_
     *
     * @return string
     */
    private static function getPackagePath($directory = null, $_ = null): string
    {
        $route = func_get_args();

        $baseDir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');

        array_unshift($route, $baseDir);

        return implode(DIRECTORY_SEPARATOR, $route);
    }

    private function getClient(): Client
    {
        // Creating a Guzzle Client with the base_uri, so we can use a relative
        // path for the requests.
        return new Client(['base_uri' => 'http://localhost:' . self::$port]);
    }

    protected function getResponseFromRequestFormPOST($path, $data): ResponseInterface
    {
        return $this->getClient()->request('POST', $this->getPathWithAppVars($path), ['form_params' => $data]);
    }

    protected function getResponseFromRequestGET($path): ResponseInterface
    {
        return $this->getClient()->request('GET', $this->getPathWithAppVars($path));
    }

    private function getPathWithAppVars($path)
    {
        $path .= strpos($path, '?') === false ? '?' : '&';
        $path .= 'APP_CALL_EXIT=' . ((int) static::$app_def_call_exit) . '&APP_CATCH_EXCEPTIONS=' . ((int) static::$app_def_caught_exception);

        return self::$webserver_root . $path;
    }
}
