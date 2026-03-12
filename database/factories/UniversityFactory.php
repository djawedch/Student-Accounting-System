<?php
namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
{
    protected $model = University::class;

    public function definition()
    {
        $universities = [
            ['name' => 'Université des Sciences et de la Technologie Houari Boumediene', 'city' => 'Alger'],
            ['name' => 'Université d\'Alger 1 Benyoucef Benkhedda', 'city' => 'Alger'],
            ['name' => 'Université Constantine 1', 'city' => 'Constantine'],
            ['name' => 'Université Constantine 3', 'city' => 'Constantine'],
            ['name' => 'Université Abdelhamid Ibn Badis', 'city' => 'Mostaganem'],
            ['name' => 'Université Ahmed Ben Bella', 'city' => 'Oran'],
            ['name' => 'Université des Sciences et de la Technologie d\'Oran', 'city' => 'Oran'],
            ['name' => 'Université Abou Bekr Belkaid', 'city' => 'Tlemcen'],
            ['name' => 'Université Ferhat Abbas', 'city' => 'Sétif'],
            ['name' => 'Université Badji Mokhtar', 'city' => 'Annaba'],
            ['name' => 'Université Djillali Liabes', 'city' => 'Sidi Bel Abbès'],
            ['name' => 'Université Ibn Khaldoun', 'city' => 'Tiaret'],
            ['name' => 'Université Ziane Achour', 'city' => 'Djelfa'],
            ['name' => 'Université Mohamed Boudiaf', 'city' => 'M\'Sila'],
            ['name' => 'Université Larbi Ben M\'Hidi', 'city' => 'Oum El Bouaghi'],
            ['name' => 'Université Kasdi Merbah', 'city' => 'Ouargla'],
            ['name' => 'Université Tahri Mohamed', 'city' => 'Béchar'],
            ['name' => 'Université Amar Telidji', 'city' => 'Laghouat'],
            ['name' => 'Université Mohamed Lamine Debaghine', 'city' => 'Sétif'],
            ['name' => 'Université Akli Mohand Oulhadj', 'city' => 'Bouira'],
        ];

        $data = fake()->unique()->randomElement($universities);

        return [
            'name'       => $data['name'],
            'city'       => $data['city'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}