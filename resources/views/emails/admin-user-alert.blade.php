<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .card { max-width: 500px; margin: 20px auto; border: 1px solid #000; border-radius: 8px; overflow: hidden; }
        .head { background: #000; color: #fff; padding: 15px; text-align: center; }
        .body { padding: 20px; text-align: center; }
        .info { background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="head">
            <h3>New User Registration</h3>
        </div>
        <div class="body">
            <p>A new client has just created an account on your website.</p>
            <div class="info">
                <strong>Name:</strong> {{ $user->name }}<br>
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <p>You can now reach out to them for hospitality solutions.</p>
        </div>
    </div>
</body>
</html>