<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PacienteRequest;
use App\Model\Paciente;

class PacienteController extends Controller
{
    public function lista()
    {

        if(!isset($_GET['q']))
            $pacientes = Paciente::paginate( config('prontuario.paginacao') );
        else {


            if (\DateTime::createFromFormat('d/m/Y', $_GET['q']) !== false) {
                $d = explode('/', $_GET['q']);
                $tmp = '';
                foreach ($d as $valor) {
                    $tmp = $valor . '-' . $tmp;
                }
                $tmp = substr($tmp, 0, -1);
                $_GET['q'] = $tmp;
            }


            $pacientes = Paciente::where('nome', 'like', '%'.$_GET['q'].'%')
                ->orWhere('email', 'like', '%'.$_GET['q'].'%')
                ->orWhere('cpf', 'like', '%'.$_GET['q'].'%')
                ->orWhere('prontuario', 'like', '%'.$_GET['q'].'%')
                ->orWhere('nascimento', 'like', '%'.$_GET['q'].'%')
            ->paginate( config('prontuario.paginacao') );
        }

        return view('pacientes.lista', compact('pacientes'));
    }

    public function gerenciar($id)
    {
        $paciente = Paciente::find($id);

        if(!$paciente)
            return redirect('pacientes');

        return view('pacientes.gerenciar', compact('paciente'));
    }


    public function criar()
    {
        $paciente = new Paciente;
        $paciente->prontuario = time();
        return view('pacientes.manipular', compact('paciente'));
    }

    public function salvar(PacienteRequest $requisicao)
    {
        if($requisicao->naturalidade == null)
            $requisicao->merge(['naturalidade' => ' ']);

        $paciente = Paciente::create($requisicao->all());

        if($requisicao->foto != null)
            $requisicao->foto->storeAs('public/pacientes', $paciente->id.'.jpg');

        return redirect('pacientes')->withMsg($requisicao->nome . ' foi cadastrada(o)!');
    }

    public function apagar($id)
    {
        $paciente = Paciente::find($id);
        $paciente->delete();

        return redirect($_SERVER['HTTP_REFERER'])->withMsg($paciente->nome . ' foi apagada(o)!');
    }

    public function edicao($id)
    {
        $paciente = Paciente::find($id);
        return view('pacientes.manipular', compact('paciente'));
    }

    public function editar(PacienteRequest $requisicao, $id)
    {
        if($requisicao->naturalidade == null)
            $requisicao->merge(['naturalidade' => ' ']);

        $paciente = Paciente::find($id);
        $paciente->fill($requisicao->all());
        $paciente->save();

        return redirect('pacientes/gerenciar/'.$id)->withMsg($paciente->nome . ' foi editada(o)!');
    }
}
