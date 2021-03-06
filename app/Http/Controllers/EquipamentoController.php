<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use Illuminate\Http\Request;
use App\Rules\MultipleEmailRule;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    if ($request->busca != null && $request->buscaatividade != null){

        $equipamentos = Equipamento::where('ip','LIKE',"%{$request->busca}%")
        ->orWhere('nome','LIKE',"%{$request->busca}%")
        ->where('equipamentoativo', $request->buscaatividade)->orderByDesc('equipamentoativo')->paginate(15);

        return view('equipamentos.index',[
        'equipamentos' => $equipamentos,
        ]);

    } else if(isset($request->busca)) {

        $equipamentos = Equipamento::where('ip','LIKE',"%{$request->busca}%")
        ->orWhere('nome','LIKE',"%{$request->busca}%")->orderByDesc('equipamentoativo')->paginate(15);

        return view('equipamentos.index',[
        'equipamentos' => $equipamentos,
        ]);


    } else if(isset($request->buscaatividade)) {

        $equipamentos = Equipamento::where('equipamentoativo', $request->buscaatividade)->orderByDesc('equipamentoativo')->paginate(15);

        return view('equipamentos.index',[
        'equipamentos' => $equipamentos,
        ]);


    } else {
        

        return view('equipamentos.index',[
            'equipamentos' => Equipamento::orderByDesc('equipamentoativo')->paginate(15),
        ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');
        return view('equipamentos.create',[
            'equipamento' => new Equipamento,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'ip' => 'required|ip|unique:equipamentos,ip',
            'nome' => 'required',
            'emails' => ['required', new MultipleEmailRule]
          ]);

        $equipamento = new Equipamento;
        $equipamento->ip = $request->ip;
        $equipamento->nome = $request->nome;
        $equipamento->emails = $request->emails;
        $equipamento->equipamentoativo = $request->equipamentoativo;
        $equipamento->save();
        return redirect("/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function show(Equipamento $equipamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipamento $equipamento)
    {
        $this->authorize('admin');
        return view('equipamentos.edit')->with('equipamento', $equipamento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        $this->authorize('admin');
        $validated = $request->validate([
            'ip' => 'required|ip',
            'nome' => 'required',
            'emails' => ['required', new MultipleEmailRule]
          ]);          
        $equipamento->equipamentoativo = $request->equipamentoativo;
        $equipamento->update($validated);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipamento $equipamento)
    {
        $this->authorize('admin');
        $equipamento->delete();
        return redirect('/');
    }
}
