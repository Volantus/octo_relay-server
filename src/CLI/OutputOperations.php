<?php
namespace Volante\SkyBukkit\RelayServer\Src\CLI;

use Carbon\Carbon;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputOperations
 * @package Volante\SkyBukkit\RelayServer\Src\CLI
 */
trait OutputOperations
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param string $topic
     * @param string $message
     */
    protected function writeInfoLine(string $topic, string $message)
    {
        $this->output->writeln('[<fg=blue>' . $this->currentTime() . '</>] [<fg=cyan;options=bold>'. $topic . '</>] ' . $message);
    }

    /**
     * @param string $topic
     * @param string $message
     */
    protected function writeErrorLine(string $topic, string $message)
    {
        $this->output->writeln('[<fg=blue>' . $this->currentTime() . '</>] [<fg=cyan;options=bold>'. $topic . '</>] <error>' . $message . '</error>');
    }

    /**
     * @return string
     */
    protected function currentTime() : string
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }
}