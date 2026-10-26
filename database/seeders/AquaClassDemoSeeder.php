<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\FishStage;
use App\Models\Fish;
use App\Models\AquariumDecoration;
use App\Models\FishDecoration;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\ClassroomAquarium;
use App\Models\StudentProfile;
use App\Models\PointTransaction;
use App\Models\LearningActivity;
use App\Models\ActivityQuestion;
use App\Models\ActivityOption;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Achievement;
use App\Models\WeeklyGoal;

class AquaClassDemoSeeder extends Seeder
{
    public function run(): void
    {
        $grade2 = GradeLevel::where('name', 'grade_2')->first();
        $grade3 = GradeLevel::where('name', 'grade_3')->first();
        $math = Subject::where('slug', 'math')->first();
        $english = Subject::where('slug', 'english')->first();
        $science = Subject::where('slug', 'science')->first();

        $stageFry = FishStage::where('name', 'tiny_fry')->first();
        $stageGuppy = FishStage::where('name', 'little_swimmer')->first();
        $stageExplorer = FishStage::where('name', 'reef_explorer')->first();
        $stageGuardian = FishStage::where('name', 'coral_guardian')->first();

        // 1. Teacher Account
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@aquaclass.com'],
            [
                'name' => 'Ms. Clara Oceanic',
                'role' => 'teacher',
                'password' => Hash::make('password123'),
                'onboarded' => true,
                'avatar' => '👩‍🏫',
                'locale' => 'en',
                'timezone' => 'UTC',
            ]
        );

        // 2. Admin Account
        $admin = User::updateOrCreate(
            ['email' => 'admin@aquaclass.com'],
            [
                'name' => 'Captain Coral (Admin)',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'onboarded' => true,
                'avatar' => '⚓',
                'locale' => 'en',
                'timezone' => 'UTC',
            ]
        );

        // 3. Classroom
        $classroom = Classroom::updateOrCreate(
            ['join_code' => 'AQUA202'],
            [
                'name' => 'Grade 2 Blue Dolphins 🐬',
                'teacher_id' => $teacher->id,
                'grade_level_id' => $grade2->id,
                'language' => 'en',
                'timezone' => 'UTC',
                'max_students' => 30,
                'description' => 'A joyful aquarium classroom where every curious learner grows bright and strong!',
                'leaderboard_enabled' => true,
                'active' => true,
            ]
        );

        ClassroomAquarium::updateOrCreate(
            ['classroom_id' => $classroom->id],
            [
                'theme' => 'coral_reef',
                'background' => 'sunlit_lagoon',
                'decorations_data' => ['plants' => 4, 'corals' => 3, 'castle' => true],
            ]
        );

        // 4. Students
        $studentsData = [
            [
                'name' => 'Leo Starfish',
                'email' => 'leo@aquaclass.com',
                'avatar' => '⭐',
                'points' => 280,
                'streak' => 4,
                'fish_name' => 'Finny',
                'species' => 'clownfish',
                'color' => '#FF7A00',
                'stage' => $stageExplorer,
                'grade' => $grade2,
            ],
            [
                'name' => 'Maya Coral',
                'email' => 'maya@aquaclass.com',
                'avatar' => '🪸',
                'points' => 170,
                'streak' => 3,
                'fish_name' => 'Bubbles',
                'species' => 'blue_tang',
                'color' => '#0284C7',
                'stage' => $stageExplorer,
                'grade' => $grade2,
            ],
            [
                'name' => 'Kai Nautil',
                'email' => 'kai@aquaclass.com',
                'avatar' => '🐚',
                'points' => 95,
                'streak' => 2,
                'fish_name' => 'Sunny',
                'species' => 'yellow_tang',
                'color' => '#EAB308',
                'stage' => $stageGuppy,
                'grade' => $grade2,
            ],
            [
                'name' => 'Zara Wave',
                'email' => 'zara@aquaclass.com',
                'avatar' => '🌊',
                'points' => 420,
                'streak' => 6,
                'fish_name' => 'Glint',
                'species' => 'angelfish',
                'color' => '#A855F7',
                'stage' => $stageGuardian,
                'grade' => $grade3,
            ],
        ];

        $decorations = AquariumDecoration::all();

