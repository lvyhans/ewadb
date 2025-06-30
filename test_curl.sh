#!/bin/bash

# Test form submission with course options
curl -X POST http://localhost:8000/applications \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "X-CSRF-TOKEN: test" \
  --data-urlencode "name=Test User" \
  --data-urlencode "email=test@example.com" \
  --data-urlencode "phone=1234567890" \
  --data-urlencode "country=USA" \
  --data-urlencode "source=Google" \
  --data-urlencode "remarks=Test application submission" \
  --data-urlencode "course_options[0][country]=Canada" \
  --data-urlencode "course_options[0][city]=Toronto" \
  --data-urlencode "course_options[0][college]=University of Toronto" \
  --data-urlencode "course_options[0][course]=Computer Science" \
  --data-urlencode "course_options[0][course_type]=Bachelor" \
  --data-urlencode "course_options[0][fees]=50000" \
  --data-urlencode "course_options[0][duration]=4 years" \
  --data-urlencode "course_options[0][college_detail_id]=123" \
  --data-urlencode "course_options[1][country]=Canada" \
  --data-urlencode "course_options[1][city]=Vancouver" \
  --data-urlencode "course_options[1][college]=UBC" \
  --data-urlencode "course_options[1][course]=Engineering" \
  --data-urlencode "course_options[1][course_type]=Bachelor" \
  --data-urlencode "course_options[1][fees]=45000" \
  --data-urlencode "course_options[1][duration]=4 years" \
  --data-urlencode "course_options[1][college_detail_id]=456"
