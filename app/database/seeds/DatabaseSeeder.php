<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('SentryGroupSeeder');
        $this->command->info('Groups Seeded!');

        $this->call('SentryUserSeeder');
        $this->command->info('User table seeded!');
        $this->command->info('Properties tables seeded!');

        $this->call('SentryUserGroupSeeder');
        $this->command->info('User Groups Seeded!');

    }

}
