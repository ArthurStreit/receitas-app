<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">

            <h2 class="text-2xl font-bold text-center mb-6">
                Login do Sistema
            </h2>

            <!-- Status -->
            <x-auth-session-status class="mb-4 text-green-600" :status="session('status')" />

            <!-- Errors -->
            <x-auth-validation-errors class="mb-4 text-red-600" :errors="$errors" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Login -->
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">
                        Usuário
                    </label>

                    <input id="login"
                           name="login"
                           type="text"
                           value="{{ old('login') }}"
                           required
                           autofocus
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Senha -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Senha
                    </label>

                    <input id="password"
                           name="password"
                           type="password"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Remember -->
                <div class="mt-4 flex items-center">
                    <input id="remember"
                           name="remember"
                           type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm">
                    
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Lembrar-me
                    </label>
                </div>

                <!-- Botão -->
                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                        Entrar
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>