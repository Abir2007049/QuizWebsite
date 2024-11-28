<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar {
            display: flex;
            gap: 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        main {
            text-align: center;
            padding: 50px 20px;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        form {
            display: inline-flex;
            gap: 20px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div><h2>Welcome Page</h2></div>
        <nav class="navbar">
            <a href="#apps">Apps</a>
            <a href="#blog">Blog</a>
            <a href="#support">Support</a>
        </nav>
    </header>
    <main>
        <h1>Welcome</h1>
        <p>Select your role:</p>
        <form action="{{ route('TorS') }}" method="GET">
            <button type="submit" name="role" value="student">Student</button>
            <button type="submit" name="role" value="teacher">Teacher</button>
        </form>
    </main>
    <footer>
        &copy; 2024 Welcome Page. All rights reserved.
    </footer>
</body>
</html>
