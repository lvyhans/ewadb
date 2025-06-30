<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Quick Application Test</h1>
    <form id="test_form" action="{{ route('applications.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name (required):</label>
            <input type="text" name="name" id="name" value="Test User" required>
        </div>
        <div>
            <label for="email">Email (required):</label>
            <input type="email" name="email" id="email" value="test@example.com" required>
        </div>
        <div>
            <label for="phone">Phone (required):</label>
            <input type="tel" name="phone" id="phone" value="1234567890" required>
        </div>
        <div>
            <label for="country">Country:</label>
            <input type="text" name="country" id="country" value="USA">
        </div>
        <div>
            <label for="source">Source (required):</label>
            <select name="source" id="source" required>
                <option value="">Select Source</option>
                <option value="Google" selected>Google</option>
                <option value="Facebook">Facebook</option>
                <option value="Reference">Reference</option>
            </select>
        </div>
        <div>
            <label for="remarks">Remarks (required):</label>
            <textarea name="remarks" id="remarks" required>Test application submission</textarea>
        </div>
        
        <!-- Test Course Options (simulating course finder data) -->
        <h3>Course Options (from course finder)</h3>
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <h4>Course Option 1</h4>
            <input type="hidden" name="course_options[0][country]" value="Canada">
            <input type="text" name="course_options[0][city]" value="Toronto" placeholder="City">
            <input type="text" name="course_options[0][college]" value="University of Toronto" placeholder="College">
            <input type="text" name="course_options[0][course]" value="Computer Science" placeholder="Course">
            <input type="text" name="course_options[0][course_type]" value="Bachelor" placeholder="Course Type">
            <input type="text" name="course_options[0][fees]" value="50000" placeholder="Fees">
            <input type="text" name="course_options[0][duration]" value="4 years" placeholder="Duration">
            <input type="hidden" name="course_options[0][college_detail_id]" value="123">
        </div>
        
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <h4>Course Option 2</h4>
            <input type="hidden" name="course_options[1][country]" value="Canada">
            <input type="text" name="course_options[1][city]" value="Vancouver" placeholder="City">
            <input type="text" name="course_options[1][college]" value="UBC" placeholder="College">
            <input type="text" name="course_options[1][course]" value="Engineering" placeholder="Course">
            <input type="text" name="course_options[1][course_type]" value="Bachelor" placeholder="Course Type">
            <input type="text" name="course_options[1][fees]" value="45000" placeholder="Fees">
            <input type="text" name="course_options[1][duration]" value="4 years" placeholder="Duration">
            <input type="hidden" name="course_options[1][college_detail_id]" value="456">
        </div>
        
        <button type="submit">Submit Test Application</button>
    </form>
    
    <script>
        document.getElementById('test_form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            console.log('Test form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            fetch('{{ route("applications.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response body:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON:', data);
                } catch (e) {
                    console.log('Response is not JSON');
                }
                alert('Form submitted! Check console for details.');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        });
    </script>
</body>
</html>
