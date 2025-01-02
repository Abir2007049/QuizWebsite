<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
        }

        /* Header Styles */
        header {
            background-color: #4caf50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h2 {
            font-size: 24px;
            margin: 0;
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
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #d4f1d4;
        }

        /* Main Styles */
        main {
            text-align: center;
            padding: 50px 20px;
        }

        h1 {
            font-size: 48px;
            color: #4caf50;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        form {
            display: inline-flex;
            gap: 20px;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #3a9c40;
            transform: translateY(-2px);
        }

        /* Footer Styles */
        footer {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            header, main, footer {
                padding: 10px;
            }

            h1 {
                font-size: 36px;
            }

            button {
                padding: 10px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div><h2>Welcome to EduHub</h2></div>
        <nav class="navbar">
            <a href="#apps">Apps</a>
            <a href="#blog">Blog</a>
            <a href="#support">Support</a>
        </nav>
    </header>
    <main>
        <h1>Welcome to EduHub</h1>
        <p>Empowering education through technology. Choose your role to get started:</p>
        <form action="{{ route('TorS') }}" method="GET">
            <button type="submit" name="role" value="student">I'm a Student</button>
            <button type="submit" name="role" value="teacher">I'm a Teacher</button>
        </form>
    </main>
    <footer>
        &copy; 2024 EduHub. All rights reserved.
    </footer>
</body>
</html>
