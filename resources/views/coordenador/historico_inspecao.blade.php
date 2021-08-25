@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="barraMenu">
            <div class="d-flex">
                <div class="mr-auto p-2 styleBarraPrincipalMOBILE">
                    <a href="javascript: history.go(-1)" style="text-decoration:none;cursor:pointer;color:black;">
                        <div class="btn-group">
                            <div style="margin-top:1px;margin-left:5px;"><img
                                    src="{{ asset('/imagens/logo_voltar.png') }}" alt="Logo" style="width:13px;"/></div>
                            <div style="margin-top:2.4px;margin-left:10px;font-size:15px;">Voltar</div>
                        </div>
                    </a>
                </div>
                <div class="mr-auto p-2 styleBarraPrincipalPC">
                    <div class="form-group">
                        <div class="tituloBarraPrincipal">Histórico de Inspeções</div>
                        <div>
                            <div
                                style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">
                                Início > Estabelecimento > Inspeções > Histórico
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="barraMenu" style="margin-top:2rem; margin-bottom:8.5rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">

                    <div class="form-group col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label
                                    style="font-size:19px;margin-top:5px;margin-bottom:5px; font-family: 'Roboto', sans-serif;">INSPEÇÕES</label>
                            </div>
                            <div class="form-group col-md-6" style="align-content: right">
                                <label
                                    style="font-size:19px;margin-top:5px;margin-bottom:5px; margin-left:435px; font-family: 'Roboto', sans-serif;"><a
                                        type="button" class="btn btn-primary" href="{{ route('gerar.pdf') }}">Baixar</a>
                                </label>
                            </div>
                            @if ($message = Session::get('error'))
                                <div class="alert alert-warning alert-block fade show">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong style="margin-right: 30px;">{{$message}}</strong>
                                </div>
                            @endif
                            @if ($message = Session::get('success'))
                                <div class="alert alert-warning alert-block fade show">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong style="margin-right: 30px;">{{$message}}</strong>
                                </div>
                            @endif
                            @if ($message = Session::get('message'))
                                <div class="alert alert-warning alert-block fade show">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong style="margin-right: 30px;">{{$message}}</strong>
                                </div>
                            @endif
                            <div class="form col-md-12" style="margin-top:-10px;">
                                <table class="table table-responsive-lg table-hover" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold; margin-right:30px;">
                                            Data
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Status
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Inspetor
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Agentes
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Empresa
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Motivo
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Cnae
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Relatório
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Notificação
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Apagar
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($inspecoes as $item)
                                        <tr>
                                            <th class="subtituloBarraPrincipal"
                                                style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{date('d-m-Y', strtotime($item->data))}}</th>
                                            <th class="subtituloBarraPrincipal"
                                                style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->status}}</th>
                                            <th class="subtituloBarraPrincipal"
                                                style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->inspetor->user->name}}</th>
                                            <th class="subtituloBarraPrincipal"
                                                style="font-size:15px;  text-align:center; vertical-align:middle; color:black">
                                                @foreach ($item->agentes as $agente)
                                                    {{$agente->user->name}}<br>
                                                @endforeach
                                            </th>
                                            @if ($item->empresa != null || $item->motivo == 'Diversas')
                                                @if($item->nome_empresa != null)
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->nome_empresa}}</th>
                                                @else
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->empresa->nome}}</th>
                                                @endif
                                            @elseif ($item->denuncia != null)
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->denuncia->empresa}}</th>
                                            @endif
                                            <th class="subtituloBarraPrincipal"
                                                style="font-size:15px;  text-align:center; vertical-align:middle; color:black">{{$item->motivo}}</th>
                                            @if ($item->requerimento != null)
                                                @if($item->requerimento->tipo == 'Diversas')
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px;  text-align:left; vertical-align:middle; color:black">Indefinido</th>
                                                @else
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px;  text-align:left; vertical-align:middle; color:black">{{$item->requerimento->cnae->descricao}}</th>
                                                @endif
                                            @else
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px;  text-align:left; vertical-align:middle; color:black"></th>
                                            @endif
                                            {{-- <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">
                                                <a href="{{ route('show.relatorio.coordenador') }}" type="button" class="btn btn-primary">Avaliar</a>
                                            </th> --}}
                                            @if ($item->relatorio == null)
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px;  text-align:center; vertical-align:middle; color:black">
                                                    <button type="button" class="btn btn-warning" disabled>Não
                                                        Finalizado
                                                    </button>
                                                </th>
                                            @else
                                                @if ($item->relatorio->status == "reprovado")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <a href="{{ route('show.relatorio.coordenador.verificar', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-danger">Reprovado</a>
                                                        <a href="{{ route('imprimir.relatorio', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-primary">Imprimir</a>
                                                        {{-- <button type="button" class="btn btn-success">Reprovado</button> --}}
                                                    </th>
                                                @elseif ($item->relatorio->coordenador == "avaliacao")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <a href="{{ route('show.relatorio.coordenador', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-primary">Avaliar</a>
                                                        <a href="{{ route('imprimir.relatorio', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-primary">Imprimir</a>
                                                    </th>
                                                @elseif ($item->relatorio->coordenador == "aprovado")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <a href="{{ route('show.relatorio.coordenador.verificar', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-success">Aprovado</a>
                                                        <a href="{{ route('imprimir.relatorio', ['relatorio_id' => Crypt::encrypt($item->relatorio_id), 'inspecao_id' => Crypt::encrypt($item->id)]) }}"
                                                           type="button" class="btn btn-primary">Imprimir</a>
                                                        {{-- <button type="button" class="btn btn-success">Aprovado</button> --}}
                                                    </th>
                                                @endif
                                            @endif
                                            <td class="subtituloBarraPrincipal"
                                                style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                @if ($item->notificacoes == null || $item->notificacoes->count() <= 0)
                                                    <button type="button" class="btn btn-primary" disabled>
                                                        Notificação
                                                    </button>
                                                @elseif ($item->notificacoes->contains('status','pendente'))
                                                    <a href="{{ route('show.notificacao.coordenador', ['inspecaoId' => Crypt::encrypt($item->id)]) }}"
                                                       type="button" class="btn btn-primary">
                                                        Avaliar
                                                    </a>
                                                @elseif ($item->notificacoes->contains('status','aprovado'))
                                                    <a href="{{ route('show.notificacao.coordenador.verificar', ['inspecaoId' => Crypt::encrypt($item->id)]) }}"
                                                       type="button" class="btn btn-success">
                                                        Aprovado
                                                    </a>
                                                @elseif ($item->notificacoes->contains('status','reprovado'))
                                                    <a href="{{ route('show.notificacao.coordenador.verificar', ['inspecaoId' => Crypt::encrypt($item->id)]) }}"
                                                       type="button" class="btn btn-danger">
                                                        Reprovado
                                                    </a>
                                                @endif
                                            </td>
                                            @if ($item->relatorio_status == "aprovado" || $item->relatorio_status == "avaliacao" || $item->relatorio_status == "reprovado")
                                                <td class="subtituloBarraPrincipal"
                                                    style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                    <button type="button" class="btn btn-danger" disabled>
                                                        <img src="{{asset('imagens/logo_lixo.png')}}"
                                                             style="width:15px">
                                                    </button>
                                                </td>
                                            @else
                                                <td class="subtituloBarraPrincipal"
                                                    style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                    <a type="button" class="btn btn-danger"
                                                       onclick="inspecaoId('{{$item->id}}')" data-toggle="modal"
                                                       data-target="#exampleModal1">
                                                        <img src="{{asset('imagens/logo_lixo.png')}}"
                                                             style="width:15px">
                                                    </a>
                                                    {{-- <a href="{{ route('deletar.inspecao', ['inspecaoId' => Crypt::encrypt($item->id)]) }}" type="button" class="btn btn-danger">
                                                        <img src="{{asset('imagens/logo_lixo.png')}}" style="width:15px">
                                                    </a> --}}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal - apagar inspeção -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:red;">
                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" alt="Logo" style=" margin-right:15px;"/><h5
                        class="modal-title" id="exampleModalLabel"
                        style="font-size:20px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">
                        Deletar Inspeção</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="font-family: 'Roboto', sans-serif;">Tem certeza de que deseja deletar
                            esta inspeção <label id="nomeDoEstabelecimento"
                                                 style="font-weight:bold; font-family: 'Roboto', sans-serif;"></label>?
                        </div>
                        {{-- <div class="col-12" style="font-family: 'Roboto', sans-serif; margin-top:10px;"><img src="{{ asset('/imagens/logo_bloqueado.png') }}" alt="Logo" style="width:15px; margin-right:5px;"/> Essa ação não poderá ser desfeita</div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="width:100px;">
                        Não
                    </button>
                    <form method="POST" action="{{ route('deletar.inspecao') }}">
                        @csrf

                        <input id="inspecaoId" type="hidden" name="inspecaoId" value="">

                        <div class="col-md-12" style="padding-left:0">
                            <button type="submit" class="btn btn-success botao-form" style="width:100%">
                                Sim, deletar inspeção
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

<script type="text/javascript">

    window.inspecaoId = function ($id) {
        console.log($id);
        document.getElementById("inspecaoId").value = $id;
    }

</script>
