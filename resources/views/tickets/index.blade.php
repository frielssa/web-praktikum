<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tiket</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        a { text-decoration: none; color: #0066cc; }
    </style>
</head>
<body>
    <h1>Daftar Tiket</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket['id'] }}</td>
                    <td>{{ $ticket['subject'] }}</td>
                    <td>{{ $ticket['status'] }}</td>
                    <td>
                        <a href="{{ route('tickets.show', ['ticket' => $ticket['id']]) }}">Detail HTML</a> | 
                        <a href="{{ route('tickets.show-json', ['ticket' => $ticket['id']]) }}" target="_blank">Detail JSON</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>