<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\LearningActivity;

class QuestionBankSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Seeding expanded question bank (300 questions across 12 learning sections)...');

        $grade1 = GradeLevel::where('name', 'grade_1')->first();
        $grade2 = GradeLevel::where('name', 'grade_2')->first();
        $grade3 = GradeLevel::where('name', 'grade_3')->first();

        $math = Subject::where('slug', 'math')->first();
        $english = Subject::where('slug', 'english')->first();
        $science = Subject::where('slug', 'science')->first();

        // Load data files
        $mathData = require __DIR__ . '/data/math_questions.php';
        $englishData = require __DIR__ . '/data/english_questions.php';
        $scienceData = require __DIR__ . '/data/science_questions.php';

        $activitiesMap = [
            // Math
            'g1_counting' => [
                'grade' => $grade1,
                'subject' => $math,
                'objective_topic' => 'Counting & Number Sense',
                'data' => $mathData['g1_counting'],
            ],
            'g1_addition' => [
                'grade' => $grade1,
                'subject' => $math,
                'objective_topic' => 'Simple Addition within 10',
                'data' => $mathData['g1_addition'],
            ],
            'g2_twodigit_addition' => [
                'grade' => $grade2,
                'subject' => $math,
                'objective_topic' => 'Two-Digit Addition',
                'data' => $mathData['g2_twodigit_addition'],
            ],
            'g2_odd_even' => [
                'grade' => $grade2,
                'subject' => $math,
                'objective_topic' => 'Odd & Even Sea Numbers',
                'data' => $mathData['g2_odd_even'],
            ],
            'g3_multiplication' => [
                'grade' => $grade3,
                'subject' => $math,
                'objective_topic' => 'Multiplication Tables (2, 5, 10)',
                'data' => $mathData['g3_multiplication'],
            ],
            'g3_fractions' => [
                'grade' => $grade3,
                'subject' => $math,
                'objective_topic' => 'Introduction to Fractions',
                'data' => $mathData['g3_fractions'],
            ],

            // English
            'g1_phonics' => [
                'grade' => $grade1,
                'subject' => $english,
                'objective_topic' => 'Phonics & Short Vowels',
                'data' => $englishData['g1_phonics'],
            ],
            'g2_grammar' => [
                'grade' => $grade2,
                'subject' => $english,
                'objective_topic' => 'Nouns, Verbs & Adjectives',
                'data' => $englishData['g2_grammar'],
            ],
            'g3_reading' => [
                'grade' => $grade3,
                'subject' => $english,
                'objective_topic' => 'Reading Comprehension & Inferences',
                'data' => $englishData['g3_reading'],
            ],

            // Science
            'g1_living' => [
                'grade' => $grade1,
                'subject' => $science,
                'objective_topic' => 'Living vs Non-Living',
                'data' => $scienceData['g1_living'],
            ],
            'g2_habitats' => [
                'grade' => $grade2,
                'subject' => $science,
                'objective_topic' => 'Ocean Habitats & Adaptations',
                'data' => $scienceData['g2_habitats'],
            ],
            'g3_watercycle' => [
                'grade' => $grade3,
                'subject' => $science,
                'objective_topic' => 'The Water Cycle & Weather',
                'data' => $scienceData['g3_watercycle'],
            ],
        ];

        foreach ($activitiesMap as $key => $config) {
            $grade = $config['grade'];
            $subject = $config['subject'];
            $objTopic = $config['objective_topic'];
            $sectionData = $config['data'];
            $actMeta = $sectionData['activity'];

            // Find matching objective
            $objective = LearningObjective::where('topic', $objTopic)
                ->where('grade_level_id', $grade->id)
                ->where('subject_id', $subject->id)
                ->first();

            // Create or update LearningActivity
            $activity = LearningActivity::updateOrCreate(
                ['title' => $actMeta['title']],
                [
                    'type' => $actMeta['type'],
                    'subject_id' => $subject->id,
                    'grade_level_id' => $grade->id,
                    'objective_id' => $objective?->id,
                    'difficulty' => $actMeta['difficulty'],
                    'instructions' => $actMeta['instructions'],
                    'max_points' => $actMeta['max_points'] ?? 100,
                    'retry_allowed' => true,
                    'max_attempts' => 5,
                    'is_system' => true,
                    'active' => true,
                ]
            );

            // Clean existing questions for this activity to avoid duplicates
            DB::table('activity_questions')->where('activity_id', $activity->id)->delete();

            $optionsBatch = [];
            $now = now();
            $qIndex = 1;

            foreach ($sectionData['questions'] as $qData) {
                $qId = DB::table('activity_questions')->insertGetId([
                    'activity_id' => $activity->id,
                    'type' => 'multiple_choice',
                    'question_text' => $qData['question'],
                    'correct_answer' => $qData['correct'],
                    'explanation' => $qData['explanation'],
                    'points' => 10,
                    'sort_order' => $qIndex++,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $oIndex = 1;
                foreach ($qData['options'] as $optText) {
                    $optionsBatch[] = [
                        'question_id' => $qId,
                        'option_text' => $optText,
                        'is_correct' => ($optText === $qData['correct']),
                        'sort_order' => $oIndex++,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Bulk insert all 100 options in one single query
            if (!empty($optionsBatch)) {
                DB::table('activity_options')->insert($optionsBatch);
            }

            $this->command?->info("✓ Seeded activity: {$activity->title} (" . count($sectionData['questions']) . " questions, " . count($optionsBatch) . " options)");
        }

        $this->command?->info('All 12 learning activities seeded successfully with 25 questions each!');
    }
}
