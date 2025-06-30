<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApplicationController;

// Create test route for course options
Route::post('/test-course-options', function (Request $request) {
    \Log::info('Test course options route accessed', [
        'request_data' => $request->all(),
        'has_course_options' => $request->has('course_options'),
        'course_options' => $request->course_options
    ]);
    
    $controller = new ApplicationController();
    return $controller->store($request);
})->name('test.course.options');
