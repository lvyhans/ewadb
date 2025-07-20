#!/bin/bash

# Test script for Task Webhook API
echo "Testing Task Webhook API..."

# Get a user ID from the database for testing
USER_ID=$(php artisan tinker --execute="echo App\\Models\\User::first()->id;")

# Define the API endpoint (using localhost for testing)
API_URL="http://127.0.0.1:8000/api/webhooks/tasks"

# Create a temporary JSON file for the request payload
cat > /tmp/test_webhook.json << EOL
{
  "b2b_member_id": $USER_ID,
  "task_id": "test-task-123",
  "task_name": "Test Task from API Test Script",
  "task_status": "created"
}
EOL

echo "Using payload:"
cat /tmp/test_webhook.json
echo ""

# Send the request to the API
echo "Sending request to $API_URL..."
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d @/tmp/test_webhook.json \
  $API_URL

echo ""
echo "Test completed."
