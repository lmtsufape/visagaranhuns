<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inspeções do Dia</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .line-title {
            border: 0;
            border-style: inset;
            border-top: 1px solid #000;
        }

        .line-title-2 {
            border: 1;
            border-style: inset;
            border-top: 0px solid #808080;
        }

        .line-title-3 {
            border: 1;
            border-style: inset;
            border-top: 0px solid #000;
            width: 175px;
        }
    </style>
</head>
<body>
{{-- <img style="position: absolute; width: 60px; height: auto;" src="{{asset('imagens/logo_atencao2.png')}}"> --}}
{{-- <img src="public/imagens/logo_atencao2.png" style="position: absolute; width: 60px; height: auto;"> --}}
<table style="width: 100%;">
    <tr>
        <td align="center">
        <span style="line-height: 1.6; font-weight: bold;">
          Secretária de Saúde e Vigilância Sanitária de Garanhuns - PE
          <br>Inspeção
        </span>
        </td>
    </tr>
</table>

<hr class="line-title">

@foreach ($emps as $indice)
    @if ($indice->email == "Empresa não cadastrada")
        <p align="left">
            {{$indice->nome}} <br>
        </p>
        <div class="row">
            <div class="col-5" style="line-height: 15px">
                <b>Endereço: {{$indice->endereco}}</b>
            </div>
            <div class="col-7" style="margin-left: 300px; line-height: 10px;">
                <b>CAA: cAMPI</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>CNPJ/CPF: {{$indice->cnpjcpf}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>CEP: {{$indice->cep}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Tipo: {{$indice->tipo}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Bairro: {{$indice->bairro}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Email: {{$indice->email}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Rua: {{$indice->rua}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Telefone 1: {{$indice->telefone1}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Complemento: {{$indice->complemento}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Telefone 2: {{$indice->telefone2}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b></b>
            </div>
        </div>
    @else
        <p align="left">
            {{$indice->nome}} <br>
        </p>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>CNPJ/CPF: {{$indice->cnpjcpf}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>CEP: {{$indice->endereco->cep}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Tipo: {{$indice->tipo}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Bairro: {{$indice->endereco->bairro}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Email: {{$indice->user->email}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Rua: {{$indice->endereco->rua}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Telefone 1: {{$indice->telefone[0]->telefone1}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b>Complemento: {{$indice->endereco->complemento}}</b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="line-height: 10px">
                <b>Telefone 2: {{$indice->telefone[0]->telefone2}}</b>
            </div>
            <div class="col-6" style="margin-left: 300px; line-height: 10px;">
                <b></b>
            </div>
        </div>
    @endif

    <hr class="line-title-2">

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Inspetor</th>
            <th scope="col">Agentes</th>
            <th scope="col">Cnae</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        @foreach ($inspecoes as $item)
            @if($item->empresa != null && $item->empresa->nome == $indice->nome || $item->nome_empresa == $indice->nome)
                <tbody>
                <tr>
                    <td>{{date('d-m-Y', strtotime($item->data))}}</td>
                    <td>{{$item->inspetor->user->name}}</td>
                    <td>
                        @foreach ($item->agentes as $agente)
                            {{$agente->user->name}}<br>
                        @endforeach
                    </td>
                    @if ($item->requerimento != null)
                        <td>{{$item->requerimento->cnae->descricao}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$item->status}}</td>
                </tr>
                </tbody>
            @elseif ($item->denuncia != null && $item->denuncia->empresaRelacionamento != null && $item->denuncia->empresaRelacionamento->nome == $indice->nome)
                <tbody>
                <tr>
                    <td>{{date('d-m-Y', strtotime($item->data))}}</td>
                    <td>{{$item->inspetor->user->name}}</td>
                    <td>
                        @foreach ($item->agentes as $agente)
                            {{$agente->user->name}}<br>
                        @endforeach
                    </td>
                    @if ($item->requerimento != null)
                        <td>{{$item->requerimento->cnae->descricao}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$item->status}}</td>
                </tr>
                </tbody>
            @elseif ($item->denuncia != null && $item->denuncia->empresaRelacionamento == null && $item->denuncia->empresa == $indice->nome)
                <tbody>
                <tr>
                    <td>{{date('d-m-Y', strtotime($item->data))}}</td>
                    <td>{{$item->inspetor->user->name}}</td>
                    <td>
                        @foreach ($item->agentes as $agente)
                            {{$agente->user->name}}<br>
                        @endforeach
                    </td>
                    @if ($item->requerimento != null)
                        <td>{{$item->requerimento->cnae->descricao}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$item->status}}</td>
                </tr>
                </tbody>
            @endif
        @endforeach
    </table>
@endforeach


</body>
</html>
