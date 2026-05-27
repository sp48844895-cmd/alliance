<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

class ServeCommand extends BaseServeCommand
{
    protected function serverCommand()
    {
        $command = parent::serverCommand();
        $ini = base_path('php.ini');

        $flags = ['-d', 'upload_max_filesize=64M', '-d', 'post_max_size=68M'];

        if (is_file($ini)) {
            $flags = array_merge(['-c', $ini], $flags);
        }

        array_splice($command, 1, 0, $flags);

        return $command;
    }
}
