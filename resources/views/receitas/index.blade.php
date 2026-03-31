<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receitas') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Receitas Cadastradas</h1>
                        <p class="text-sm text-gray-500 mt-1">Listagem das receitas doces e salgadas do sistema.</p>
                    </div>

                    <a href="{{ route('receitas.create') }}" class="inline-block px-4 py-2 rounded text-sm font-semibold"
                        style="background:#4f46e5;color:#fff;text-decoration:none;">
                        Nova Receita
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Data de
                                    Registro</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Custo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($receitas as $receita)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $receita->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $receita->nome }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $receita->descricao }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">R$
                                        {{ number_format($receita->custo, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($receita->tipo_receita) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center justify-center gap-2">

                                            <a href="{{ route('receitas.show', $receita->id) }}"
                                                class="inline-block px-3 py-2 rounded text-xs font-medium"
                                                style="background:#2563eb;color:#fff;text-decoration:none;">
                                                Ver
                                            </a>

                                            <a href="{{ route('receitas.edit', $receita->id) }}"
                                                class="inline-block px-3 py-2 rounded text-xs font-medium"
                                                style="background:#f59e0b;color:#fff;text-decoration:none;">
                                                Editar
                                            </a>

                                            <form action="{{ route('receitas.destroy', $receita->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir esta receita?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-block px-3 py-2 rounded text-xs font-medium"
                                                    style="background:#dc2626;color:#fff;border:none;">
                                                    Excluir
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                        Nenhuma receita cadastrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
