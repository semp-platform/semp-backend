<?php

namespace Database\Seeders\Reference;


use Illuminate\Database\Seeder;
use App\Models\Reference\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [

            ['name' => 'Abia', 'code' => 'ABI', 'capital' => 'Umuahia', 'geopolitical_zone' => 'South East'],
            ['name' => 'Adamawa', 'code' => 'ADA', 'capital' => 'Yola', 'geopolitical_zone' => 'North East'],
            ['name' => 'Akwa Ibom', 'code' => 'AKW', 'capital' => 'Uyo', 'geopolitical_zone' => 'South South'],
            ['name' => 'Anambra', 'code' => 'ANA', 'capital' => 'Awka', 'geopolitical_zone' => 'South East'],
            ['name' => 'Bauchi', 'code' => 'BAU', 'capital' => 'Bauchi', 'geopolitical_zone' => 'North East'],
            ['name' => 'Bayelsa', 'code' => 'BAY', 'capital' => 'Yenagoa', 'geopolitical_zone' => 'South South'],
            ['name' => 'Benue', 'code' => 'BEN', 'capital' => 'Makurdi', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Borno', 'code' => 'BOR', 'capital' => 'Maiduguri', 'geopolitical_zone' => 'North East'],
            ['name' => 'Cross River', 'code' => 'CRS', 'capital' => 'Calabar', 'geopolitical_zone' => 'South South'],
            ['name' => 'Delta', 'code' => 'DEL', 'capital' => 'Asaba', 'geopolitical_zone' => 'South South'],
            ['name' => 'Ebonyi', 'code' => 'EBO', 'capital' => 'Abakaliki', 'geopolitical_zone' => 'South East'],
            ['name' => 'Edo', 'code' => 'EDO', 'capital' => 'Benin City', 'geopolitical_zone' => 'South South'],
            ['name' => 'Ekiti', 'code' => 'EKT', 'capital' => 'Ado-Ekiti', 'geopolitical_zone' => 'South West'],
            ['name' => 'Enugu', 'code' => 'ENU', 'capital' => 'Enugu', 'geopolitical_zone' => 'South East'],
            ['name' => 'Federal Capital Territory', 'code' => 'FCT', 'capital' => 'Abuja', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Gombe', 'code' => 'GOM', 'capital' => 'Gombe', 'geopolitical_zone' => 'North East'],
            ['name' => 'Imo', 'code' => 'IMO', 'capital' => 'Owerri', 'geopolitical_zone' => 'South East'],
            ['name' => 'Jigawa', 'code' => 'JIG', 'capital' => 'Dutse', 'geopolitical_zone' => 'North West'],
            ['name' => 'Kaduna', 'code' => 'KAD', 'capital' => 'Kaduna', 'geopolitical_zone' => 'North West'],
            ['name' => 'Kano', 'code' => 'KAN', 'capital' => 'Kano', 'geopolitical_zone' => 'North West'],
            ['name' => 'Katsina', 'code' => 'KAT', 'capital' => 'Katsina', 'geopolitical_zone' => 'North West'],
            ['name' => 'Kebbi', 'code' => 'KEB', 'capital' => 'Birnin Kebbi', 'geopolitical_zone' => 'North West'],
            ['name' => 'Kogi', 'code' => 'KOG', 'capital' => 'Lokoja', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Kwara', 'code' => 'KWA', 'capital' => 'Ilorin', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Lagos', 'code' => 'LAG', 'capital' => 'Ikeja', 'geopolitical_zone' => 'South West'],
            ['name' => 'Nasarawa', 'code' => 'NAS', 'capital' => 'Lafia', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Niger', 'code' => 'NIG', 'capital' => 'Minna', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Ogun', 'code' => 'OGN', 'capital' => 'Abeokuta', 'geopolitical_zone' => 'South West'],
            ['name' => 'Ondo', 'code' => 'OND', 'capital' => 'Akure', 'geopolitical_zone' => 'South West'],
            ['name' => 'Osun', 'code' => 'OSU', 'capital' => 'Osogbo', 'geopolitical_zone' => 'South West'],
            ['name' => 'Oyo', 'code' => 'OYO', 'capital' => 'Ibadan', 'geopolitical_zone' => 'South West'],
            ['name' => 'Plateau', 'code' => 'PLA', 'capital' => 'Jos', 'geopolitical_zone' => 'North Central'],
            ['name' => 'Rivers', 'code' => 'RIV', 'capital' => 'Port Harcourt', 'geopolitical_zone' => 'South South'],
            ['name' => 'Sokoto', 'code' => 'SOK', 'capital' => 'Sokoto', 'geopolitical_zone' => 'North West'],
            ['name' => 'Taraba', 'code' => 'TAR', 'capital' => 'Jalingo', 'geopolitical_zone' => 'North East'],
            ['name' => 'Yobe', 'code' => 'YOB', 'capital' => 'Damaturu', 'geopolitical_zone' => 'North East'],
            ['name' => 'Zamfara', 'code' => 'ZAM', 'capital' => 'Gusau', 'geopolitical_zone' => 'North West'],

        ];

       foreach ($states as $state) {

    State::updateOrCreate(

        [
            'code' => $state['code'],
        ],

        [
            'name' => $state['name'],
            'capital' => $state['capital'],
            'geopolitical_zone' => $state['geopolitical_zone'],
            'is_active' => true,
        ]

    );

}
    }
}

