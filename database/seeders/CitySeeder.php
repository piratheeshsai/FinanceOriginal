<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Colombo',
            'Kandy',
            'Galle',
            'Jaffna',
            'Negombo',
            'Anuradhapura',
            'Ratnapura',
            'Trincomalee',
            'Batticaloa',
            'Kurunegala',
            'Matara',
            'Nuwara Eliya',
            'Badulla',
            'Ampara',
            'Polonnaruwa',
            'Kalutara',
            'Kalmunai',
            'Karativu',
            'Kaluvanchikudy',
            'Kallady',
            'Eravur',
            'Valachenai',
            'Oddamavadi',
            'Maruthamunai',
            'Sammanthurai',
            'Arayampathy',
            'Chenkalady',
            'Kiran',
            'Kokkaddicholai',
            'Vavunathivu',
            'Vellaveli',
            'Kattankudy',
        ];

        foreach ($cities as $city) {
            City::create(['name' => $city]); // Ensure 'name' is fillable in the model
        }
    }
}
