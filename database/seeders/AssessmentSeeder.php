<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assessment = Assessment::create([
            'title' => 'Customer Satisfaction Survey',
            'description' => 'Please help us improve our services by providing your honest feedback',
            'is_active' => true
        ]);

        $questions = [
            'How would you rate the quality of our products?',
            'How satisfied are you with our customer service?',
            'How likely are you to recommend us to others?',
            'How would you rate the value for money?',
            'How satisfied are you with our delivery speed?'
        ];

        foreach ($questions as $index => $questionText) {
            Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => $questionText,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}