        foreach ($studentsData as $sData) {
            $student = User::updateOrCreate(
                ['email' => $sData['email']],
                [
                    'name' => $sData['name'],
                    'role' => 'student',
                    'password' => Hash::make('password123'),
                    'onboarded' => true,
                    'avatar' => $sData['avatar'],
                    'locale' => 'en',
                    'timezone' => 'UTC',
                ]
            );

            // Student profile
            StudentProfile::updateOrCreate(
                ['user_id' => $student->id],
                [
                    'display_name' => $sData['name'],
                    'grade_level_id' => $sData['grade']->id,
                    'total_points' => $sData['points'],
                    'current_streak' => $sData['streak'],
                    'last_activity_date' => now()->toDateString(),
                    'parent_email' => 'parent.' . strtolower(explode(' ', $sData['name'])[0]) . '@example.com',
                    'preferences' => ['sound_enabled' => true, 'theme' => 'blue_ocean'],
                ]
            );

            // Fish
            $fish = Fish::updateOrCreate(
                ['user_id' => $student->id],
                [
                    'name' => $sData['fish_name'],
                    'species' => $sData['species'],
                    'color' => $sData['color'],
                    'size' => $sData['stage']->size_multiplier,
                    'stage_id' => $sData['stage']->id,
                    'total_growth_points' => $sData['points'],
                    'customizations' => [
                        'hat' => $sData['points'] > 200 ? 'pirate_hat' : 'none',
                        'bubble_trail' => true,
                    ],
                ]
            );

            // Classroom membership (for Grade 2 students)
            if ($sData['grade']->id === $grade2->id) {
                ClassroomMember::updateOrCreate(
                    ['classroom_id' => $classroom->id, 'user_id' => $student->id],
                    ['role' => 'student', 'status' => 'active', 'joined_at' => now()]
                );
            }

            // Ledger record
            PointTransaction::firstOrCreate(
                ['user_id' => $student->id, 'type' => 'homework_completion'],
                [
                    'points' => $sData['points'],
                    'source_type' => 'Initial',
                    'source_id' => 1,
                    'description' => 'Welcome points and completed assignments',
                    'awarded_by' => $teacher->id,
                    'created_at' => now()->subDays(2),
                ]
            );

            // Weekly goal
            WeeklyGoal::updateOrCreate(
                ['user_id' => $student->id, 'week_start' => now()->startOfWeek()->toDateString()],
                [
                    'classroom_id' => $classroom->id,
                    'target_points' => 50,
                    'earned_points' => min(50, $sData['points']),
                    'completed' => $sData['points'] >= 50,
                ]
            );

            // Add unlocked decorations
            if ($decorations->isNotEmpty()) {
                FishDecoration::firstOrCreate(
                    ['fish_id' => $fish->id, 'decoration_id' => $decorations->first()->id],
                    ['position_x' => -2.5, 'position_y' => -1.2, 'placed' => true, 'unlocked_at' => now()]
                );
            }
        }

        // 5. Interactive Learning Activities
        $mathObj = LearningObjective::where('topic', 'Two-Digit Addition')->first()
            ?? LearningObjective::first();
        $scienceObj = LearningObjective::where('topic', 'Ocean Habitats & Adaptations')->first()
            ?? LearningObjective::first();
        $englishObj = LearningObjective::where('topic', 'Nouns, Verbs & Adjectives')->first()
            ?? LearningObjective::first();

        // Math Activity
        $actMath = LearningActivity::updateOrCreate(
            ['title' => 'Coral Reef Math: Addition Adventure'],
            [
                'type' => 'multiple_choice',
                'subject_id' => $math->id,
                'grade_level_id' => $grade2->id,
                'objective_id' => $mathObj->id,
                'difficulty' => 'easy',
                'instructions' => 'Count the cheerful sea creatures and add the numbers to discover secret reef treasure!',
                'max_points' => 30,
                'retry_allowed' => true,
                'max_attempts' => 3,
                'is_system' => true,
                'active' => true,
            ]
        );

