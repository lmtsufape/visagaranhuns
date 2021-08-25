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
                        <div class="tituloBarraPrincipal">Requerimentos</div>
                        <div>
                            <div
                                style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">
                                Início > Estabelecimento > {{$nome}} > Requerimentos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label
                                    style="font-size:19px;margin-top:5px;margin-bottom:5px; font-family: 'Roboto', sans-serif;">REQUERIMENTOS</label>
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
                            <div class="form col-md-12" style="margin-top:-10px;">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">Código
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">CNAE
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Requerimento
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Documentação
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cnaes as $item)
                                        @foreach ($check as $item2)
                                            @if ($item->areas_id == $item2->area && $item2->status == "pendente")
                                                <tr>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">{{$item->codigo}}</th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">{{$item->descricao}}</th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <button type="button"
                                                                class="btn btn-success btn-sm subtituloBarraPrincipal"
                                                                style="color:white; font-size:15px;"
                                                                onclick="statusCNAERequisicaoRT('criarRequisicao','{{$item->descricao}}',null, '{{$item->id}}', '{{$resptecnico}}', '{{$empresas->id}}')"
                                                                data-toggle="modal"
                                                                data-target="#requerimentoCnaeRequisicaoRTModal">Criar
                                                        </button>
                                                    </th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <button class="btn btn-warning btn-sm subtituloBarraPrincipal"
                                                                style="font-size:15px; color:black; cursor:pointer;"
                                                                data-toggle="modal" data-target="#statusPendente">
                                                            Pendente
                                                        </button>
                                                    </th>
                                                </tr>
                                            @elseif ($item->areas_id == $item2->area && $item2->status == "completo")
                                                <tr>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">{{$item->codigo}}</th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">{{$item->descricao}}</th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <button type="button"
                                                                class="btn btn-success btn-sm subtituloBarraPrincipal"
                                                                style="color:white; font-size:15px; "
                                                                onclick="statusCNAERequisicaoRT('criarRequisicao','{{$item->descricao}}',null, '{{$item->id}}', '{{$resptecnico}}', '{{$empresas->id}}')"
                                                                data-toggle="modal"
                                                                data-target="#requerimentoCnaeRequisicaoRTModal">Criar
                                                        </button>
                                                    </th>
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                        <button class="btn btn-success btn-sm subtituloBarraPrincipal"
                                                                style="font-size:15px; color:black; cursor:pointer;"
                                                                data-toggle="modal" data-target="#statusCompleto">
                                                            Completo
                                                        </button>
                                                    </th>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    {{-- @if(isset($resultados))
                                        @foreach($resultados as $item)
                                            <tr>
                                                <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">{{$item->codigo}}</th>
                                                <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">{{$item->descricao}}</th>
                                                {{-- tipo --}}
                                    {{-- @if($item->tipo == "")
                                        <th class="subtituloBarraPrincipal" style="font-size:15px; color:black"></th>
                                    @else
                                        @if($item->tipo == "primeira_licenca")
                                            <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">Primeira licença</th>
                                        @elseif($item->tipo == "renovacao")
                                            <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">Renovação</th>
                                        @endif
                                    @endif --}}
                                    {{-- status --}}
                                    {{-- @if($item->status == "")
                                        <th class="subtituloBarraPrincipal" style="font-size:15px; color:black; cursor:pointer;" onclick="statusCNAERequisicaoRT('criarRequisicao','{{$item->descricao}}',null, '{{$item->id}}')" data-toggle="modal" data-target="#requerimentoCnaeRequisicaoRTModal">Nenhum requerimento</th>
                                    @elseif($item->status == "aprovado")
                                        <th class="subtituloBarraPrincipal" style="font-size:15px; color:#0e6b0e; cursor:pointer;" onclick="statusCNAERequisicaoRT('aprovado','{{$item->descricao}}',null,null)" data-toggle="modal" data-target="#statusCnaeRequisicaoRTModalAprovado"><img src="{{ asset('/imagens/logo_aprovado.png') }}" width="20px" alt="Logo" style="margin-right:13px;"/> Aprovado</th>
                                    @elseif($item->status == "reprovado")
                                        <th class="subtituloBarraPrincipal" style="font-size:15px; color:#c4302b; cursor:pointer;" onclick="statusCNAERequisicaoRT('reprovado','{{$item->descricao}}','{{$item->aviso}}',null)" data-toggle="modal" data-target="#statusCnaeRequisicaoRTModalReprovado"><img src="{{ asset('/imagens/logo_atencao4.png') }}" width="20px" alt="Logo" style="margin-right:13px;"/> Reprovado</th>
                                    @elseif($item->status == "pendente")
                                        <th class="subtituloBarraPrincipal" style="font-size:15px; color:#e1ad01;"><img src="{{ asset('/imagens/logo_atencao.png') }}" width="22px" alt="Logo" style="margin-right:13px;"/>Pendente</th>
                                    @endif --}}
                                    {{-- </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th></th>
                                    <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">Nenhum cnae cadastrado.</th>
                                    <th></th>
                                    <th></th>
                                </tr>

                            @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label
                                    style="font-size:19px;margin-top:5px;margin-bottom:5px; font-family: 'Roboto', sans-serif;">HISTÓRICO</label>
                            </div>
                            <div class="form col-md-12" style="margin-top:-10px;">
                                <table class="table table-hover" cellspacing="0" cellpadding="4" width="100%"
                                       style="border:1px solid #000;">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">Código
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">CNAE
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">Tipo
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">Status
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; color:black; font-weight:bold">Data
                                        </th>
                                        <th scope="col" class="subtituloBarraPrincipal"
                                            style="font-size:15px; text-align:center; vertical-align:middle; color:black; font-weight:bold">
                                            Aviso
                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requerimentos as $indice)
                                        @if ($indice->status == "reprovado")
                                            <tr>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->cnae->codigo}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->cnae->descricao}}</th>
                                                @if ($indice->tipo == "Primeira Licenca")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Primeira Licença
                                                    </th>
                                                @elseif ($indice->tipo == "Renovacao")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Renovação
                                                    </th>
                                                @elseif ($indice->tipo == "Renovacao Segunda Via")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Renovação Segunda Via
                                                    </th>
                                                @elseif ($indice->tipo == "Primeira Licenca Segunda Via")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Primeira Licença Segunda Via
                                                    </th>
                                                @elseif ($indice->tipo == "Dispensa CNAE")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Dispensa CNAE
                                                    </th>
                                                @endif
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->status}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{date('d-m-Y', strtotime($indice->data))}}</th>
                                                <input type="hidden" id="avisoTempRequerimentoRt{{$indice->id}}"
                                                       value="{{ $indice->aviso }}">
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; text-align:center; vertical-align:middle; color:black">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                            style="font-size:15px;"
                                                            onclick="avisoRequerimentoRt('{{$indice->id}}')"
                                                            data-toggle="modal" data-target="#exampleModalCenter">Abrir
                                                    </button>
                                                </th>
                                            </tr>
                                        @else
                                            <tr>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->cnae->codigo}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->cnae->descricao}}</th>
                                                @if ($indice->tipo == "Primeira Licenca")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Primeira Licença
                                                    </th>
                                                @elseif ($indice->tipo == "Renovacao")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Renovação
                                                    </th>
                                                @elseif ($indice->tipo == "Renovacao Segunda Via")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Renovação Segunda Via
                                                    </th>
                                                @elseif ($indice->tipo == "Primeira Licenca Segunda Via")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Primeira Licença Segunda Via
                                                    </th>
                                                @elseif ($indice->tipo == "Dispensa CNAE")
                                                    <th class="subtituloBarraPrincipal"
                                                        style="font-size:15px; color:black">Dispensa CNAE
                                                    </th>
                                                @endif
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{$indice->status}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; color:black">{{date('d-m-Y', strtotime($indice->data))}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; text-align:center; vertical-align:middle; color:black">{{$indice->aviso}}</th>
                                                <th class="subtituloBarraPrincipal"
                                                    style="font-size:15px; text-align:center; vertical-align:middle; color:black">{{$indice->aviso}}</th>
                                            </tr>
                                        @endif
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

    <!-- Modal de Aviso -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#2a9df4;">
                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" width="30px;" alt="Logo"
                         style=" margin-right:15px; margin-top:10px;"/><h5 class="modal-title" id="exampleModalLabel2"
                                                                           style="font-size:20px; margin-top:7px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">
                        Avisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formRequerimento" method="POST" action="{{ route('cadastrar.requerimento') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div id="avisoReq" class="col-12"
                                 style="font-family: 'Roboto', sans-serif; margin-bottom:10px;">Motivo da reprovação do
                                requerimento:
                            </div>
                            <div class="col-12"><textarea name="avisoRequerimentoRt" id="summary-ckeditor" cols="30"
                                                          rows="10" disabled></textarea></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal - Checklist completo-->
    <div class="modal fade" id="statusCompleto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:white;">
                    <img src="{{ asset('/imagens/logo_aprovado.png') }}" width="25px;" alt="Logo"
                         style=" margin-right:15px; margin-top:10px;"/><h5 class="modal-title" id="exampleModalLabel2"
                                                                           style="font-size:20px; margin-top:7px; color:#00b300; font-weight:bold; font-family: 'Roboto', sans-serif;">
                        Checklist Completa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="font-family: 'Roboto', sans-serif;"><label id="descricaoCNAERT"
                                                                                              style="font-weight:bold; font-family: 'Roboto', sans-serif;"></label>Os
                            documentos para o checklist deste cnae foram todos anexados.
                            Você pode gerar um arquivo da situação documental da empresa clicando <a
                                href="{{route('gerar.situacao.rt', ['areas' => $areas, 'empresa' => $empresas->id])}}"
                                style="weight:500px;">aqui</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="width:200px;">Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal - Checklist pendente-->
    <div class="modal fade" id="statusPendente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#fce205;">
                    <img src="{{ asset('/imagens/logo_atencao4.png') }}" width="25px;" alt="Logo"
                         style=" margin-right:15px; margin-top:10px;"/><h5 class="modal-title" id="exampleModalLabel2"
                                                                           style="font-size:20px; margin-top:7px; color:black; font-weight:bold; font-family: 'Roboto', sans-serif;">
                        Checklist Pendente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="font-family: 'Roboto', sans-serif;"><label
                                id="descricaoCNAERTreprovado"
                                style="font-weight:bold; font-family: 'Roboto', sans-serif;"> </label>Ainda há
                            documentos que não foram anexados! Verifique clicando <a
                                href="{{route('rt.documentacao.empresa', ['empresa' => Crypt::encrypt($empresas->id)])}}"
                                style="weight:500px;">aqui</a>.
                            Você pode gerar um arquivo da situação documental da empresa clicando <a
                                href="{{route('gerar.situacao.rt', ['areas' => $areas, 'empresa' => $empresas->id])}}"
                                style="weight:500px;">aqui</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="width:200px;">Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal - cnae requerimento-->
    <div class="modal fade" id="requerimentoCnaeRequisicaoRTModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#3ea81f;">
                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" width="30px;" alt="Logo"
                         style=" margin-right:15px; margin-top:10px;"/><h5 class="modal-title" id="exampleModalLabel2"
                                                                           style="font-size:20px; margin-top:7px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">
                        Criar requerimento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formRequerimento" method="POST" action="{{ route('cadastrar.requerimento') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" style="font-family: 'Roboto', sans-serif;">Criar requerimento para o
                                CNAE <label id="criarRequerimentoCNAERT"
                                            style="font-weight:bold; font-family: 'Roboto', sans-serif;"> </label> ?
                            </div>

                            <div class="col-12" style="font-family: 'Roboto', sans-serif; margin-top:10px;">
                                <input type="hidden" name="resptecnico" value="{{$resptecnico}}">
                                <input type="hidden" name="empresa" value="{{$empresas->id}}">
                                <input type="hidden" name="cnae" id="idCnaeRequerimentoRT">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Tipo de requerimento</label>
                                    <select class="form-control" id="exampleFormControlSelect1" name="tipo">
                                        <optgroup label="Primeira Via">
                                            <option id="priLicenca" value="Primeira Licenca">Primeira Licença</option>
                                            <option id="renoLicenca" value="Renovacao">Renovação</option>
                                        </optgroup>
                                        <optgroup label="Segunda Via">
                                            <option id="priLicenca2via" value="Primeira Licenca Segunda Via">Primeira
                                                Licença
                                            </option>
                                            <option id="renoLicenca2via" value="Renovacao Segunda Via">Renovação
                                            </option>
                                        </optgroup>
                                        <optgroup label="Outros">
                                            <option id="dispensaCNAE" value="Dispensa CNAE">Dispensa CNAE
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal" style="width:200px;">Cancelar
                        </button>
                        <button type="submit" class="btn btn-success" style="width:200px;">Sim, criar requerimento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('summary-ckeditor');
    </script>
@endsection

<script type="text/javascript">
    window.statusCNAERequisicaoRT = function ($flag, $descricao, $aviso, $idCnae, $respTecnico, $empresa) {

        $.ajax({
            url: '{{ config('prefixo.PREFIXO') }}cnae/encontrar',
            type: "get",
            dataType: 'json',
            data: {
                'cnaeId': $idCnae,
                'respTecnico': $respTecnico,
                'empresa': $empresa,
            },
            success: function (response) {
                if (response.tipo == "Primeira Licenca") {
                    console.log("Primeira Licenca");
                    if (response.valor == "pendente") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else if (response.valor == "aprovado") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", false);
                        $("option[value='Renovacao']").prop("disabled", false);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else {
                        $("option[value='Primeira Licenca']").prop("disabled", false);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", false);
                    }
                } else if (response.tipo == "Renovacao") {
                    console.log("Renovacao");
                    if (response.valor == "pendente") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else if (response.valor == "aprovado") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", false);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", false);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", false);
                        $("option[value='Renovacao']").prop("disabled", false);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    }
                } else if (response.tipo == "Primeira Licenca Segunda Via" && response.valor == "pendente") {
                    $("option[value='Primeira Licenca']").prop("disabled", true);
                    $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                    $("option[value='Renovacao']").prop("disabled", true);
                    $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                    $("option[value='Dispensa CNAE']").prop("disabled", true);
                } else if (response.tipo == "Renovacao Segunda Via" && response.valor == "pendente") {
                    $("option[value='Primeira Licenca']").prop("disabled", true);
                    $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                    $("option[value='Renovacao']").prop("disabled", true);
                    $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                    $("option[value='Dispensa CNAE']").prop("disabled", true);
                } else if (response.tipo == "Dispensa CNAE") {
                    if (response.valor == "pendente") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else if (response.valor == "aprovado") {
                        $("option[value='Primeira Licenca']").prop("disabled", true);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", true);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", true);
                    } else {
                        $("option[value='Primeira Licenca']").prop("disabled", false);
                        $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                        $("option[value='Renovacao']").prop("disabled", false);
                        $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                        $("option[value='Dispensa CNAE']").prop("disabled", false);
                    }
                } else if (response.tipo == "nenhum") {
                    console.log("Astrolábio");
                    $("option[value='Primeira Licenca']").prop("disabled", false);
                    $("option[value='Primeira Licenca Segunda Via']").prop("disabled", true);
                    $("option[value='Renovacao']").prop("disabled", true);
                    $("option[value='Renovacao Segunda Via']").prop("disabled", true);
                    $("option[value='Dispensa CNAE']").prop("disabled", false);
                } else {
                    $("option[value='Primeira Licenca']").prop("disabled", true);
                    $("option[value='Primeira Licenca Segunda Via']").prop("disabled", false);
                    $("option[value='Renovacao']").prop("disabled", true);
                    $("option[value='Renovacao Segunda Via']").prop("disabled", false);
                    $("option[value='Dispensa CNAE']").prop("disabled", true);
                }
            }
        });

        if ($flag == "reprovado") {

            document.getElementById('descricaoCNAERTreprovado').innerHTML = $descricao;
            document.getElementById('avisoCNAERTreprovado').innerHTML = $aviso;

        } else if ($flag == "aprovado") {

            document.getElementById('descricaoCNAERT').innerHTML = $descricao;

        } else if ($flag == "criarRequisicao") {

            document.getElementById('criarRequerimentoCNAERT').innerHTML = $descricao;
            document.getElementById('idCnaeRequerimentoRT').value = $idCnae;
        }
    }

</script>
