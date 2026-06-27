<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes da Receita
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <span class="block text-sm font-medium text-gray-500">Nome</span>
                        <p class="mt-1 text-lg text-gray-900">{{ $receita->nome }}</p>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500">Descrição</span>
                        <p class="mt-1 text-lg text-gray-900">{{ $receita->descricao }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Data de Registro</span>
                            <p class="mt-1 text-lg text-gray-900">
                                {{ \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-gray-500">Custo</span>
                            <p class="mt-1 text-lg text-gray-900">R$ {{ number_format($receita->custo, 2, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-gray-500">Tipo</span>
                            <p class="mt-1 text-lg text-gray-900">{{ ucfirst($receita->tipo_receita) }}</p>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-gray-500">Status</span>
                            <p class="mt-1 text-lg text-gray-900">{{ ucfirst($receita->status) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('receitas.index') }}" class="inline-block px-4 py-2 rounded text-sm font-medium"
                        style="background:#6b7280;color:#fff;text-decoration:none;">
                        Voltar
                    </a>

                    <a href="{{ route('receitas.edit', $receita->id) }}"
                        class="inline-block px-4 py-2 rounded text-sm font-medium"
                        style="background:#f59e0b;color:#fff;text-decoration:none;">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
