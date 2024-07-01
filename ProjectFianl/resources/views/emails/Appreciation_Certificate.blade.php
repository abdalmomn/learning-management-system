<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement - Very Good</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .certificate-container {
            border: 5px solid #ccc;
            padding: 20px;
            margin: 50px auto;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        Certificate of Achievement - Very Good
        <p>{{$data['message']}}</p>
    </div>
    <div class="certificate-body">
        <p>Congratulations, <strong>{{ $data['name'] }}</strong>!</p>
        <p>You have achieved a very good score of <strong>{{ $data['mark'] }}</strong> in the course <strong>{{ $data['courseName'] }}</strong>.</p>
    </div>
    <div class="certificate-footer">
        <p>Issued on: {{ date('Y-m-d') }}</p>
        <p>Keep up the great work!</p>
    </div>
</div>
</body>
</html>
