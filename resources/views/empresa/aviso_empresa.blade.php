@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 5rem;">
        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <img src="{{ asset('/imagens/logo_parabens.png') }}" alt="Logo" style="width:100%; margin-top:10px; margin-bottom:10px;"/>
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label style="font-size:35px;margin-top:10px;font-weight:bold;color:#6c63ff; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">PARABÉNS!</label>
                            </div>
                            <div class="form col-md-12" style="margin-top:10px;">
                                <label style="font-weight:normal;font-size:22px; font-family: 'Roboto', sans-serif; color:#3f3d56; line-height:30px;">A solicitação de cadastro de usuário e empresa foi realizada com sucesso!</label>
                            </div>
                            <div class="form col-md-12" style="margin-top:10px;">
                                <label style="font-size:16px;font-family: 'Quicksand', sans-serif;">Seus dados encontram-se em avaliação, espere a sua aprovação para que possa ter acesso a outras funcionalidades do sistema. </label>
                            </div>
                            <div class="form col-md-12" style="margin-top:40px;">
                                <a class="btn btn-success botao-form" style="weight:500px; color:white;">Clique aqui</a>
                                <label style="margin-left:10px; font-family: 'Roboto', sans-serif;"> para voltar à página inicial</label>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
