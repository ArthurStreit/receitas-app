<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Receita
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('receitas.update', $receita->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('receitas.form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('receitas.index') }}"
                            class="inline-block px-4 py-2 rounded text-sm font-medium"
                            style="background:#6b7280;color:#fff;text-decoration:none;">
                            Cancelar
                        </a>

                        <button type="submit" class="inline-block px-4 py-2 rounded text-sm font-medium"
                            style="background:#4f46e5;color:#fff;border:none;">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
