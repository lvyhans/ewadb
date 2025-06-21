<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Registration Submitted Successfully!</h1>
            
            <div class="text-gray-600 space-y-3 mb-6">
                <p>Thank you for registering as a Administrator.</p>
                <p>Your application has been submitted and is currently under review.</p>
                <p class="font-medium text-orange-600">
                    ⏳ Your account is pending approval and you will not be able to login until it's approved by an administrator.
                </p>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-medium text-blue-900 mb-2">What happens next?</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Our team will review your application and documents</li>
                    <li>• We may contact you for additional information if needed</li>
                    <li>• You'll receive an email notification once approved</li>
                    <li>• Processing typically takes 1-3 business days</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-medium text-gray-900 mb-2">Need help?</h3>
                <p class="text-sm text-gray-600">
                    If you have any questions, please contact our support team:
                </p>
                <p class="text-sm text-blue-600 mt-1">
                    📧 support@{{ config('app.name', 'company') }}.com<br>
                    📞 +91 XXXXX XXXXX
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-block">
                    Go to Login Page
                </a>
                
                <a href="/" 
                   class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors inline-block">
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>