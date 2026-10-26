<?php

namespace Database\Factories;

use App\Models\SafetyReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SafetyReport>
 */
class SafetyReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_number' => 'SH-'.fake()->unique()->numerify('####-######'),
            'reporter_name' => fake()->name(),
            'employee_number' => fake()->numerify('#####'),
            'section' => fake()->word(),
            'location' => fake()->address(),
            'hazard_description' => fake()->sentence(),
            'reported_to_supervisor' => fake()->boolean(),
            'ky_created' => fake()->boolean(),
            'initial_image_path' => 'safety-reports/originals/test.jpg',
            'status' => 'awaiting_pic',
        ];
    }
}
