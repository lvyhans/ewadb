<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Services\ExternalApiService;
use Illuminate\Console\Command;

class TestExternalApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:external-api {--lead-id= : Specific lead ID to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test external API integration by sending lead data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leadId = $this->option('lead-id');
        
        if ($leadId) {
            $lead = Lead::find($leadId);
            if (!$lead) {
                $this->error("Lead with ID {$leadId} not found");
                return 1;
            }
        } else {
            $lead = Lead::first();
            if (!$lead) {
                $this->error("No leads found in database");
                return 1;
            }
        }

        $this->info("Testing external API with lead: {$lead->name} (ID: {$lead->id})");
        $this->info("API URL: " . config('services.external_api.enquiry_lead_url'));
        $this->info("API Enabled: " . (config('services.external_api.enabled') ? 'Yes' : 'No'));

        $externalApiService = new ExternalApiService();
        
        $this->info("Sending lead data to external API...");
        
        $success = $externalApiService->sendEnquiryLead($lead);
        
        if ($success) {
            $this->info("✓ Lead data sent successfully to external API");
        } else {
            $this->error("✗ Failed to send lead data to external API");
            $this->info("Check the logs for more details");
        }

        return $success ? 0 : 1;
    }
}
