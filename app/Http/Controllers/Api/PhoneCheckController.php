<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PhoneCheckController extends Controller
{
    protected $externalApiService;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }

    /**
     * Check if a phone number already exists in the external CRM
     */
    public function checkPhoneNumber(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|string|regex:/^[0-9]{10}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Phone number must be exactly 10 digits',
                'errors' => $validator->errors()
            ], 400);
        }

        $phoneNumber = $request->input('phoneNumber');

        // Call the external API service
        $result = $this->externalApiService->checkPhoneNumber($phoneNumber);

        // Return standardized response
        return response()->json([
            'success' => $result['success'],
            'status' => $result['status'],
            'message' => $result['message'],
            'exists' => $result['exists'],
            'phoneNumber' => $phoneNumber
        ], $result['success'] ? 200 : 500);
    }
}
