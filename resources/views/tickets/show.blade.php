<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tiket - {{ $ticket['subject'] }}</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 400px; }
        a { text-decoration: none; color: #0066cc; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $ticket['subject'] }}</h1>
        <p><strong>ID Tiket:</strong> {{ $ticket['id'] }}</p>
        <p><strong>Status:</strong> {{ $ticket['status'] }}</p>

        <a href="{{ route('tickets.index') }}">&larr; Kembali ke Daftar Tiket</a>
    </div>
</body>
</html>