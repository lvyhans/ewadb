<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Clearing existing users and adding new data...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing users
        DB::table('users')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Users data from your provided SQL
        $users = [
            [
                'id' => 1,
                'name' => 'First Admin',
                'email' => 'superadmin@yourcompany.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-20 22:35:21',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$qzpcp8V3fUZDZHxQfQqSqOmw/KicrpjKQciCpNVjayE3hzD2SHeQK',
                'remember_token' => null,
                'created_at' => '2025-06-20 22:35:21',
                'updated_at' => '2025-06-20 22:35:21',
                'company_name' => null,
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 2,
                'name' => 'Second Admin',
                'email' => 'admin1@example.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-20 23:57:47',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$U71ey95PC8cKXliAuESoF.1mnFfXHAYCqNz560kP8UV8vShNl7HtO',
                'remember_token' => null,
                'created_at' => '2025-06-20 22:43:23',
                'updated_at' => '2025-06-20 23:57:47',
                'company_name' => null,
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 5,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => null,
                'approved_by' => null,
                'admin_id' => 2,
                'admin_group_name' => 'Sales Team',
                'password' => '$2y$12$uJBcPDpZnGQClvimBiMZZeqYXNkifTMJiHQ79pVUpjGpi7f/JeFge',
                'remember_token' => null,
                'created_at' => '2025-06-20 22:44:17',
                'updated_at' => '2025-06-20 22:44:42',
                'company_name' => 'Company B',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 11,
                'name' => 'Bulk Test User 1',
                'email' => 'bulktest1@example.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 00:04:18',
                'approved_by' => 1,
                'admin_id' => 1,
                'admin_group_name' => null,
                'password' => '$2y$12$5h6APNCGW6bI9zOQqHUe1e6PCBRx5iaZRJxt3Ugtoz15BVHyMKgdu',
                'remember_token' => null,
                'created_at' => '2025-06-21 00:04:04',
                'updated_at' => '2025-06-21 00:04:18',
                'company_name' => 'Bulk Company 1',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 12,
                'name' => 'Bulk Test User 2',
                'email' => 'bulktest2@example.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 00:04:18',
                'approved_by' => 1,
                'admin_id' => 1,
                'admin_group_name' => null,
                'password' => '$2y$12$/tnlgnnYiEmgXokfQxBQaOVuuvhY2TPhaIaStujLdoXJdD/VOR1pS',
                'remember_token' => null,
                'created_at' => '2025-06-21 00:04:05',
                'updated_at' => '2025-06-21 00:04:18',
                'company_name' => 'Bulk Company 2',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 13,
                'name' => 'Bulk Test User 3',
                'email' => 'bulktest3@example.com',
                'email_verified_at' => null,
                'approval_status' => 'rejected',
                'rejection_reason' => 'Bulk rejection test',
                'approved_at' => null,
                'approved_by' => null,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$VfJvanY7vgWzuG2DycpQw.k2Q/FvKu74FNn7OKmp0pE26jYtIxRxq',
                'remember_token' => null,
                'created_at' => '2025-06-21 00:04:05',
                'updated_at' => '2025-06-21 00:04:31',
                'company_name' => 'Bulk Company 3',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 15,
                'name' => 'Test Admin User',
                'email' => 'testadmin@example.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 01:16:25',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$rYtTdtXh4EHPSn/x0D0PfOg7a2dPxGcREb0NfX7e708IKIOuDPTSa',
                'remember_token' => null,
                'created_at' => '2025-06-21 01:13:33',
                'updated_at' => '2025-06-21 01:16:25',
                'company_name' => 'Test Admin Company',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 16,
                'name' => 'Test Regular Admin 2',
                'email' => 'testadmin2@example.com',
                'email_verified_at' => null,
                'approval_status' => 'pending',
                'rejection_reason' => null,
                'approved_at' => null,
                'approved_by' => null,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$1SdUT9Khj2YacQVlO5cHguG12P/X9RhlQuzshE66T9ZtlGtNgYZQ6',
                'remember_token' => null,
                'created_at' => '2025-06-21 01:15:24',
                'updated_at' => '2025-06-21 01:15:24',
                'company_name' => 'Test Company 2',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 17,
                'name' => 'Neil Garza',
                'email' => 'moweval@mailinator.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 01:19:31',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$AsQETZ2IAzfkprRk0VkuIOGWGFvpYhTIIPx4K5MHeLHZHoXXtSZ5a',
                'remember_token' => null,
                'created_at' => '2025-06-21 01:16:57',
                'updated_at' => '2025-06-21 01:19:31',
                'company_name' => 'Hewitt Gilliam Trading',
                'company_registration_number' => 'Wagner Foreman Co',
                'gstin' => '27AAAPA1234A1Z5',
                'pan_number' => 'ABCDE1234F',
                'company_address' => 'Beatae tempore ut q',
                'company_city' => 'Kelly and Mejia Inc',
                'company_state' => 'Karnataka',
                'company_pincode' => '324356',
                'company_phone' => '9634887799',
                'company_email' => 'xyquwa@mailinator.com',
                'company_registration_certificate' => 'company_documents/1750488417_company_registration_certificate.pdf',
                'gst_certificate' => 'company_documents/1750488417_gst_certificate.pdf',
                'pan_card' => 'company_documents/1750488417_pan_card.pdf',
                'address_proof' => 'company_documents/1750488417_address_proof.pdf',
                'bank_statement' => 'company_documents/1750488417_bank_statement.pdf',
            ],
            [
                'id' => 18,
                'name' => 'Sarah Duran',
                'email' => 'cyhexeze@mailinator.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 01:37:49',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$TVNhADfpn4KQB5SoaZx8peYexfuyV7rXGiDQwZJ.49swszZnn.lSy',
                'remember_token' => null,
                'created_at' => '2025-06-21 01:37:49',
                'updated_at' => '2025-06-21 01:37:49',
                'company_name' => 'Blanchard and Newton Trading',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
            [
                'id' => 20,
                'name' => 'Quinlan Nielsen',
                'email' => 'cujefipare@mailinator.com',
                'email_verified_at' => null,
                'approval_status' => 'approved',
                'rejection_reason' => null,
                'approved_at' => '2025-06-21 01:57:08',
                'approved_by' => 1,
                'admin_id' => null,
                'admin_group_name' => null,
                'password' => '$2y$12$9XrAcs83X4k/xPRit6Luy.fKxV3OOZQUo8BQJGv5L3pYtLMq3uPyS',
                'remember_token' => null,
                'created_at' => '2025-06-21 01:57:08',
                'updated_at' => '2025-06-21 01:57:08',
                'company_name' => 'Duffy and Mullins Associates',
                'company_registration_number' => null,
                'gstin' => null,
                'pan_number' => null,
                'company_address' => null,
                'company_city' => null,
                'company_state' => null,
                'company_pincode' => null,
                'company_phone' => null,
                'company_email' => null,
                'company_registration_certificate' => null,
                'gst_certificate' => null,
                'pan_card' => null,
                'address_proof' => null,
                'bank_statement' => null,
            ],
        ];

        // Insert all users
        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ All users data added successfully!');
        $this->command->info('Total users: ' . count($users));
    }
}
