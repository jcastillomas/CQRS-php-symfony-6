<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Cli;

use App\Tests\Integration\Application\Shared\ApplicationTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class AbstractConsoleTestCase extends ApplicationTestCase
{
    final protected function app(Command $command, string $alias): CommandTester
    {
        $kernel = self::getKernel();
        $application = new Application($kernel);
        $application->add($command);

        $command = $application->find($alias);

        return new CommandTester($command);
    }
}
