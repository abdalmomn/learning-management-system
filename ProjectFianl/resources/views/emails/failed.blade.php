<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Result - Failed</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
        }
        .certificate-container {
            border: 5px solid #f5c6cb;
            padding: 20px;
            margin: 50px auto;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f8d7da;
        }
        .certificate-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .certificate-body {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .certificate-footer {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
<div class="certificate-container">
    <div class="certificate-header">
        Test Result - Failed
    </div>
    <div class="certificate-body">
        <p>Sorry, <strong>{{ $data['name'] }}</strong>.</p>
        <p>You did not achieve a passing score in the course <strong>{{ $data['courseName'] }}</strong>.</p>
        <p>We encourage you to review the material and try again.</p>
    </div>
    <div class="certificate-footer">
        <p>Issued on: {{ date('Y-m-d') }}</p>
        <p>Best of luck for your next attempt.</p>
    </div>
</div>
</body>
</html>
