<?php

namespace JOOservices\XClient\Commands;

use Illuminate\Console\Command;

class XClientCommand extends Command
{
    public $signature = 'xclient';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
