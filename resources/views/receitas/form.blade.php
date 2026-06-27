<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $receita->nome ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>

    <div>
        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea name="descricao" id="descricao" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('descricao', $receita->descricao ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="data_registro" class="block text-sm font-medium text-gray-700">Data de Registro</label>
            <input type="date" name="data_registro" id="data_registro"
                   value="{{ old('data_registro', isset($receita) ? $receita->data_registro : date('Y-m-d')) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div>
            <label for="custo" class="block text-sm font-medium text-gray-700">Custo</label>
            <input type="number" step="0.01" name="custo" id="custo" value="{{ old('custo', $receita->custo ?? '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div>
            <label for="tipo_receita" class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="tipo_receita" id="tipo_receita"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Selecione</option>
                <option value="doce" {{ old('tipo_receita', $receita->tipo_receita ?? '') === 'doce' ? 'selected' : '' }}>Doce</option>
                <option value="salgada" {{ old('tipo_receita', $receita->tipo_receita ?? '') === 'salgada' ? 'selected' : '' }}>Salgada</option>
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Selecione</option>
                <option value="ativo" {{ old('status', $receita->status ?? 'ativo') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ old('status', $receita->status ?? '') === 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
    </div>
</div>
