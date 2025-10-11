<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendamento Confirmado</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg text-center w-96">
        <h1 class="text-2xl font-bold text-green-700 mb-4">Agendamento Confirmado! ✅</h1>
        <p class="text-gray-700 mb-2">
            Seu horário foi confirmado com <strong>{{ $profissional }}</strong>.
        </p>
        <p class="text-gray-700 mb-4">
            Data: <strong>{{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}</strong><br>
            Horário: <strong>{{ $horario }}</strong>
        </p>
        <a href="/" class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700">
            Voltar para o site
        </a>
    </div>
</body>
</html>
