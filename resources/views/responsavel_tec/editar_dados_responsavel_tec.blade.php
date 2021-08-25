@extends('layouts.app')

@section('content')
<div class="container">
    <div class="barraMenu">
        <div class="d-flex justify-content-center">
            <div class="mr-auto p-2 styleBarraPrincipalMOBILE">
                <a href="javascript: history.go(-1)" style="text-decoration:none;cursor:pointer;color:black;">
                    <div class="btn-group">
                        <div style="margin-top:1px;margin-left:5px;"><img src="{{ asset('/imagens/logo_voltar.png') }}" alt="Logo" style="width:13px;"/></div>
                        <div style="margin-top:2.4px;margin-left:10px;font-size:15px;">Voltar</div>
                    </div>
                </a>
            </div>
            <div class="mr-auto p-2 styleBarraPrincipalPC">
                <div class="form-group">
                    <div class="tituloBarraPrincipal">Editar meus dados</div>
                    <div>
                        <div style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">Início > Editar meus dados </div>
                    </div>
                </div>
            </div>
            <div class="p-2">
                {{-- <div class="dropdown" style="width:50px"> --}}
                    {{-- <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ações
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item btn btn-primary" data-toggle="modal" data-target="#exampleModal">Convidar agente</a>
                    </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>

    <div class="container">
            <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
                <div class="container" style="margin-top:1rem;">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success alert-block fade show">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{$message}}</strong>
                                        </div>
                                    @elseif ($message = Session::get('error'))
                                        <div class="alert alert-warning alert-block fade show">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{$message}}</strong>
                                        </div>
                                    @endif
                                    <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">MEUS DADOS</label>
                                </div>
                            </div>
                            <form id="teste" method="POST" action="{{ route('update.rt') }}">
                                @csrf
                                <input type="hidden" name="respTecnico" value="{{$respTecnico->id}}">
                                <div class="form-row">
                                    <div class="form-group col-md-4"  style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="nome">Nome Completo<span style="color:red">*</span></label>
                                        <input type="text" id="nome" class="styleInputCadastro" name="nome" value="{{$user->name}}" required>
                                    </div>
                                    <div class="form-group col-md-4"  style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="cpf">CPF:<span style="color:red">*</span></label>
                                        <input type="text" id="cpf" class="styleInputCadastro" name="cpf" value="{{$respTecnico->cpf}}" required maxlength="11">
                                    </div>
                                    <div class="form-group col-md-4"  style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="inputTelefone1">Telefone:<span style="color:red">*</span></label>
                                        <input type="text" class="styleInputCadastro" name="telefone" id="inputTelefone1" value="{{$respTecnico->telefone}}" required maxlength="11">
                                    </div>
                                    <div class="form-group col-md-4" style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="inputPassword4">Conselho:<span style="color:red">*</span></label>
                                        <input type="text" maxlength="5" class="styleInputCadastro" name="conselho" placeholder="" value="{{$respTecnico->conselho}}" required>
                                    </div>
                                    <div class="form-group col-md-4" style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="inputPassword4">Número do Conselho/Registro:<span style="color:red">*</span></label>
                                        <input type="text" maxlength="6" class="styleInputCadastro" name="num_conselho" placeholder="" value="{{$respTecnico->num_conselho}}" required>
                                    </div>
                                    <div class="form-group col-md-4"  style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="formacao">Formação:<span style="color:red">*</span></label>
                                        <input type="text" id="formacao" class="styleInputCadastro" name="formacao" value="{{$respTecnico->formacao}}" required>
                                    </div>
                                    <div class="form-group col-md-4"  style="padding-right:15px;">
                                        <label class="styleTituloDoInputCadastro" for="especializacao">Especialização:<span style="color:red">*</span></label>
                                        <input type="text" id="especializacao" class="styleInputCadastro" name="especializacao" value="{{$respTecnico->especializacao}}" required>
                                    </div>
                                </div>
                            <hr size = 7>
                            <div style="margin-bottom:0.2rem;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label style="font-weight:bold; color:red; font-family:Arial, Helvetica, sans-serif"><span style="font-size:20px">*</span> campos obrigatórios</label>
                                        </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success" style="width:100%;">Atualizar</button>
                                    </div>
                                </div>
                            </div>
                            <form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>
@endsection


