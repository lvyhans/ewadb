<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Registration - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <h1 class="text-2xl font-bold">Super Admin Registration</h1>
                <p class="mt-2 text-blue-100">Register your company to become a Super Administrator</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="p-6" x-data="registrationForm()">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Personal Information Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Personal Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10">
                                <button type="button" @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg x-show="!showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Company Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Company Registration Number -->
                        <div>
                            <label for="company_registration_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Registration Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_registration_number" name="company_registration_number" value="{{ old('company_registration_number') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- GSTIN -->
                        <div>
                            <label for="gstin" class="block text-sm font-medium text-gray-700 mb-2">
                                GSTIN <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500">(15 characters)</span>
                            </label>
                            <input type="text" id="gstin" name="gstin" value="{{ old('gstin') }}" required
                                   maxlength="15" placeholder="22AAAAA0000A1Z5"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                        </div>

                        <!-- PAN Number -->
                        <div>
                            <label for="pan_number" class="block text-sm font-medium text-gray-700 mb-2">
                                PAN Number <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500">(10 characters)</span>
                            </label>
                            <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" required
                                   maxlength="10" placeholder="ABCDE1234F"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                        </div>

                        <!-- Company Phone -->
                        <div>
                            <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="company_phone" name="company_phone" value="{{ old('company_phone') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Company Email -->
                        <div>
                            <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="company_email" name="company_email" value="{{ old('company_email') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Company Address -->
                    <div class="mt-6">
                        <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Address <span class="text-red-500">*</span>
                        </label>
                        <textarea id="company_address" name="company_address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('company_address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- City -->
                        <div>
                            <label for="company_city" class="block text-sm font-medium text-gray-700 mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_city" name="company_city" value="{{ old('company_city') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- State -->
                        <div>
                            <label for="company_state" class="block text-sm font-medium text-gray-700 mb-2">
                                State <span class="text-red-500">*</span>
                            </label>
                            <select id="company_state" name="company_state" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select State</option>
                                <option value="Andhra Pradesh" {{ old('company_state') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                                <option value="Arunachal Pradesh" {{ old('company_state') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                                <option value="Assam" {{ old('company_state') == 'Assam' ? 'selected' : '' }}>Assam</option>
                                <option value="Bihar" {{ old('company_state') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                                <option value="Chhattisgarh" {{ old('company_state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                                <option value="Goa" {{ old('company_state') == 'Goa' ? 'selected' : '' }}>Goa</option>
                                <option value="Gujarat" {{ old('company_state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                                <option value="Haryana" {{ old('company_state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                                <option value="Himachal Pradesh" {{ old('company_state') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                                <option value="Jharkhand" {{ old('company_state') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                                <option value="Karnataka" {{ old('company_state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                <option value="Kerala" {{ old('company_state') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                                <option value="Madhya Pradesh" {{ old('company_state') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                                <option value="Maharashtra" {{ old('company_state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                <option value="Manipur" {{ old('company_state') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                                <option value="Meghalaya" {{ old('company_state') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                                <option value="Mizoram" {{ old('company_state') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                                <option value="Nagaland" {{ old('company_state') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                                <option value="Odisha" {{ old('company_state') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                                <option value="Punjab" {{ old('company_state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                <option value="Rajasthan" {{ old('company_state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                                <option value="Sikkim" {{ old('company_state') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                                <option value="Tamil Nadu" {{ old('company_state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                <option value="Telangana" {{ old('company_state') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                                <option value="Tripura" {{ old('company_state') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                                <option value="Uttar Pradesh" {{ old('company_state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                                <option value="Uttarakhand" {{ old('company_state') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                                <option value="West Bengal" {{ old('company_state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                                <option value="Delhi" {{ old('company_state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                <option value="Chandigarh" {{ old('company_state') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                                <option value="Puducherry" {{ old('company_state') == 'Puducherry' ? 'selected' : '' }}>Puducherry</option>
                            </select>
                        </div>

                        <!-- Pincode -->
                        <div>
                            <label for="company_pincode" class="block text-sm font-medium text-gray-700 mb-2">
                                Pincode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_pincode" name="company_pincode" value="{{ old('company_pincode') }}" required
                                   maxlength="6" pattern="[0-9]{6}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Required Documents Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Required Documents
                    </h2>
                    <p class="text-sm text-gray-600 mb-6">Upload the following documents (PDF, JPG, JPEG, PNG - Max 5MB each):</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Registration Certificate -->
                        <div>
                            <label for="company_registration_certificate" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Registration Certificate <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="company_registration_certificate" name="company_registration_certificate" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- GST Certificate -->
                        <div>
                            <label for="gst_certificate" class="block text-sm font-medium text-gray-700 mb-2">
                                GST Certificate <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="gst_certificate" name="gst_certificate" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- PAN Card -->
                        <div>
                            <label for="pan_card" class="block text-sm font-medium text-gray-700 mb-2">
                                PAN Card <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="pan_card" name="pan_card" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Address Proof -->
                        <div>
                            <label for="address_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Proof <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="address_proof" name="address_proof" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Bank Statement -->
                        <div class="md:col-span-2">
                            <label for="bank_statement" class="block text-sm font-medium text-gray-700 mb-2">
                                Bank Statement (Last 3 months) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="bank_statement" name="bank_statement" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" required class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">
                            I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and 
                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">
                        Already have an account? Sign in
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function registrationForm() {
            return {
                showPassword: false,
                showConfirmPassword: false
            }
        }

        // Auto-format inputs
        document.addEventListener('DOMContentLoaded', function() {
            // GSTIN formatting
            const gstinInput = document.getElementById('gstin');
            gstinInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });

            // PAN formatting
            const panInput = document.getElementById('pan_number');
            panInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });

            // Pincode validation
            const pincodeInput = document.getElementById('company_pincode');
            pincodeInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            // Phone number formatting
            const phoneInput = document.getElementById('company_phone');
            phoneInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        });
    </script>
</body>
</html>