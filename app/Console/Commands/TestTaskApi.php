<?php

namespace App\Console\Commands;

use App\Services\TaskManagementService;
use Illuminate\Console\Command;

class TestTaskApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:task-api {user_id} {--admin_id=} {--member_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test task API with specific user parameters';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $adminId = $this->option('admin_id');
        $memberId = $this->option('member_id');
        
        $taskService = app(TaskManagementService::class);
        
        // Test parameters similar to what dashboard uses
        $params = [
            'return' => 'count',
            'task_status' => 'open'
        ];
        
        if ($adminId) {
            $params['b2b_admin_id'] = $adminId;
            $params['b2b_member_id'] = 0;
            $this->info("Testing as admin ID: {$adminId}");
        } elseif ($memberId) {
            $params['b2b_member_id'] = $memberId;
            $params['b2b_admin_id'] = 1; // Fallback admin
            $this->info("Testing as member ID: {$memberId}");
        } else {
            $params['b2b_admin_id'] = $userId;
            $params['b2b_member_id'] = 0;
            $this->info("Testing as admin (default) ID: {$userId}");
        }
        
        $this->info("Request parameters:");
        $this->table(['Parameter', 'Value'], collect($params)->map(function($value, $key) {
            return [$key, $value];
        })->toArray());
        
        try {
            $result = $taskService->fetchTasks($params);
            
            $this->info("API Response:");
            $this->line(json_encode($result, JSON_PRETTY_PRINT));
            
            $count = $result['count'] ?? 'N/A';
            $this->info("Task count: {$count}");
            
        } catch (\Exception $e) {
            $this->error("API call failed: " . $e->getMessage());
        }
        
        return 0;
    }
}
