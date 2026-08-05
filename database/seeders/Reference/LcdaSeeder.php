<?php

namespace Database\Seeders\Reference;

use Illuminate\Database\Seeder;
use App\Models\Reference\Lcda;
use App\Models\Reference\Lga;

class LcdaSeeder extends Seeder
{
    public function run(): void
    {
        $lcdas = [

            ['name' => 'Abeokuta North West LCDA', 'lga' => 'Abeokuta North'],
            ['name' => 'Abeokuta North East LCDA', 'lga' => 'Abeokuta North'],
            ['name' => 'Oke-Ogun LCDA', 'lga' => 'Abeokuta North'],

            ['name' => 'Abeokuta South East LCDA', 'lga' => 'Abeokuta South'],
            ['name' => 'Abeokuta South West LCDA', 'lga' => 'Abeokuta South'],

            ['name' => 'Ado-Odo LCDA', 'lga' => 'Ado Odo-Ota'],
['name' => 'Agbara/Igbesa LCDA', 'lga' => 'Ado Odo-Ota'],
['name' => 'Ota West LCDA', 'lga' => 'Ado Odo-Ota'],
['name' => 'Sango/Ijoko LCDA', 'lga' => 'Ado Odo-Ota'],
            ['name' => 'Ewekoro North LCDA', 'lga' => 'Ewekoro'],

            ['name' => 'Ifo Central LCDA', 'lga' => 'Ifo'],
            ['name' => 'Coker Ibogun LCDA', 'lga' => 'Ifo'],
            ['name' => 'Ifo South LCDA', 'lga' => 'Ifo'],

            ['name' => 'Ijebu East Central LCDA', 'lga' => 'Ijebu East'],

            ['name' => 'Ijebu North Central LCDA', 'lga' => 'Ijebu North'],
            ['name' => 'Ijebu Igbo West LCDA', 'lga' => 'Ijebu North'],
            ['name' => 'Ago-Iwoye LCDA', 'lga' => 'Ijebu North'],

            ['name' => 'Yemoji LCDA', 'lga' => 'Ijebu North-East'],

           ['name' => 'Ijebu Ode South LCDA', 'lga' => 'Ijebu-Ode'],

            ['name' => 'Remo Central LCDA', 'lga' => 'Ikenne'],

            ['name' => 'Afon LCDA', 'lga' => 'Imeko-Afon'],

            ['name' => 'Ipokia West LCDA', 'lga' => 'Ipokia'],
            ['name' => 'Idi Iroko LCDA', 'lga' => 'Ipokia'],

            ['name' => 'Oba LCDA', 'lga' => 'Obafemi-Owode'],
['name' => 'Obafemi LCDA', 'lga' => 'Obafemi-Owode'],
['name' => 'Ofada/Mokoloki LCDA', 'lga' => 'Obafemi-Owode'],

            ['name' => 'Opeji LCDA', 'lga' => 'Odeda'],
            ['name' => 'Ilugun LCDA', 'lga' => 'Odeda'],

            ['name' => 'Leguru LCDA', 'lga' => 'Odogbolu'],
            ['name' => 'Ifesowapo LCDA', 'lga' => 'Odogbolu'],

            ['name' => 'Ogun Waterside East LCDA', 'lga' => 'Ogun Waterside'],

            ['name' => 'Remo North East LCDA', 'lga' => 'Remo North'],

            ['name' => 'Sagamu Remo West LCDA', 'lga' => 'Shagamu'],
['name' => 'Sagamu Remo South LCDA', 'lga' => 'Shagamu'],

            ['name' => 'Iju LCDA', 'lga' => 'Yewa North'],
            ['name' => 'Ketu LCDA', 'lga' => 'Yewa North'],

            ['name' => 'Yewa South East LCDA', 'lga' => 'Yewa South'],
        ];

        foreach ($lcdas as $lcda) {

            $lga = Lga::where('name', $lcda['lga'])->first();

            if (! $lga) {
                $this->command->warn(
                    "LGA '{$lcda['lga']}' not found. Skipping {$lcda['name']}."
                );

                continue;
            }

            Lcda::updateOrCreate(
                [
                    'lga_id' => $lga->id,
                    'name'   => $lcda['name'],
                ],
                [
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('LCDA seeding completed successfully.');
    }
}
