<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NaoMedicoRequest;
use App\Model\{NaoMedico, Usuario};

class NaoMedicoController extends Controller
{
    public function lista()
    {
        if(!isset($_GET['q']))
            $nmedicos = NaoMedico::paginate( config('prontuario.paginacao') );
        else {
            $nmedicos = NaoMedico::whereHas('usuario', function($query) {
                $query->where('nome', 'like', '%'.$_GET['q'].'%')
                    ->orWhere('email', 'like', '%'.$_GET['q'].'%')
                    ->orWhere('cpf', 'like', '%'.$_GET['q'].'%');
            })->paginate( config('prontuario.paginacao') );
        }
        
        return view('nao-medicos.lista', compact('nmedicos'));
    }

    public function gerenciar($id)
    {
        $medico = NaoMedico::find($id);

        if(!$medico)
            return redirect('nao-medicos');

        return view('nao-medicos.gerenciar', compact('medico'));
    }

    public function criar()
    {
        $nmedico = new NaoMedico;
        $nmedico->usuario = new Usuario;
        return view('nao-medicos.manipular', compact('nmedico'));
    }

    public function salvar(NaoMedicoRequest $requisicao)
    {
        $usuario = Usuario::create(
            $requisicao->all() +
            ['senha' => bcrypt($requisicao->cpf)]
        );
        NaoMedico::create(
            [ 'usuario_id' => $usuario->id ] +
            $requisicao->all()
        );

        return redirect('nao-medicos')->withMsg($usuario->nome . ' foi cadastrada(o)!');
    }

    public function edicao($id)
    {
        $nmedico = NaoMedico::find($id);
        return view('nao-medicos.manipular', compact('nmedico'));
    }

    public function editar(NaoMedicoRequest $requisicao, $id)
    {
        $nmedico = NaoMedico::find($id);
        $nmedico->usuario->fill($requisicao->all());
        $nmedico->usuario->save();

        $nmedico->fill($requisicao->all());
        $nmedico->save();

        return redirect('nao-medicos/gerenciar/'.$id)->withMsg($nmedico->usuario->nome . ' foi editada(o)!');
    }

    public function historico($id)
    {
        $nmedico = NaoMedico::find($id);
        $nmedico->historico = ($nmedico->historico) ? 0 : 1;
        $nmedico->save();

        return redirect('nao-medicos/gerenciar/'.$id)->withMsg($nmedico->usuario->nome . ' foi alterada(o)!');
    }
}
