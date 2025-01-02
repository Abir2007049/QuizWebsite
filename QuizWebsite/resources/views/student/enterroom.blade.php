<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745; /* Green color */
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #28a745; /* Green color */
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745; /* Green color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .form-group input::placeholder {
            color: #888;
        }
        .form-group input:focus::placeholder {
            color: transparent;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Form</h2>
        <form action="{{ route('enter.room') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter your student ID" required>
            </div>
            <div class="form-group">
                <label for="room_name">Room Name</label>
                <input type="text" id="room_name" name="room_name" placeholder="Enter the room name" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
