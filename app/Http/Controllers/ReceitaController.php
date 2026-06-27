<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    public function index(Request $request)
    {
        $receitas = $this->filtrarReceitas($request)->get();

        return view('receitas.index', compact('receitas'));
    }

    public function exportarPdf(Request $request)
    {
        $receitas = $this->filtrarReceitas($request)->get();

        $pdf = Pdf::loadView('receitas.pdf', [
            'receitas' => $receitas,
            'filtros' => $request->only(['data_registro', 'status']),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('receitas.pdf');
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
            'status' => ['required', 'in:ativo,inativo'],
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
            'status' => ['required', 'in:ativo,inativo'],
        ]);

        $receita->update($dados);

        return redirect()->route('receitas.index')->with('success', 'Receita atualizada com sucesso.');
    }

    public function destroy(Receita $receita)
    {
        $receita->delete();

        return redirect()->route('receitas.index')->with('success', 'Receita excluída com sucesso.');
    }

    private function filtrarReceitas(Request $request)
    {
        return Receita::query()
            ->when($request->filled('data_registro'), function ($query) use ($request) {
                $query->whereDate('data_registro', $request->data_registro);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('id', 'desc');
    }
}
