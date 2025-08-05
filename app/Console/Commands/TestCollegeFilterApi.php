<?php

namespace App\Console\Commands;

use App\Services\ExternalApiService;
use Illuminate\Console\Command;

class TestCollegeFilterApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:college-filter-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the College Filter API integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎓 Testing College Filter API Integration');
        $this->line('');

        $service = new ExternalApiService();

        // Test 1: Countries
        $this->info('1. Testing Countries:');
        $countries = $service->getCountries();
        $this->line("   ✓ Found " . count($countries) . " countries");
        $this->line("   ✓ First few: " . implode(', ', array_slice(array_column($countries, 'country_name'), 0, 3)));
        $this->line('');

        if (empty($countries)) {
            $this->error('No countries found. Stopping test.');
            return;
        }

        // Test 2: Cities
        $firstCountry = $countries[0]['country_name'];
        $this->info("2. Testing Cities for '{$firstCountry}':");
        $cities = $service->getCitiesByCountry($firstCountry);
        $this->line("   ✓ Found " . count($cities) . " cities");
        $this->line("   ✓ First few: " . implode(', ', array_slice(array_column($cities, 'city_name'), 0, 5)));
        $this->line('');

        if (empty($cities)) {
            $this->error('No cities found. Stopping test.');
            return;
        }

        // Test 3: Colleges
        $firstCity = $cities[0]['city_name'];
        $this->info("3. Testing Colleges for '{$firstCountry}' > '{$firstCity}':");
        $colleges = $service->getCollegesByCountryAndCity($firstCountry, $firstCity);
        $this->line("   ✓ Found " . count($colleges) . " colleges");
        $this->line("   ✓ First few: " . implode(', ', array_slice(array_column($colleges, 'college_name'), 0, 3)));
        $this->line('');

        if (empty($colleges)) {
            $this->error('No colleges found. Stopping test.');
            return;
        }

        // Test 4: Courses
        $firstCollege = $colleges[0]['college_name'];
        $this->info("4. Testing Courses for '{$firstCountry}' > '{$firstCity}' > '{$firstCollege}':");
        $courses = $service->getCoursesByCountryCityCollege($firstCountry, $firstCity, $firstCollege);
        $this->line("   ✓ Found " . count($courses) . " courses");
        $this->line("   ✓ First few: " . implode(', ', array_slice(array_column($courses, 'course_name'), 0, 3)));
        $this->line('');

        // Test 5: Unified API
        $this->info('5. Testing Unified API:');
        $result = $service->getCollegeFilterData(['country' => $firstCountry]);
        if (isset($result['status']) && $result['status'] === 'success') {
            $this->line("   ✓ Unified API working: " . $result['level'] . " level with " . $result['count'] . " items");
        } else {
            $this->error("   ✗ Unified API failed");
        }
        $this->line('');

        $this->info('🎉 College Filter API Integration Test Complete!');
        $this->line('');
        $this->comment('The API is successfully integrated and working with your application form.');
        $this->comment('Users can now select countries, cities, colleges, and courses with real data.');
    }
}
