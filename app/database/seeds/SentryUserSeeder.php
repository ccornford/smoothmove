<?php
class SentryUserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $admin = Sentry::getUserProvider()->create(array(
            'email'         => 'chris@smoothlettings.com',
            'password'      => 'Chrissmooth42',
            'activated'     => 1,
            'first_name'    => 'Chris',
            'last_name'     => 'Cornford',
            'phone'         => '07544964840',
            'public_phone'  => '028 4362 2608',
            'public_email'  => 'enquiries@smoothlettings.com',
            'street'        => '47 Durham Road',
            'town'          => 'Gateshead',
            'county'        => 'Tyne & Wear',
            'postcode'      => 'NE9 79Z'
        ));

        $user = Sentry::getUserProvider()->create(array(
            'email'         => 'joe@example.com',
            'password'      => 'Joeexample56',
            'activated'     => 1,
            'first_name'    => 'Joe',
            'last_name'     => 'Bloggs',
            'phone'         => '07544893426'
        ));

        $this->command->info('Users seeded!');



        DB::table('property_types')->delete();

        $semi = PropertyType::create(array(
            'name'  => 'Semi-detached',
        ));
        $detached = PropertyType::create(array(
            'name'  => 'Detached',
        ));
        $flat = PropertyType::create(array(
            'name'  => 'Flat',
        ));
        PropertyType::create(array(
            'name'  => 'Bungalow',
        ));

        $this->command->info('Property Types seeded!');

        DB::table('property_furnished')->delete();

        PropertyFurnished::create(array(
            'name'  => 'Furnished',
        ));
        PropertyFurnished::create(array(
            'name'  => 'Unfurnished',
        ));
        PropertyFurnished::create(array(
            'name'  => 'Part-furnished',
        ));

        $this->command->info('Property Furnished seeded!');




        DB::table('property_parking')->delete();

        PropertyParking::create(array(
            'name'  => 'On street',
        ));
        PropertyParking::create(array(
            'name'  => 'Off street',
        ));
        PropertyParking::create(array(
            'name'  => 'Garage',
        ));
        PropertyParking::create(array(
            'name'  => 'Allocated parking',
        ));

        $this->command->info('Property Parking seeded!');



        DB::table('property_garden')->delete();

        PropertyGarden::create(array(
            'name'  => 'No garden',
        ));
        PropertyGarden::create(array(
            'name'  => 'small garden',
        ));
        PropertyGarden::create(array(
            'name'  => 'Large garden',
        ));
        PropertyGarden::create(array(
            'name'  => 'Yard',
        ));
        PropertyGarden::create(array(
            'name'  => 'Shared garden',
        ));

        $this->command->info('Property Garden seeded!');


        

        DB::table('properties')->delete();

        $property1 = Property::create(array(
            'type_id'       => $semi->id,
            'price'         => 400.00,
            'bedrooms'      => 3,
            'description'   => 'This is a description',
            'furnished_id'  => 1,
            'street'        => 'Northumberland Street',
            'town'          => 'Newcastle',
            'county'        => 'Tyne & Wear',
            'postcode'      => 'NE1 6PZ',
            'longitude'     => '-1.5999534130096',
            'latitude'      => '54.97370166282365',
            'garden_id'     => 3,
            'parking_id'    => 1,
            'user_id'       => $admin->id,
        ));
        $property2 = Property::create(array(
            'type_id'       => $detached->id,
            'price'         => 550.00,
            'bedrooms'      => 2,
            'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sed blandit est, ac tincidunt neque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla suscipit massa non nisl placerat, non molestie mauris tempor. Phasellus sollicitudin, arcu quis feugiat pharetra, velit velit pretium mi, at eleifend est mauris at est. Phasellus tristique sem at nulla molestie volutpat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas purus odio, malesuada at vestibulum quis, mollis sit amet orci. Donec et neque ac arcu cursus commodo. In elementum ac dui vitae posuere. Morbi eget magna et justo pellentesque ultrices a nec risus. Nunc et consequat nisl, eu sollicitudin lectus.

Cras commodo, magna ut rhoncus ultrices, neque enim consequat lorem, ac lacinia dui massa eget enim. Fusce euismod molestie sem, in varius est dignissim luctus. Pellentesque id ex sagittis, sagittis est vel, euismod eros. Nulla eget lacus quis est pretium blandit eget non neque. Duis ultrices felis ut feugiat dictum. Vivamus tincidunt aliquet augue vitae bibendum. Mauris fermentum id purus at dictum. Nam sagittis mauris eget nunc aliquam, vel blandit erat efficitur. Etiam rhoncus leo lectus, at tincidunt lectus malesuada vel. Donec finibus lacus a justo semper pretium. Donec sodales sem non accumsan lobortis. Suspendisse quis placerat metus, nec pretium neque.

Suspendisse potenti. Quisque vel velit sit amet velit volutpat fermentum. Nam fermentum feugiat ligula eget mollis. In at elit felis. Pellentesque faucibus aliquet nulla, non commodo felis mattis vel. Donec a mattis turpis, nec placerat turpis. Cras sed diam bibendum, imperdiet nisl a, placerat quam. Ut sed rhoncus neque, in ultrices dui. Sed sodales elit nec elementum suscipit. Ut in vestibulum sem, eget sollicitudin nibh. Aenean commodo nunc nec nisi dignissim consectetur. Vestibulum in gravida ligula.',
            'furnished_id'  => 1,
            'street'        => 'Old Durham Road',
            'town'          => 'Gateshead',
            'county'        => 'Tyne & Wear',
            'postcode'      => 'NE9 5LB',
            'longitude'     => '-1.59178390',
            'latitude'      => '54.94720860',
            'garden_id'     => 2,
            'parking_id'    => 3,
            'user_id'       => $admin->id,
        ));

        $property3 = Property::create(array(
            'type_id'       => $flat->id,
            'price'         => 300.00,
            'bedrooms'      => 1,
            'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sed blandit est, ac tincidunt neque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla suscipit massa non nisl placerat, non molestie mauris tempor. Phasellus sollicitudin, arcu quis feugiat pharetra, velit velit pretium mi, at eleifend est mauris at est. Phasellus tristique sem at nulla molestie volutpat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas purus odio, malesuada at vestibulum quis, mollis sit amet orci. Donec et neque ac arcu cursus commodo. In elementum ac dui vitae posuere. Morbi eget magna et justo pellentesque ultrices a nec risus. Nunc et consequat nisl, eu sollicitudin lectus.

Cras commodo, magna ut rhoncus ultrices, neque enim consequat lorem, ac lacinia dui massa eget enim. Fusce euismod molestie sem, in varius est dignissim luctus. Pellentesque id ex sagittis, sagittis est vel, euismod eros. Nulla eget lacus quis est pretium blandit eget non neque. Duis ultrices felis ut feugiat dictum. Vivamus tincidunt aliquet augue vitae bibendum. Mauris fermentum id purus at dictum. Nam sagittis mauris eget nunc aliquam, vel blandit erat efficitur. Etiam rhoncus leo lectus, at tincidunt lectus malesuada vel. Donec finibus lacus a justo semper pretium. Donec sodales sem non accumsan lobortis. Suspendisse quis placerat metus, nec pretium neque.

Suspendisse potenti. Quisque vel velit sit amet velit volutpat fermentum. Nam fermentum feugiat ligula eget mollis. In at elit felis. Pellentesque faucibus aliquet nulla, non commodo felis mattis vel. Donec a mattis turpis, nec placerat turpis. Cras sed diam bibendum, imperdiet nisl a, placerat quam. Ut sed rhoncus neque, in ultrices dui. Sed sodales elit nec elementum suscipit. Ut in vestibulum sem, eget sollicitudin nibh. Aenean commodo nunc nec nisi dignissim consectetur. Vestibulum in gravida ligula.',
            'furnished_id'  => 1,
            'street'        => 'Addington Cres',
            'town'          => 'North Shields',
            'county'        => 'Tyne & Wear',
            'postcode'      => 'NE29 7PZ',
            'longitude'     => '-1.465708732604',
            'latitude'      => '55.0116197539058',
            'garden_id'     => 0,
            'parking_id'    => 1,
            'user_id'       => $admin->id,
        ));

        $this->command->info('Properties seeded');



        DB::table('property_features')->delete();

        PropertyFeature::create(array(
            'prop_id'   => 1,
            'name'      => 'Tripple glazing',
        ));
        PropertyFeature::create(array(
            'prop_id'   => 1,
            'name'      => 'Solar panels',
        ));
        PropertyFeature::create(array(
            'prop_id'   => 1,
            'name'      => 'Underfloor heating',
        ));
        PropertyFeature::create(array(
            'prop_id'   => 2,
            'name'      => 'Recently decorated',
        ));
        PropertyFeature::create(array(
            'prop_id'   => 2,
            'name'      => 'Quiet area',
        ));       
        PropertyFeature::create(array(
            'prop_id'   => 2,
            'name'      => 'Rear patio',
        ));           
        PropertyFeature::create(array(
            'prop_id'   => 2,
            'name'      => 'Pets allowed',
        ));        
        PropertyFeature::create(array(
            'prop_id'   => 3,
            'name'      => 'Downstairs bathroom',
        ));
        PropertyFeature::create(array(
            'prop_id'   => 3,
            'name'      => 'Three story',
        ));       
        PropertyFeature::create(array(
            'prop_id'   => 3,
            'name'      => '15 minute walk to Newcastle',
        ));

        $this->command->info('Property Features seeded!');

    }
}
