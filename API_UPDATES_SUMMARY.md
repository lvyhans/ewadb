# API Updates Summary - Simplified Parameters with Token Authentication

## ✅ **Changes Made**

### **1. Simplified API Parameters**
**Before:**
```json
{
  "ref_no": "LEAD000123",
  "revert_message": "Message content",
  "revert_type": "query",
  "submitted_by": "John Doe", 
  "team_name": "Document Team",
  "priority": "high",
  "metadata": {...}
}
```

**After:**
```json
{
  "ref_no": "LEAD000123",
  "revert_message": "Message content",
  "submitted_by": "John Doe"
}
```

### **2. Added Token Authentication**
- All API endpoints now require Bearer token authentication
- Added middleware `auth:sanctum` to external routes
- Tokens provide better security and usage tracking

### **3. Default Values**
- `revert_type`: Defaults to "remark"
- `team_name`: Defaults to "External Team"  
- `priority`: Defaults to "normal"
- `metadata`: Defaults to null

### **4. Updated Bulk Submission**
**Before:**
```json
{
  "submitted_by": "User Name",
  "team_name": "Team Name", 
  "reverts": [...]
}
```

**After:**
```json
{
  "reverts": [
    {
      "ref_no": "LEAD000123",
      "revert_message": "Message",
      "submitted_by": "John Doe"
    }
  ]
}
```

## 🔐 **Token Management**

### Generate Token
```bash
php artisan generate:api-token "Team Name" --email=team@example.com
```

### Use Token
```bash
curl -H "Authorization: Bearer your-token" \
     -H "Content-Type: application/json" \
     -X POST https://domain.com/api/external/lead-reverts/submit \
     -d '{"ref_no":"LEAD000123","revert_message":"Message","submitted_by":"John"}'
```

## 📋 **Required Parameters (Minimum)**
1. `ref_no` - Lead reference number
2. `revert_message` - The actual message/remark (10-2000 characters)
3. `submitted_by` - Person's name submitting the revert

## 🧪 **Testing**
```bash
# Generate a test token
php artisan generate:api-token "Test Team" --email=test@example.com

# Test the API
php test_lead_revert_api.php "http://localhost:8000/api/external/lead-reverts" "your-token"
```

## 📚 **Updated Documentation**
- `LEAD_REVERT_API_DOCUMENTATION.md` - Updated with new parameters and auth
- `API_TOKEN_MANAGEMENT.md` - Complete token management guide
- `test_lead_revert_api.php` - Updated test script with token support

## 🎯 **Benefits**
1. **Simpler Integration**: Only 3 required parameters instead of 7
2. **Better Security**: Token-based authentication with tracking
3. **Easier Maintenance**: Default values reduce complexity
4. **Cleaner API**: More focused and streamlined requests

## 🔄 **Migration for Existing Teams**
1. Generate API tokens for each external team
2. Update their integration code to:
   - Include `Authorization: Bearer token` header
   - Remove optional parameters (`revert_type`, `team_name`, `priority`, `metadata`)
   - For bulk submission, move `submitted_by` into each revert object
3. Test with new simplified format
4. Deploy updated integration

The API is now much simpler to use while maintaining all the functionality and adding better security!
