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
            background: url('{{ asset('images/quiz.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        /* Overlay to make text readable */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7); /* Adjust opacity as needed */
            z-index: -1;
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
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
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
            transition: color 0.3s, transform 0.2s;
        }

        .navbar a:hover {
            color: #d4f1d4;
            transform: translateY(-2px);
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        marquee {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
            display: block;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
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
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        footer:hover {
            background-color: #3a9c40;
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
        <marquee behavior="scroll" direction="left">
            Empowering education through technology. Choose your role to get started:
        </marquee>
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
