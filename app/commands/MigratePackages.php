<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigratePackages extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialise all packages migrations defined in this classes fire method.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('migrate', array('--package' => 'cartalyst/sentry'));
    }
}
