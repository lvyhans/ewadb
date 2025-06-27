<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadEmploymentHistory;
use App\Models\LeadFollowup;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users if they don't exist
        $user1 = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password')
        ]);

        $user2 = User::firstOrCreate([
            'email' => 'counselor@example.com'
        ], [
            'name' => 'Visa Counselor',
            'password' => bcrypt('password')
        ]);

        // Sample leads data
        $leadsData = [
            [
                'ref_no' => 'LEAD000001',
                'name' => 'John Smith',
                'phone' => '+1-555-0123',
                'email' => 'john.smith@email.com',
                'preferred_country' => 'Canada',
                'preferred_city' => 'Toronto',
                'preferred_college' => 'University of Toronto',
                'preferred_course' => 'Computer Science',
                'source' => 'Google',
                'status' => 'new',
                'created_by' => $user1->id,
                'remarks' => 'Interested in pursuing Masters in Computer Science',
            ],
            [
                'ref_no' => 'LEAD000002',
                'name' => 'Sarah Johnson',
                'phone' => '+1-555-0456',
                'email' => 'sarah.j@email.com',
                'dob' => '1995-05-15',
                'father_name' => 'Robert Johnson',
                'preferred_country' => 'Australia',
                'preferred_city' => 'Melbourne',
                'preferred_college' => 'University of Melbourne',
                'preferred_course' => 'Business Administration',
                'score_type' => 'ielts',
                'ielts_overall' => 7.5,
                'ielts_listening' => 8.0,
                'ielts_reading' => 7.5,
                'ielts_writing' => 7.0,
                'ielts_speaking' => 7.5,
                'last_qualification' => 'Graduation',
                'graduation_year' => '2017',
                'graduation_percentage' => '85%',
                'graduation_major' => 'Commerce',
                'source' => 'Reference',
                'reference_name' => 'Mike Wilson',
                'status' => 'contacted',
                'assigned_to' => $user2->id,
                'created_by' => $user1->id,
                'remarks' => 'High IELTS scores, good academic background',
            ],
            [
                'ref_no' => 'LEAD000003',
                'name' => 'Rajesh Patel',
                'phone' => '+91-98765-43210',
                'email' => 'rajesh.patel@email.com',
                'dob' => '1992-08-20',
                'father_name' => 'Dinesh Patel',
                'city' => 'Mumbai',
                'address' => '123 Marine Drive, Mumbai',
                'preferred_country' => 'UK',
                'preferred_city' => 'London',
                'preferred_college' => 'Imperial College London',
                'preferred_course' => 'Data Science',
                'score_type' => 'pte',
                'pte_overall' => 75,
                'pte_listening' => 78,
                'pte_reading' => 72,
                'pte_writing' => 70,
                'pte_speaking' => 80,
                'last_qualification' => 'Post Graduation',
                'graduation_year' => '2014',
                'graduation_percentage' => '78%',
                'graduation_major' => 'Engineering',
                'post_graduation_year' => '2016',
                'post_graduation_percentage' => '82%',
                'post_graduation_major' => 'Computer Science',
                'travel_history' => 'UAE (2019), Singapore (2020)',
                'source' => 'Website',
                'status' => 'qualified',
                'assigned_to' => $user2->id,
                'created_by' => $user1->id,
                'remarks' => 'Experienced professional looking for career advancement',
            ],
        ];

        foreach ($leadsData as $leadData) {
            $lead = Lead::create($leadData);

            // Add employment history for some leads
            if ($lead->name === 'Sarah Johnson') {
                LeadEmploymentHistory::create([
                    'lead_id' => $lead->id,
                    'join_date' => '2017-06-01',
                    'left_date' => '2020-12-31',
                    'company_name' => 'ABC Corporation',
                    'job_position' => 'Marketing Executive',
                    'job_city' => 'New York',
                ]);

                LeadEmploymentHistory::create([
                    'lead_id' => $lead->id,
                    'join_date' => '2021-01-15',
                    'left_date' => '2024-05-30',
                    'company_name' => 'XYZ Ltd',
                    'job_position' => 'Senior Marketing Manager',
                    'job_city' => 'Boston',
                ]);
            }

            if ($lead->name === 'Rajesh Patel') {
                LeadEmploymentHistory::create([
                    'lead_id' => $lead->id,
                    'join_date' => '2016-07-01',
                    'left_date' => '2019-03-31',
                    'company_name' => 'Tech Solutions Pvt Ltd',
                    'job_position' => 'Software Developer',
                    'job_city' => 'Mumbai',
                ]);

                LeadEmploymentHistory::create([
                    'lead_id' => $lead->id,
                    'join_date' => '2019-04-01',
                    'left_date' => '2024-06-15',
                    'company_name' => 'Digital Innovations',
                    'job_position' => 'Senior Developer',
                    'job_city' => 'Pune',
                ]);
            }

            // Add some followups
            if ($lead->status === 'contacted' || $lead->status === 'qualified') {
                LeadFollowup::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user2->id,
                    'type' => 'call',
                    'subject' => 'Initial consultation call',
                    'description' => 'Discussed study options and requirements',
                    'status' => 'completed',
                    'scheduled_at' => now()->subDays(3),
                    'completed_at' => now()->subDays(3)->addHours(1),
                    'outcome' => 'Positive response, needs more information about courses',
                    'next_followup' => now()->addDays(2),
                ]);

                LeadFollowup::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user2->id,
                    'type' => 'email',
                    'subject' => 'Course details and application process',
                    'description' => 'Send detailed course information and application guidelines',
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addDays(2),
                ]);
            }
        }
    }
}
