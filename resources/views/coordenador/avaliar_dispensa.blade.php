@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="barraMenu">
            <div class="d-flex justify-content-center">
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
                        <div class="tituloBarraPrincipal">Documentação</div>
                        <div>
                            <div
                                style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">
                                Início > Documentação > {{$empresa->nome}}</div>
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


        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div>
                            <label style="color:black; font-size:35px;  margin-bottom:-10px; font-weight:400; font-family: 'Libre Baskerville', serif;;
                                ;">{{$empresa->nome}}</label>
                        </div>
                        <div>
                            <div style="font-size:13px;margin-top:2px; margin-bottom:-10px;color:gray;">Início >
                                Estabelecimentos > {{$empresa->nome}} > Documentos
                            </div>
                        </div>
                        <hr size=7 style="margin-bottom:-2px;">
                    </div>

                    <div class="form-group col-md-12">
                        @if($errors->any())
                            <div class="alert alert-warning alert-block fade show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{$errors->first()}}</strong>
                            </div>
                        @endif
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
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label
                                    style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">DISPENSA</label>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="styleTituloDoInputCadastro" for="dispensa">Motivo de Solicitação
                                            da
                                            Dispensa:</label><br>
                                        <textarea name="dispensa" id="dispensa" cols="50" rows="6"
                                                  readonly>{{$dispensa->dispensa}}</textarea>
                                    </div>
                                    <div class="form-group col-md-4 align-content-center">
                                        <label class="styleTituloDoInputCadastro" for="cnpj">CNPJ:</label><br>
                                        <a class="btn btn-success" href="{{route('dispensa.download', $dispensa->id)}}"
                                           style="color: white;"><i class="fa fa-download"> Download</i></a>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="margin-top:2rem; margin-bottom:1rem">
                                <div class="col-auto mr-auto"></div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger" style="margin-right:5px;"
                                            data-toggle="modal" data-target="#exampleModal1">Reprovar Dispensa
                                    </button>
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#exampleModal2">Aprovar Dispensa
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal - reprovar cadastro-->
                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel1" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:red;">
                                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" alt="Logo"
                                         style=" margin-right:15px;"/><h5 class="modal-title" id="exampleModalLabel"
                                                                          style="font-size:20px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">
                                        Reprovar Dispensa</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('julgar.requerimento') }}">
                                    @csrf

                                    <input type="hidden" name="empresa" value="{{$empresa->id}}">
                                    <input type="hidden" name="decisao" value="false">
                                    <input type="hidden" name="requerimento" value="{{$requerimento}}">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12" style="font-family: 'Roboto', sans-serif;">Tem certeza
                                                de que deseja reprovar a dispensa de CNAE do estabelecimento <label
                                                    id="nomeDoEstabelecimento"
                                                    style="font-weight:bold; font-family: 'Roboto', sans-serif;">{{$empresa->nome}}</label>?
                                            </div>
                                            <div class="col-12" style="font-family: 'Roboto', sans-serif;">
                                                <label for="exampleFormControlTextarea1">Informe o motivo da reprovação
                                                    da dispensa: <span style="color:red">*</span></label>
                                                <textarea class="form-control" id="summary-ckeditor" rows="3"
                                                          name="avisos"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <div>
                                            <label
                                                style="font-weight:bold; color:red; font-family:Arial, Helvetica, sans-serif"><span
                                                    style="font-size:20px">*</span> Campo obrigatório</label>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                                                    style="width:100px; margin-right:15px;">Não
                                            </button>
                                            <button type="submit" class="btn btn-success botao-form">Sim, reprovar
                                                dispensa
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal - aprovar cadastro-->
                    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel2" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#3ea81f;">
                                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" alt="Logo"
                                         style=" margin-right:15px;"/><h5 class="modal-title" id="exampleModalLabel2"
                                                                          style="font-size:20px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">
                                        Aprovar Requerimento</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12" style="font-family: 'Roboto', sans-serif;">Tem certeza de
                                            que deseja aprovar a dispensa de CNAE do estabelecimento <label
                                                id="nomeDoEstabelecimento"
                                                style="font-weight:bold; font-family: 'Roboto', sans-serif;">{{$empresa->nome}}</label>?
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                                            style="width:100px;">Não
                                    </button>
                                    <form method="POST" action="{{ route('julgar.requerimento') }}">
                                        @csrf

                                        <input type="hidden" name="empresa" value="{{$empresa->id}}">
                                        <input type="hidden" name="decisao" value="true">
                                        <input type="hidden" name="requerimento" value="{{$requerimento}}">

                                        <div class="col-md-12" style="padding-right:0">
                                            <button type="submit" class="btn btn-success botao-form" style="width:100%">
                                                Sim, aprovar dispensa
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            {{-- <hr size = 7 style="margin-bottom:-15px;"> --}}
            <div class="row" style="margin-top:2rem; margin-bottom:1rem">
                <div class="col-auto mr-auto"></div>
                <div class="col-auto">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('summary-ckeditor');
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection


