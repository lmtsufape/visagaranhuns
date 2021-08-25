@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="form-row">
        <div class="col-md-12" style="margin-left:10px; font-family:'Roboto'; font-size:18px; margin-bottom:5px;">Principais Serviços</div>
        <div class="form-group col-md-4">
            <a href="{{ route('home.cadastrar')}}" style="text-decoration:none;cursor:pointer;color:black;">
                <div class="cardAreaGrande" style="padding:1rem; width:100%; height:100%; background-color:#12B100">
                <div class="form-row">
                    <div class="col-12" style="height:80px;  text-align:right;">
                            <img src="{{ asset('/imagens/logo_predio2.png') }}" alt="Logo" style="width:40px;"/>
                        </div>
                    <div class="col-12" style="color:white;font-family:'Noto Sans SC'; font-weight:400; font-size:16px">Cadastre sua empresa</div>
                </div>
                </div>
            </a>
        </div>
        <div class="form-group col-md-4">
            <a href="{{ route('pagina.denuncia')}}" style="text-decoration:none;cursor:pointer;color:black;">
                <div class="cardAreaGrande" style="padding:1rem; width:100%; height:100%; background-color:#d88366">
                <div class="form-row">
                    <div class="col-12" style="height:80px;  text-align:right;">
                            <img src="{{ asset('/imagens/megafone.png') }}" alt="Logo" style="width:40px;"/>
                        </div>
                    <div class="col-12" style="color:white;font-family:'Noto Sans SC'; font-weight:400; font-size:16px">Denúncia</div>
                </div>
                </div>
            </a>
        </div>
        @foreach ($servicos as $item)
            <div class="form-group col-md-4">
                <a href="{{ route('home.informacao',["value"=>Crypt::encrypt($item->id)]) }}" style="text-decoration:none;">
                    <div class="cardAreaGrande" style="padding:1rem; width:100%; height:100%;background-color:{{$item->cor}}">
                        <div class="form-row">
                            <div class="col-12" style="height:80px;  text-align:right;">
                                <img src="{{ $item->icone }}" alt="Logo" style="width:40px;"/>
                            </div>
                            <div class="col-12 limiteDeTextoCard" style="color:white;font-family:'Noto Sans SC'; font-weight:400; font-size:15px;">{{$item->titulo}}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        @if(count($servicos)>=4)
            <div class="form-group col-md-4">
                <a href="{{ route('home.outras.informacoes') }}" style="text-decoration:none;">
                    <div class="cardAreaGrande" style="padding:1rem; width:100%; height:100%;background-color:#ae71cc">
                        <div class="form-row">
                            <div class="col-12" style="height:80px;  text-align:right;">
                                    <img src="{{ asset('/imagens/logo_mais.png') }}" alt="Logo" style="width:40px;"/>
                                </div>
                            <div class="col-12" style="color:white;font-family:'Noto Sans SC'; font-weight:400; font-size:15px">Outros Serviços</div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-md-12" style="margin-left:10px;margin-top:10px; font-family:'Roboto'; font-size:18px; margin-bottom:5px;">Endereço e Contatos</div>

        <div class="form-group col-md-4">
            <div  style="padding:1rem; width:350px; height:300px;">
                <div class="form-row">
                    <div id="img1" style="display:block">
                        <a href="https://www.google.com/maps/place/SECRETARIA+DE+SA%C3%9ADE+DE+GARANHUNS+-+PE/@-8.8841637,-36.487425,15z/data=!4m2!3m1!1s0x0:0x4d75d799b5e64a5d?sa=X&ved=2ahUKEwjptPP9vt_rAhXWCrkGHQCECj0Q_BIwCnoECBYQBg">
                            <img class="styleMapa" src="{{ asset('/imagens/mapa_ssg.png') }}" alt="Logo" style="width:100%; height:100%;"/>
                        </a>
                    </div>
                    <div id="img2" style="display:none">
                        <a href="">
                            <img  class="styleMapa" src="{{ asset('/imagens/mapa_sms.png') }}" alt="Logo" style="width:100%; height:100%;"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-8">
            <div class="" style="padding:1rem; width:100%; height:100%;">
                <div class="form-row">
                    <div class="cardMapa">
                        <div class="d-flex">
                            <div class="mr-auto p-2">
                                <div class="btn-group">
                                    <div style="margin-top:2.4px;margin-left:10px;font-size:15px; font-family:'Roboto'; font-weight:bold; color:#707070">Secretaria de Saúde de Garanhuns - PE</div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div id="btnMostrar1" style="margin-right:10px; cursor:pointer;" onclick="mostrarContato('mostrar1','texto1','img1')"><span id="texto1">Fechar</span></div>
                            </div>
                        </div>
                        <div id="mostrar1" style="display:block;">
                            <div class="container" style="margin-left:3px; font-family:arial;">R. Amauri de Medeiros, 215-387 - Heliópolis, Garanhuns - PE, 55295-430</div>
                            <div class="container" style="margin-left:3px; font-family:arial; color:red">Segunda a Sexta - 08:00-14:00</div>
                            <div class="container" style="margin-left:3px; margin-bottom:10px; font-family:arial;"></div>
                        </div>
                    </div>

                    <div class="cardMapa">
                        <div class="d-flex">
                            <div class="mr-auto p-2">
                                <div class="btn-group">
                                    <div style="margin-top:2.4px;margin-left:10px;font-size:15px; font-family:'Roboto'; font-weight:bold; color:#707070">Secretaria Municipal de Garanhuns - PE</div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div style="margin-right:10px; cursor:pointer;" onclick="mostrarContato('mostrar2','texto2','img2')"><span id="texto2">Mostrar</span></div>
                            </div>
                        </div>
                        <div id="mostrar2" style="display:none;">
                            <div class="container" style="margin-left:3px; font-family:arial;">R. Joaquim Távora - Heliópolis, Garanhuns - PE, 55295-410</div>
                            <div class="container" style="margin-left:3px; font-family:arial; color:red">Segunda a Sexta - 08:00-14:00</div>
                            <div class="container" style="margin-left:3px; margin-bottom:10px; font-family:arial;">(87) 3762-7071</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
