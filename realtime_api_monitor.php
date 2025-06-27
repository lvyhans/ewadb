<?php

/**
 * Real-time API Hit Monitor
 * Monitors Laravel logs for API hits in real-time
 */

echo "🔍 Real-time API Hit Monitor\n";
echo "============================\n";
echo "Monitoring Laravel logs for Lead Revert API hits...\n";
echo "Press Ctrl+C to stop monitoring\n\n";

$logFile = 'storage/logs/laravel.log';
$lastSize = filesize($logFile);

// Show recent activity first
echo "📋 Recent Activity (last 10 entries):\n";
echo "--------------------------------------\n";

$lines = explode("\n", file_get_contents($logFile));
$recentLines = array_slice($lines, -20);

foreach ($recentLines as $line) {
    if (strpos($line, 'lead revert') !== false || 
        strpos($line, 'Revert notifications') !== false ||
        strpos($line, 'LeadRevertApiController') !== false) {
        
        // Extract timestamp and message
        if (preg_match('/\[(.*?)\]/', $line, $matches)) {
            $timestamp = $matches[1];
            $message = substr($line, strpos($line, '] ') + 2);
            
            if (strpos($message, 'New lead revert submitted') !== false) {
                echo "✅ " . date('H:i:s', strtotime($timestamp)) . " - REVERT SUBMITTED: $message\n";
            } elseif (strpos($message, 'Revert notifications sent') !== false) {
                echo "📧 " . date('H:i:s', strtotime($timestamp)) . " - NOTIFICATIONS SENT: $message\n";
            }
        }
    }
}

echo "\n🔄 Monitoring for new activity...\n";
echo "==================================\n";

// Monitor for new entries
while (true) {
    clearstatcache();
    $currentSize = filesize($logFile);
    
    if ($currentSize > $lastSize) {
        // Read new content
        $handle = fopen($logFile, 'r');
        fseek($handle, $lastSize);
        
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            
            if (strpos($line, 'lead revert') !== false || 
                strpos($line, 'Revert notifications') !== false ||
                strpos($line, 'LeadRevertApiController') !== false) {
                
                $currentTime = date('H:i:s');
                
                if (strpos($line, 'New lead revert submitted') !== false) {
                    echo "🆕 $currentTime - NEW API HIT: $line\n";
                } elseif (strpos($line, 'Revert notifications sent') !== false) {
                    echo "📨 $currentTime - NOTIFICATIONS: $line\n";
                } else {
                    echo "ℹ️  $currentTime - LOG: $line\n";
                }
            }
        }
        
        fclose($handle);
        $lastSize = $currentSize;
    }
    
    sleep(1); // Check every second
}
