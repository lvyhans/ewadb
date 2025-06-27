<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ManageExternalApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external-api:manage {action : enable|disable|status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage external API integration settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $envFile = base_path('.env');

        switch ($action) {
            case 'enable':
                $this->updateEnvValue('EXTERNAL_API_ENABLED', 'true');
                $this->info('✓ External API integration enabled');
                break;

            case 'disable':
                $this->updateEnvValue('EXTERNAL_API_ENABLED', 'false');
                $this->info('✓ External API integration disabled');
                break;

            case 'status':
                $this->showStatus();
                break;

            default:
                $this->error('Invalid action. Use: enable, disable, or status');
                return 1;
        }

        return 0;
    }

    private function showStatus()
    {
        $enabled = config('services.external_api.enabled');
        $url = config('services.external_api.enquiry_lead_url');

        $this->info('External API Status:');
        $this->info('Enabled: ' . ($enabled ? 'Yes' : 'No'));
        $this->info('URL: ' . ($url ?: 'Not configured'));
        
        if ($enabled && !$url) {
            $this->warn('⚠ API is enabled but URL is not configured!');
        }
    }

    private function updateEnvValue($key, $value)
    {
        $envFile = base_path('.env');
        $envContents = file_get_contents($envFile);
        
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";
        
        if (preg_match($pattern, $envContents)) {
            $envContents = preg_replace($pattern, $replacement, $envContents);
        } else {
            $envContents .= "\n{$replacement}";
        }
        
        file_put_contents($envFile, $envContents);
    }
}
