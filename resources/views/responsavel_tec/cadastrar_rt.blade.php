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
                    <div class="tituloBarraPrincipal">Finalizar Cadastro</div>
                </div>
            </div>
        </div>
    </div>

    <form id="teste" method="POST" action="{{ route('completar.cadastro.rt.salvar') }}">
        @csrf
        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
                <div class="container" style="margin-top:1rem;">

                    @if ($message = Session::get('error'))
                            <div class="alert alert-warning alert-block fade show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{$message}}</strong>
                            </div>
                    @endif
                    @if ($message = Session::get('success'))
                            <div class="alert alert-warning alert-block fade show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{$message}}</strong>
                            </div>
                    @endif

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">DADOS DO RESPONSÁVEL TÉCNICO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="container">
                            <div class="form-row">
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputEmail4">Nome:<span style="color:red">*</span></label>
                                    <input type="text" class="styleInputCadastro" name="nome" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">CPF:<span style="color:red">*</span></label>
                                    <input type="text" class="styleInputCadastro" name="cpf" id="cpf" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Telefone:<span style="color:red">*</span></label>
                                    <input type="text" class="styleInputCadastro" name="telefone" placeholder=""
                                           onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Conselho:<span style="color:red">*</span></label>
                                    <input type="text" maxlength="5" class="styleInputCadastro" name="conselho" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Número do Conselho/Registro:<span style="color:red">*</span></label>
                                    <input type="text" maxlength="6" class="styleInputCadastro" name="num_conselho" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Formação:<span style="color:red">*</span></label>
                                    <input type="text" class="styleInputCadastro" name="formacao" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Especialização:</label>
                                    <input type="text" class="styleInputCadastro" name="especializacao" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">ALTERAR SENHA<span style="color:red">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4" style="padding-right:15px;">
                            <input type="password" class="styleInputCadastro" name="senha" id="senha" placeholder="" required>
                        </div>
                    </div>
                    <hr size = 7>
                            <div style="margin-bottom:1rem;">
                                    <div class="d-flex">
                                        <div class="mr-auto p-2">
                                            <label style="font-weight:bold; color:red; font-family:Arial, Helvetica, sans-serif"><span style="font-size:20px">*</span> campos obrigatórios</label>
                                        </div>
                                    <div class="p-2">
                                            <input type="hidden" name="user" value="{{Auth::user()->id}}">
                                        <button type="submit" class="btn btn-success" style="width:340px;">Cadastrar</button>
                                    </div>
                                </div>
                            </div>
                </div>
        </div>
        <div class="container" style="margin-top:1rem;margin-left:10px;">
        </div>
    </form>
</div>

<script type="text/javascript">
    function mask(o, f) {
        setTimeout(function() {
            var v = mphone(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, "");
        r = r.replace(/^0/, "");
        if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1)$2-$3");
        } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1)$2-$3");
        } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1)$2");
        } else {
            r = r.replace(/^(\d*)/, "($1");
        }
        return r;
    }
</script>

<script type="text/javascript">

    window.onload= function() {

        $('#cpf').blur(function(){
            console.log("FARL!");
            // O CPF ou CNPJ
            var cpf_cnpj = $(this).val();

            // Testa a validação e formata se estiver OK
            if ( formata_cpf_cnpj( cpf_cnpj ) ) {
                $(this).val( formata_cpf_cnpj( cpf_cnpj ) );
            } else {
                alert('CPF ou CNPJ inválido!');
            }
        });
    };

</script>
@endsection
