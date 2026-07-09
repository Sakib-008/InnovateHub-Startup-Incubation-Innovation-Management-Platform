<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\StartupIdea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@innovatehub.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Founders
        $founderData = [
            ['name' => 'Sarah Ahmed',   'email' => 'sarah@innovatehub.test',   'bio' => 'Serial entrepreneur with a passion for EdTech.'],
            ['name' => 'Rahim Chowdhury', 'email' => 'rahim@innovatehub.test', 'bio' => 'Building the future of AgriTech in Bangladesh.'],
            ['name' => 'Priya Das',     'email' => 'priya@innovatehub.test',   'bio' => 'FinTech founder focused on financial inclusion.'],
        ];

        $founders = [];
        foreach ($founderData as $fd) {
            $founders[] = User::create(array_merge($fd, [
                'password' => Hash::make('password'),
                'role'     => 'founder',
            ]));
        }

        // Mentors
        $mentorData = [
            ['name' => 'Dr. Karim Hossain', 'email' => 'karim@innovatehub.test', 'expertise' => 'Product Strategy & Growth', 'bio' => '15 years in Silicon Valley startups.'],
            ['name' => 'Lisa Chen',          'email' => 'lisa@innovatehub.test',  'expertise' => 'FinTech & Blockchain',       'bio' => 'Former Goldman Sachs VP turned startup mentor.'],
        ];

        $mentors = [];
        foreach ($mentorData as $md) {
            $mentors[] = User::create(array_merge($md, [
                'password' => Hash::make('password'),
                'role'     => 'mentor',
            ]));
        }

        // Investors
        $investorData = [
            ['name' => 'Venture Capital BD', 'email' => 'vc@innovatehub.test',    'company' => 'VCBD Fund', 'focus' => 'FinTech & EdTech', 'range' => '$50k - $500k'],
            ['name' => 'Angel Investor',      'email' => 'angel@innovatehub.test', 'company' => 'Solo Angel', 'focus' => 'AgriTech & HealthTech', 'range' => '$10k - $100k'],
        ];

        $investors = [];
        foreach ($investorData as $id) {
            $investor = User::create([
                'name'     => $id['name'],
                'email'    => $id['email'],
                'password' => Hash::make('password'),
                'role'     => 'investor',
            ]);
            $investor->investorProfile()->create([
                'company_name'     => $id['company'],
                'investment_focus' => $id['focus'],
                'investment_range' => $id['range'],
            ]);
            $investors[] = $investor;
        }

        // Startup Ideas
        $ideasData = [
            ['founder' => $founders[0], 'title' => 'EduTrack — Smart Learning Management', 'category' => 'EdTech', 'status' => 'approved', 'desc' => 'AI-powered platform that personalises learning paths for students in rural Bangladesh, integrating with existing school curricula.'],
            ['founder' => $founders[1], 'title' => 'FarmSense — IoT Crop Monitoring', 'category' => 'AgriTech', 'status' => 'approved', 'desc' => 'Low-cost IoT sensors and a mobile app helping smallholder farmers monitor soil, weather, and crop health in real time.'],
            ['founder' => $founders[2], 'title' => 'PayLocal — Mobile Payment for SMEs', 'category' => 'FinTech', 'status' => 'pending',  'desc' => 'Frictionless mobile payment solution targeting the 6 million unbanked SME owners across Bangladesh.'],
        ];

        foreach ($ideasData as $idea) {
            StartupIdea::create([
                'founder_id'  => $idea['founder']->id,
                'title'       => $idea['title'],
                'category'    => $idea['category'],
                'status'      => $idea['status'],
                'description' => $idea['desc'],
            ]);
        }

        // Events
        $events = [
            ['title' => 'Startup Pitch Night 2026',      'date' => now()->addDays(14), 'location' => 'Dhaka Innovation Hub'],
            ['title' => 'Women in Tech Summit',           'date' => now()->addDays(28), 'location' => 'BRAC University, Dhaka'],
            ['title' => 'AgriTech & CleanTech Showcase',  'date' => now()->addDays(45), 'location' => 'Online (Zoom)'],
        ];

        foreach ($events as $e) {
            Event::create([
                'created_by'  => $admin->id,
                'title'       => $e['title'],
                'description' => fake()->paragraph(3),
                'location'    => $e['location'],
                'event_date'  => $e['date'],
                'status'      => 'upcoming',
            ]);
        }
    }
}