<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'serve')]
class ServeWithUploadLimitsCommand extends ServeCommand
{
    protected function serverCommand(): array
    {
        $command = parent::serverCommand();

        array_splice($command, 1, 0, [
            '-d',
            'upload_max_filesize=10M',
            '-d',
            'post_max_size=12M',
        ]);

        return $command;
    }
}
