<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['address' => 'Amtola'],
            ['address' => 'Haripottri'],
            ['address' => 'Sodagorpottri'],
            ['address' => 'Jhalai Porttri'],
            ['address' => 'Jahaj Compani'],
            ['address' => 'Central Road'],
            ['address' => 'Jaman Market'],
            ['address' => 'Pauro Bazar'],
            ['address' => 'Super Market'],
            ['address' => 'Lalmonir Hat'],
            ['address' => 'Mostofi'],
            ['address' => 'Rajar Hat'],
            ['address' => 'Cawnia'],
            ['address' => 'Baili Brize'],
            ['address' => 'Mahiganj'],
            ['address' => 'Prigacha'],
            ['address' => 'Abdulpur'],
            ['address' => 'Tepa Modhupur'],
            ['address' => 'Pao Tana'],
            ['address' => 'Araji Gopal'],
            ['address' => 'Satdorga'],
            ['address' => 'Borodorga'],
            ['address' => 'Deoti'],
            ['address' => 'Soreyar Tol'],
            ['address' => 'Sothibari'],
            ['address' => 'Jaygir'],
            ['address' => 'Balar Hat'],
            ['address' => 'Mather Hat'],
            ['address' => 'Hulasu'],
            ['address' => 'Hajir hat'],
            ['address' => 'Mordan'],
            ['address' => 'Haragach'],
            ['address' => 'Cadkuti'],
            ['address' => 'Company'],
            ['address' => 'Kishorganj'],
            ['address' => 'Joldhaka'],
            ['address' => 'Hazirhat'],
            ['address' => 'Chowdhori Hat'],
            ['address' => 'Condoner Hat'],
            ['address' => 'Borovita'],
            ['address' => 'Paglapir'],
            ['address' => 'Tangonmari'],
            ['address' => 'Dimla'],
            ['address' => 'Boraibari'],
            ['address' => 'Gongachora'],
            ['address' => 'CO Bazar'],
            ['address' => 'Pakar Matha'],
            ['address' => 'Kakina'],
            ['address' => 'Tushbhander'],
            ['address' => 'Kaliganj'],
            ['address' => 'Vullarhat'],
            ['address' => 'Votmari'],
            ['address' => 'Chamtahat'],
            ['address' => 'Doikhaoya'],
            ['address' => 'Nowdabash'],
            ['address' => 'Hatibandha'],
            ['address' => 'Sirajul market'],
            ['address' => 'Mohipur'],
            ['address' => 'Gosghonta'],
            ['address' => 'Badorganj'],
            ['address' => 'Shekher Hat'],
            ['address' => 'Paikarer Hat'],
            ['address' => 'Shampur'],
            ['address' => 'Lahiri'],
            ['address' => 'Kant Bazar'],
            ['address' => 'Tarminal'],
            ['address' => 'Rampura'],
            ['address' => 'Lalbug'],
            ['address' => 'Station'],
            ['address' => 'Keranir Hat'],
            ['address' => 'Shotibari'],
            ['address' => 'KG Market'],
        ];

        foreach ($locations as $location) {
            Location::create(array_merge($location, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
