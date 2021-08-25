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
                    <div class="tituloBarraPrincipal">Avaliar estabelecimentos</div>
                    <div>
                        <div style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">Início > Requerimento > Avaliar > {{$empresa->nome}}</div>
                    </div>
                </div>
            </div>
            <div class="p-2" style="width:50px">
                {{-- <div class="dropdown" style="margin-top:10px">
                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ações
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item btn btn-primary" data-toggle="modal" data-target="#exampleModal">Convidar inspetores</a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
        <div class="container" style="margin-top:1rem;">
            <div class="form-row">
                <div class="form-group col-md-12" >
                    <div>
                        <label style="color:black; font-size:35px;  margin-bottom:-10px; font-weight:400; font-family: 'Libre Baskerville', serif;;
                        ;">{{$empresa->nome}}</label>
                    </div>
                    <hr size = 7 style="margin-bottom:-2px;">
                </div>

                <div class="form-group col-md-7">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">INFORMAÇÕES DO ESTABELECIMENTO</label>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Nome: </label>
                            <span style="color:#707070">{{$empresa->nome}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Nome de Fantasia: </label>
                            <span style="color:#707070">{{$empresa->nome_fantasia}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">CNPJ: </label>
                            <span style="color:#707070">{{$empresa->cnpjcpf}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Tipo: </label>
                            <span style="color:#707070">{{$empresa->tipo}}</span>
                        </div>

                        <div class="form-group col-md-12">
                            <label style="font-size:19px;margin-top:10px;margin-bottom:-5px; font-family: 'Roboto', sans-serif;">ENDEREÇO</label>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="margin-bottom:-15px; font-weight:normal;font-family: 'Roboto', sans-serif;">Rua: </label>
                            <span style="margin:0px;color:#707070">{{$endereco->rua}},</span>
                            <span style="margin:0px;color:#707070"> nº{{$endereco->numero}},</span>
                            <span style="margin:0px;color:#707070"> {{$endereco->bairro}},</span>
                            <span style="margin:0px;color:#707070"> {{$endereco->cidade}}/{{$endereco->uf}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:1px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">CEP: </label>
                            <span style="color:#707070">{{$endereco->cep}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Complemento: </label>
                            <span style="color:#707070">{{$endereco->complemento}}</span>
                        </div>


                        <div class="form-group col-md-12">
                            <label style="font-size:19px;margin-top:10px;margin-bottom:-5px; font-family: 'Roboto', sans-serif;">CONTATO</label>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">E-mail: </label>
                            <span style="color:#707070">{{$empresa->email}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Telefone 1: </label>
                            <span style="color:#707070">{{$telefone->telefone1}}</span>
                        </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Telefone 2: </label>
                            <span style="color:#707070">{{$telefone->telefone2}}</span>
                        </div>



                    </div>
                </div>
                <div class="form col-md-5">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                                <label style="font-size:19px;margin-top:10px;margin-bottom:-5px; font-family: 'Roboto', sans-serif;">INFORMAÇÕES DO GERENTE</label>
                            </div>
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Nome: </label>
                            <span style="color:#707070">{{$empresa->user->name}}</span>
                        </div>
                        {{-- <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">CPF: </label>
                            <span style="color:#707070">000.000.000-00</span>
                        </div> --}}
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">E-mail: </label>
                            <span style="color:#707070">{{$empresa->user->email}}</span>
                        </div>
                        {{-- <div class="form col-md-12" style="margin-top:-10px;">
                            <label style="font-weight:normal;font-family: 'Roboto', sans-serif;">Telefone: </label>
                            <span style="color:#707070">(00) 0000-0000</span>
                        </div> --}}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label style="font-size:19px;margin-top:0px;margin-bottom:-5px; font-family: 'Roboto', sans-serif;">CNAE</label>
                </div>
                @foreach($cnae as $item)
                        <div class="form col-md-12" style="margin-top:-10px;">
                            <img src="{{ asset('/imagens/logo_ponto.png') }}" alt="Logo" style="margin-top:-5px; margin-right:5px;"/>
                            <label style="  ">{{$item->cnae->codigo}} </label> |
                            <span style="color:#707070">{{$item->cnae->descricao}}</span>
                        </div>
                @endforeach
            </div>
            <hr size = 7 style="margin-bottom:-15px;">
            <div class="row" style="margin-top:2rem; margin-bottom:1rem">
                <div class="col-auto mr-auto"></div>
                <div class="col-auto">
                        <button type="button" class="btn btn-danger" style="margin-right:5px;" data-toggle="modal" data-target="#exampleModal1">Reprovar cadastro</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal2">Aprovar cadastro</button>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal - reprovar cadastro-->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:red;">
                    <img src="{{ asset('/imagens/logo_atencao3.png') }}" alt="Logo" style=" margin-right:15px;"/><h5 class="modal-title" id="exampleModalLabel" style="font-size:20px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">Reprovar cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="font-family: 'Roboto', sans-serif;">Tem certeza de que deseja reprovar o cadastro do estabelecimento <label id="nomeDoEstabelecimento" style="font-weight:bold; font-family: 'Roboto', sans-serif;">{{$empresa->nome}}</label>?</div>
                    {{-- <div class="col-12" style="font-family: 'Roboto', sans-serif; margin-top:10px;"><img src="{{ asset('/imagens/logo_bloqueado.png') }}" alt="Logo" style="width:15px; margin-right:5px;"/> Essa ação não poderá ser desfeita</div> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"style="width:100px;">Não</button>
                <form method="POST" action="{{ route('julgar.cadastro', ['empresa_id' => $empresa->id, 'user_id' => $user->id, 'decisao' => 'false']) }}">
                    @csrf
                    <div class="col-md-12" style="padding-left:0">
                        <button type="submit" class="btn btn-success botao-form" style="width:100%">
                                Sim, reprovar cadastro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal - aprovar cadastro-->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#3ea81f;">
                        <img src="{{ asset('/imagens/logo_atencao3.png') }}" alt="Logo" style=" margin-right:15px;"/><h5 class="modal-title" id="exampleModalLabel2" style="font-size:20px; color:white; font-weight:bold; font-family: 'Roboto', sans-serif;">Aprovar cadastro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="font-family: 'Roboto', sans-serif;">Tem certeza de que deseja aprovar o cadastro do estabelecimento <label id="nomeDoEstabelecimento" style="font-weight:bold; font-family: 'Roboto', sans-serif;">{{$empresa->nome}}</label>?</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"style="width:100px;">Não</button>
                    <form method="POST" action="{{ route('julgar.cadastro', ['empresa_id' => $empresa->id, 'user_id' => $user->id, 'decisao' => 'true']) }}">
                        @csrf
                        <div class="col-md-12" style="padding-right:0">
                            <button type="submit" class="btn btn-success botao-form" style="width:100%">
                                Sim, aprovar cadastro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


