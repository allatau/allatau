<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ComputingResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'port' => "2022",
            'host'   => "127.0.0.1",
            'template_type'   => "ssh_script",
            'login' => "sshuser",
            'password' => '', // password
            'public_key'   => "",
            'private_key'   => "",
//            'calculations_folder' => "",
        ];
    }
}
