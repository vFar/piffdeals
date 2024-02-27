<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>Contact Form Submission</h1>
    <p>You have received a new contact form submission. Here are the details:</p>
    <p><strong>Name:</strong> {{ $formData['email'] ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $formData['title'] ?? 'N/A' }}</p>
    <p><strong>Message:</strong> {{ $formData['textMessage'] ?? 'N/A' }}</p>
</body>
</html>