        $q1 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actMath->id, 'sort_order' => 1],
            [
                'type' => 'multiple_choice',
                'question_text' => 'There are 14 orange clownfish and 12 blue tangs swimming by the anemone. How many friendly fish are there in total?',
                'question_data' => ['image' => 'reef_fish', 'hint' => 'Add 14 + 12'],
                'correct_answer' => '26',
                'explanation' => 'Super job! 14 + 12 = 26 fish swimming together in harmony.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => '24'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => '26'], ['is_correct' => true, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => '28'], ['is_correct' => false, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => '30'], ['is_correct' => false, 'sort_order' => 4]);

        $q2 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actMath->id, 'sort_order' => 2],
            [
                'type' => 'multiple_choice',
                'question_text' => 'A baby turtle counts 25 shiny seashells on Monday, and finds 15 more on Tuesday. How many seashells does the turtle have now?',
                'question_data' => ['hint' => '25 + 15 = ?'],
                'correct_answer' => '40',
                'explanation' => 'Fantastic! 25 + 15 = 40 glittering shells.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => '35'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => '40'], ['is_correct' => true, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => '45'], ['is_correct' => false, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => '50'], ['is_correct' => false, 'sort_order' => 4]);

        $q3 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actMath->id, 'sort_order' => 3],
            [
                'type' => 'multiple_choice',
                'question_text' => 'Which of these numbers is an EVEN number of pearls?',
                'question_data' => ['hint' => 'Even numbers can be split into two equal teams!'],
                'correct_answer' => '18',
                'explanation' => 'Brilliant! 18 ends in 8, so it is an even number.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => '13'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => '17'], ['is_correct' => false, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => '18'], ['is_correct' => true, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => '21'], ['is_correct' => false, 'sort_order' => 4]);

        // Science Activity
        $actSci = LearningActivity::updateOrCreate(
            ['title' => 'Ocean Explorers: Gills, Fins & Habitats'],
            [
                'type' => 'multiple_choice',
                'subject_id' => $science->id,
                'grade_level_id' => $grade2->id,
                'objective_id' => $scienceObj->id,
                'difficulty' => 'easy',
                'instructions' => 'Discover how our ocean animal friends breathe, move, and thrive in coral reefs!',
                'max_points' => 20,
                'retry_allowed' => true,
                'max_attempts' => 3,
                'is_system' => true,
                'active' => true,
            ]
        );

        $sq1 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actSci->id, 'sort_order' => 1],
            [
                'type' => 'multiple_choice',
                'question_text' => 'What special body part do fish use to breathe underwater by taking oxygen from the water?',
                'correct_answer' => 'Gills',
                'explanation' => 'Correct! Fish use gills to absorb dissolved oxygen directly from water.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $sq1->id, 'option_text' => 'Lungs'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $sq1->id, 'option_text' => 'Gills'], ['is_correct' => true, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $sq1->id, 'option_text' => 'Nose'], ['is_correct' => false, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $sq1->id, 'option_text' => 'Wings'], ['is_correct' => false, 'sort_order' => 4]);

        $sq2 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actSci->id, 'sort_order' => 2],
            [
                'type' => 'multiple_choice',
                'question_text' => 'Why do clownfish hide safely inside sea anemones?',
                'correct_answer' => 'The anemone protects them from larger predators',
                'explanation' => 'Wonderful! Clownfish have a special mucus layer that keeps them safe from stinging tentacles.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $sq2->id, 'option_text' => 'To sleep all winter'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $sq2->id, 'option_text' => 'The anemone protects them from larger predators'], ['is_correct' => true, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $sq2->id, 'option_text' => 'To fly above the ocean'], ['is_correct' => false, 'sort_order' => 3]);

        // English Activity
        $actEng = LearningActivity::updateOrCreate(
            ['title' => 'Word Waves: Finding Verbs & Nouns'],
            [
                'type' => 'multiple_choice',
                'subject_id' => $english->id,
                'grade_level_id' => $grade2->id,
                'objective_id' => $englishObj->id,
                'difficulty' => 'easy',
                'instructions' => 'Dive into language! Identify action words (verbs) and naming words (nouns).',
                'max_points' => 20,
                'retry_allowed' => true,
                'max_attempts' => 3,
                'is_system' => true,
                'active' => true,
            ]
        );

        $eq1 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actEng->id, 'sort_order' => 1],
            [
                'type' => 'multiple_choice',
                'question_text' => 'Look at the sentence: "The playful dolphin jumps over the ocean wave." Which word is an ACTION verb?',
                'correct_answer' => 'jumps',
                'explanation' => 'Spot on! "Jumps" is what the dolphin is doing — an action verb.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $eq1->id, 'option_text' => 'dolphin'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $eq1->id, 'option_text' => 'jumps'], ['is_correct' => true, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $eq1->id, 'option_text' => 'ocean'], ['is_correct' => false, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $eq1->id, 'option_text' => 'wave'], ['is_correct' => false, 'sort_order' => 4]);

        $eq2 = ActivityQuestion::updateOrCreate(
            ['activity_id' => $actEng->id, 'sort_order' => 2],
            [
                'type' => 'multiple_choice',
                'question_text' => 'Which word is a NOUN that names a creature in the sea?',
                'correct_answer' => 'Octopus',
                'explanation' => 'Great! An Octopus is a noun that names an eight-armed marine animal.',
                'points' => 10,
            ]
        );
        ActivityOption::updateOrCreate(['question_id' => $eq2->id, 'option_text' => 'Quickly'], ['is_correct' => false, 'sort_order' => 1]);
        ActivityOption::updateOrCreate(['question_id' => $eq2->id, 'option_text' => 'Swim'], ['is_correct' => false, 'sort_order' => 2]);
        ActivityOption::updateOrCreate(['question_id' => $eq2->id, 'option_text' => 'Octopus'], ['is_correct' => true, 'sort_order' => 3]);
        ActivityOption::updateOrCreate(['question_id' => $eq2->id, 'option_text' => 'Blue'], ['is_correct' => false, 'sort_order' => 4]);

        // 6. Classroom Homework Assignments
        $hw1 = Assignment::updateOrCreate(
            ['title' => 'My Ocean Adventure Journal & Drawing'],
            [
                'classroom_id' => $classroom->id,
                'description' => 'Write 3 complete sentences describing your favorite sea animal and why it is special! Include what it eats and how it swims.',
                'type' => 'homework',
                'subject_id' => $science->id,
                'activity_id' => $actSci->id,
                'due_date' => now()->addDays(3),
                'points' => 25,
                'status' => 'published',
                'allow_late' => true,
                'max_submissions' => 2,
                'created_by' => $teacher->id,
            ]
        );

        $hw2 = Assignment::updateOrCreate(
            ['title' => 'Weekly Math Practice: Coral Sums'],
            [
                'classroom_id' => $classroom->id,
                'description' => 'Complete the Coral Reef Math activity and achieve at least 20 points!',
                'type' => 'activity',
                'subject_id' => $math->id,
                'activity_id' => $actMath->id,
                'due_date' => now()->addDays(5),
                'points' => 20,
                'status' => 'published',
                'allow_late' => false,
                'max_submissions' => 1,
                'created_by' => $teacher->id,
            ]
        );

        // 7. Student Submissions
        $leo = User::where('email', 'leo@aquaclass.com')->first();
        $maya = User::where('email', 'maya@aquaclass.com')->first();

        if ($leo) {
            Submission::updateOrCreate(
                ['assignment_id' => $hw1->id, 'user_id' => $leo->id],
                [
                    'content' => 'My favorite sea creature is the sea turtle! They can hold their breath underwater for hours and glide gracefully like birds in the sky. I love how they protect their babies on the sandy beach.',
                    'status' => 'approved',
                    'submitted_at' => now()->subDay(),
                    'reviewed_at' => now()->subHours(4),
                    'reviewer_id' => $teacher->id,
                    'feedback' => 'Wonderful writing Leo! Your descriptive details about how turtles glide like birds are poetic and clear. Keep up the high effort!',
                    'points_awarded' => 25,
                    'points_credited' => true,
                ]
            );
        }

        if ($maya) {
            Submission::updateOrCreate(
                ['assignment_id' => $hw1->id, 'user_id' => $maya->id],
                [
                    'content' => 'I love the playful blue tang fish. They live in shallow lagoons and eat yummy green algae off the rocks to keep the coral reef clean and healthy.',
                    'status' => 'submitted',
                    'submitted_at' => now()->subHours(2),
                    'feedback' => null,
                    'points_awarded' => 0,
                    'points_credited' => false,
                ]
            );
        }

        // Achievements for Leo
        $achSplash = Achievement::where('slug', 'first-splash')->first();
        if ($leo && $achSplash) {
            $leo->achievements()->syncWithoutDetaching([$achSplash->id => ['earned_at' => now()->subDays(2)]]);
        }
    }
}
