<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    public function index()
    {
        $receitas = Receita::orderBy('id', 'desc')->get();

        return view('receitas.index', compact('receitas'));
    }

    public function create()
    {
        return view('receitas.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'data_registro' => ['required', 'date'],
            'custo' => ['required', 'numeric'],
            'tipo_receita' => ['required', 'in:doce,salgada'],
        ]);

        Receita::create($dados);

        return redirect()->route('receitas.index')->with('success', 'Receita cadastrada com sucesso.');
    }

    public function show(Receita $receita)
    {
        return view('receitas.show', compact('receita'));
    }

    public function edit(Receita $receita)
    {
        return view('receitas.edit', compact('receita'));
    }

    public function update(Request $request, Receita $receita)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'data_registro' => ['required', 'date'],
            'custo' => ['required', 'numeric'],
            'tipo_receita' => ['required', 'in:doce,salgada'],
        ]);

        $receita->update($dados);

        return redirect()->route('receitas.index')->with('success', 'Receita atualizada com sucesso.');
    }

    public function destroy(Receita $receita)
    {
        $receita->delete();

        return redirect()->route('receitas.index')->with('success', 'Receita excluída com sucesso.');
    }
}