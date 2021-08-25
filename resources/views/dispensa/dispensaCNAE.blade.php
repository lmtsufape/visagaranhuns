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
                                Início > Estabelecimento > {{$empresa->nome}} > Requerimentos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="teste" method="POST" action="{{ route('solicitar.dispensa.empresa') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="resptecnico" value="{{$resptecnico}}">
            <input type="hidden" name="empresa" value="{{$empresa->id}}">
            <input type="hidden" name="cnae" id="idCnaeRequerimentoRT" value="{{$cnae}}">
            <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
                <div class="container" style="margin-top:1rem;">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label
                                style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">DISPENSA
                                DE CNAE</label>
                            @if ($message = Session::get('error'))
                                <div class="alert alert-warning alert-block fade show">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{$message}}</strong>
                                </div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-warning alert-block fade show">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{$errors->first()}}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="styleTituloDoInputCadastro" for="dispensa">Motivo de Solicitação da
                                        Dispensa:<span style="color:red">*</span></label><br>
                                    <textarea name="dispensa" id="dispensa" cols="50" rows="6" required></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="styleTituloDoInputCadastro" for="cnpj">Envio do CNPJ:<span
                                            style="color:red">*</span></label><br>
                                    <input type="file" name="cnpj" required>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <button type="submit" class="btn btn-success" style="width:340px;">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
