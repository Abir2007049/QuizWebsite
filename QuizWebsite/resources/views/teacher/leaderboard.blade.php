<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard </title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .leaderboard-table {
            margin-top: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .leaderboard-table th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
        }
        .leaderboard-table td {
            background-color: #f2f2f2;
        }
        .leaderboard-table tr:nth-child(even) td {
            background-color: #e9ecef;
        }
        .rank {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .score {
            color: #28a745;
            font-weight: bold;
        }
        .username {
            font-weight: 600;
        }
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>

<div class="container">
   

    <!-- Leaderboard Table -->
    <div class="leaderboard-table">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="rank">Rank</th>
                    <th class="username">Username</th>
                    <th class="score">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $index => $entry)
                    <tr>
                        <td class="rank">{{ $index + 1 }}</td>
                        <td class="StudentId">{{ $entry->student_id }}</td>
                        <td class="score">{{ $entry->score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination (Optional, if you have many entries) -->
    <div class="d-flex justify-content-center">
        <!-- Example pagination buttons -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
