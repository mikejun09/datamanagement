<?php

namespace Database\Factories;

use App\Models\MasterList;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterListFactory extends Factory
{
    protected $model = MasterList::class;

    public function definition()
    {
        return [
            'precinct' => $this->faker->word,
            'name' => $this->faker->name,
            'address' => $this->faker->address,
        ];
    }
}
