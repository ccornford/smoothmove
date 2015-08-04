<?php
class SentryGroupSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->delete();
        Sentry::getGroupProvider()->create(array(
            'name' => 'Users',
            'permissions' => array(
                'user'          => 1,
                'user.question' => 1,
                'manager'       => 0,
                'admin'         => 0,
            )
        ));
        Sentry::getGroupProvider()->create(array(
            'name' => 'Property Managers',
            'permissions' => array(
                'user'          => 1,
                'user.question' => 0,
                'manager'       => 1,
                'admin'         => 0,
            )
        ));
        Sentry::getGroupProvider()->create(array(
            'name' => 'Admins',
            'permissions' => array(
                'user'          => 1,
                'manager'       => 1,
                'admin'         => 1,
            )
        ));

    }
}
