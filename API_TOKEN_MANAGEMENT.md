# API Token Management Guide

## Overview
The Lead Revert API now uses token-based authentication for enhanced security. External teams need API tokens to submit reverts and access lead information.

## Generating API Tokens

### For Administrators
Use the following Artisan command to generate API tokens for external teams:

```bash
php artisan generate:api-token "Team Name" --email=team@example.com
```

#### Examples:
```bash
# Generate token for Document Verification Team
php artisan generate:api-token "Document Verification Team" --email=docteam@partner.com

# Generate token for Admissions Office
php artisan generate:api-token "Admissions Office" --email=admissions@university.edu

# Generate token for Interview Panel
php artisan generate:api-token "Interview Panel" --email=interviews@agency.com
```

### Token Output
The command will output:
```
API Token generated successfully for: Document Verification Team
Email: docteam@partner.com
Token: 1|abcd1234567890efghijklmnopqrstuvwxyz
Keep this token secure and provide it to the external team.
```

## Using API Tokens

### In API Requests
Include the token in the Authorization header:

```bash
curl -X POST https://your-domain.com/api/external/lead-reverts/submit \
  -H "Authorization: Bearer 1|abcd1234567890efghijklmnopqrstuvwxyz" \
  -H "Content-Type: application/json" \
  -d '{
    "ref_no": "LEAD000123",
    "revert_message": "Verification required for documents",
    "submitted_by": "John Doe"
  }'
```

### In Programming Languages

#### JavaScript/Node.js
```javascript
const response = await fetch('https://your-domain.com/api/external/lead-reverts/submit', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer 1|abcd1234567890efghijklmnopqrstuvwxyz',
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    ref_no: 'LEAD000123',
    revert_message: 'Verification required for documents',
    submitted_by: 'John Doe'
  })
});
```

#### Python
```python
import requests

headers = {
    'Authorization': 'Bearer 1|abcd1234567890efghijklmnopqrstuvwxyz',
    'Content-Type': 'application/json',
}

data = {
    'ref_no': 'LEAD000123',
    'revert_message': 'Verification required for documents',
    'submitted_by': 'John Doe'
}

response = requests.post('https://your-domain.com/api/external/lead-reverts/submit', 
                        headers=headers, json=data)
```

#### PHP
```php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://your-domain.com/api/external/lead-reverts/submit',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer 1|abcd1234567890efghijklmnopqrstuvwxyz',
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'ref_no' => 'LEAD000123',
        'revert_message' => 'Verification required for documents',
        'submitted_by' => 'John Doe'
    ])
]);

$response = curl_exec($curl);
curl_close($curl);
```

## Token Security Best Practices

### For External Teams
1. **Keep tokens secure**: Store tokens in environment variables, not in code
2. **Don't share tokens**: Each team should have their own unique token
3. **Use HTTPS**: Always use HTTPS in production to protect tokens in transit
4. **Report compromised tokens**: Contact administrators immediately if a token is compromised

### For Administrators
1. **Regular rotation**: Consider rotating tokens periodically
2. **Monitor usage**: Track API usage by token to identify unusual activity
3. **Revoke when needed**: Disable tokens for teams that no longer need access
4. **Unique tokens per team**: Don't share the same token across multiple teams

## Token Management Commands

### List all tokens (requires database access)
```sql
SELECT name, tokenable_id, created_at, last_used_at 
FROM personal_access_tokens 
ORDER BY created_at DESC;
```

### Revoke a token (requires database access)
```sql
DELETE FROM personal_access_tokens WHERE id = [token_id];
```

### Revoke all tokens for a user
```sql
DELETE FROM personal_access_tokens WHERE tokenable_id = [user_id];
```

## API Rate Limiting
- Individual submissions: 100 requests per hour per token
- Bulk submissions: 10 requests per hour per token (max 50 reverts per request)

## Troubleshooting

### Common Errors

#### "Unauthenticated" (401)
- **Cause**: Missing or invalid token
- **Solution**: Check that the Authorization header is properly formatted: `Bearer your-token`

#### "Token not found" (401)
- **Cause**: Token has been revoked or is invalid
- **Solution**: Generate a new token

#### "Rate limit exceeded" (429)
- **Cause**: Too many requests in a short period
- **Solution**: Wait and retry, or use bulk submission for multiple reverts

## Support
For token-related issues:
1. Check the token format and headers
2. Verify the token hasn't been revoked
3. Contact your system administrator for new tokens
4. Review the API documentation for proper usage
