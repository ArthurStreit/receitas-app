<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatorio de Receitas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 22px;
        }

        p {
            margin: 0 0 4px;
        }

        .header {
            margin-bottom: 18px;
        }

        .filtros {
            margin-bottom: 14px;
            padding: 10px 12px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #e5e7eb;
            font-size: 11px;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatorio de Receitas</h1>
        <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="filtros">
        <p><strong>Data:</strong>
            {{ !empty($filtros['data_registro']) ? \Carbon\Carbon::parse($filtros['data_registro'])->format('d/m/Y') : 'Todas' }}
        </p>
        <p><strong>Status:</strong> {{ !empty($filtros['status']) ? ucfirst($filtros['status']) : 'Todos' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descricao</th>
                <th>Data de Registro</th>
                <th>Custo</th>
                <th>Tipo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($receitas as $receita)
                <tr>
                    <td>{{ $receita->id }}</td>
                    <td>{{ $receita->nome }}</td>
                    <td>{{ $receita->descricao }}</td>
                    <td>{{ $receita->data_registro->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($receita->custo, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($receita->tipo_receita) }}</td>
                    <td>{{ ucfirst($receita->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Nenhuma receita encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
