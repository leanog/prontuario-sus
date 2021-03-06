@extends('layouts.app')

@section('titulo', 'Gerenciamento de secretários')

@section('lateral')
    {{--  <li><a href="#">Item person</a></li>  --}}
@endsection

@section('conteudo')

    <p style="text-align:center">
        @if(session('msg'))
            <span class="texto-verde">
                {{ session('msg') }}
            </span>
        @endif

         @if(session('erro'))
            <span class="texto-vermelho">
                {{ session('erro') }}
            </span>
        @endif
    </p>

    {{ Form::open(['url' => 'secretarios', 'method' => 'get']) }}
        <section>
            <div>
                {{ Form::search('q', '',['placeholder' => 'Buscar por nome, email ou CPF']) }}
                {{ Form::submit('Buscar', ['class' => 'btn verde', 'style' => 'flex-grow: 1; margin-left: 3px']) }}
            </div>
        </section>
    {{ Form::close() }}


    <p>
        Aqui você pode gerenciar qualquer secretário registrado no sistema, veja a seguir alguns dos quais estão na sua base de dados:
    </p>
    <br>
    <a class="btn verde" href="{{ url('secretarios/novo') }}">Cadastrar novo secretário</a>

    <table>
        <tr>
            <td>Ações</td>
            <td>Nome</td>
            <td>Email</td>
            <td>Cargo</td>
            <td>CPF</td>
        </tr>

        @foreach($secretarios as $secretario)
            <tr>
                <td>
                    <a href="{{ url('secretarios/gerenciar/' . $secretario->id) }}" class="btn azul">Gerenciar</a>
                </td>
                <td>{{ $secretario->usuario->nome }}</td>
                <td>{{ $secretario->usuario->email }}</td>
                <td>{{ $secretario->cargo }}</td>
                <td>{{ $secretario->usuario->cpf }}</td>
            </tr>
        @endforeach
    </table>

    <section style="text-align:center">
        {{ $secretarios->links() }}
    </section>
@endsection