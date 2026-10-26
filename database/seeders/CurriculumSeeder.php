<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CurriculumFramework;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\CurriculumStandard;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Framework
        $framework = CurriculumFramework::updateOrCreate(
            ['name' => 'International Primary Curriculum', 'version' => '2026.1'],
            [
                'description' => 'Global Cambridge & IB Primary Aligned Curriculum for Grades 1-3',
                'country_code' => 'INT',
                'language' => 'en',
                'active' => true,
            ]
        );

        // 2. Grade Levels
        $grade1 = GradeLevel::updateOrCreate(
            ['name' => 'grade_1'],
            ['display_name' => 'Grade 1', 'min_age' => 6, 'max_age' => 7, 'sort_order' => 1]
        );
        $grade2 = GradeLevel::updateOrCreate(
            ['name' => 'grade_2'],
            ['display_name' => 'Grade 2', 'min_age' => 7, 'max_age' => 8, 'sort_order' => 2]
        );
        $grade3 = GradeLevel::updateOrCreate(
            ['name' => 'grade_3'],
            ['display_name' => 'Grade 3', 'min_age' => 8, 'max_age' => 9, 'sort_order' => 3]
        );

        // 3. Subjects
        $math = Subject::updateOrCreate(
            ['slug' => 'math'],
            ['name' => 'Mathematics', 'icon' => 'calculator', 'color' => '#2563EB', 'sort_order' => 1]
        );
        $english = Subject::updateOrCreate(
            ['slug' => 'english'],
            ['name' => 'English Language Arts', 'icon' => 'book-open', 'color' => '#059669', 'sort_order' => 2]
        );
        $science = Subject::updateOrCreate(
            ['slug' => 'science'],
            ['name' => 'Science & Discovery', 'icon' => 'flask-conical', 'color' => '#D97706', 'sort_order' => 3]
        );
        $creative = Subject::updateOrCreate(
            ['slug' => 'creative'],
            ['name' => 'Creative & Ocean Arts', 'icon' => 'palette', 'color' => '#7C3AED', 'sort_order' => 4]
        );

        // 4. Standards & Objectives
        $objectives = [
            // Grade 1 Math
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade1->id,
                'subject_id' => $math->id,
                'topic' => 'Counting & Number Sense',
                'description' => 'Count forward and backward to 20 with ocean sea creatures and seashells.',
                'difficulty' => 'beginner',
                'min_age' => 6,
                'max_age' => 7,
            ],
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade1->id,
                'subject_id' => $math->id,
                'topic' => 'Simple Addition within 10',
                'description' => 'Combine two groups of friendly fish to find sums up to 10.',
                'difficulty' => 'beginner',
                'min_age' => 6,
                'max_age' => 7,
            ],
            // Grade 2 Math
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade2->id,
                'subject_id' => $math->id,
                'topic' => 'Two-Digit Addition',
                'description' => 'Add two 2-digit numbers using mental math and base-ten blocks.',
                'difficulty' => 'intermediate',
                'min_age' => 7,
                'max_age' => 8,
            ],
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade2->id,
                'subject_id' => $math->id,
                'topic' => 'Odd & Even Sea Numbers',
                'description' => 'Identify even and odd sets by pairing oceanic friends together.',
                'difficulty' => 'beginner',
                'min_age' => 7,
                'max_age' => 8,
            ],
            // Grade 3 Math
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade3->id,
                'subject_id' => $math->id,
                'topic' => 'Multiplication Tables (2, 5, 10)',
                'description' => 'Use skip-counting and arrays to solve fast multiplication problems.',
                'difficulty' => 'intermediate',
                'min_age' => 8,
                'max_age' => 9,
            ],
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade3->id,
                'subject_id' => $math->id,
                'topic' => 'Introduction to Fractions',
                'description' => 'Understand halves (1/2), thirds (1/3), and quarters (1/4) using coral pizza slices.',
                'difficulty' => 'advanced',
                'min_age' => 8,
                'max_age' => 9,
            ],

            // Grade 1 English
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade1->id,
                'subject_id' => $english->id,
                'topic' => 'Phonics & Short Vowels',
                'description' => 'Recognize short vowel sounds (a, e, i, o, u) in CVC words like cat, fin, sun.',
                'difficulty' => 'beginner',
                'min_age' => 6,
                'max_age' => 7,
            ],
            // Grade 2 English
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade2->id,
                'subject_id' => $english->id,
                'topic' => 'Nouns, Verbs & Adjectives',
                'description' => 'Identify naming words, action words, and describing words in lively ocean stories.',
                'difficulty' => 'intermediate',
                'min_age' => 7,
                'max_age' => 8,
            ],
            // Grade 3 English
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade3->id,
                'subject_id' => $english->id,
                'topic' => 'Reading Comprehension & Inferences',
                'description' => 'Read short informational paragraphs about coral reefs and draw text-based conclusions.',
                'difficulty' => 'intermediate',
                'min_age' => 8,
                'max_age' => 9,
            ],

            // Grade 1 Science
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade1->id,
                'subject_id' => $science->id,
                'topic' => 'Living vs Non-Living',
                'description' => 'Differentiate living things (clownfish, kelp) from non-living things (rocks, water).',
                'difficulty' => 'beginner',
                'min_age' => 6,
                'max_age' => 7,
            ],
            // Grade 2 Science
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade2->id,
                'subject_id' => $science->id,
                'topic' => 'Ocean Habitats & Adaptations',
                'description' => 'Explore how fish use gills to breathe underwater and camouflage on the reef.',
                'difficulty' => 'intermediate',
                'min_age' => 7,
                'max_age' => 8,
            ],
            // Grade 3 Science
            [
                'framework_id' => $framework->id,
                'grade_level_id' => $grade3->id,
                'subject_id' => $science->id,
                'topic' => 'The Water Cycle & Weather',
                'description' => 'Understand evaporation, condensation, precipitation, and ocean currents.',
                'difficulty' => 'intermediate',
                'min_age' => 8,
                'max_age' => 9,
            ],
        ];

        foreach ($objectives as $obj) {
            LearningObjective::firstOrCreate(
                [
                    'framework_id' => $obj['framework_id'],
                    'grade_level_id' => $obj['grade_level_id'],
                    'subject_id' => $obj['subject_id'],
                    'topic' => $obj['topic'],
                ],
                $obj
            );
        }
    }
}
