<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SystemUp extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'system:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate & seed custom DB, create groups and assign permissions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->migrateDB();
    }

    public function migrateDB()
    {
        $this->call('migrate', array('--seed' => 'true'));
    }

}
