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
                    <div class="tituloBarraPrincipal">Cadastrar um novo estabelecimento</div>
                    <div>
                        <div style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">Início > Estabelecimentos > Cadastrar estabelecimento</div>
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
    <form id="teste" method="POST" action="{{ route('adicionar.empresa') }}">
        @csrf
        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">DADOS DO ESTABELECIMENTO</label>
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
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4" style="padding-right:15px;">
                                <label class="styleTituloDoInputCadastro" for="inputEmail4">Nome/Razão Social:<span style="color:red">*</span></label>
                                <input class="styleInputCadastro" type="text" class="form-control" name="nome" placeholder="" required>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:15px;">
                                <label class="styleTituloDoInputCadastro"  for="inputPassword4">CNPJ/CPF:<span style="color:red">*</span></label>
                                <input class="styleInputCadastro" type="text" class="form-control" name="cnpjcpf" id="cnpjcpf" placeholder="" required>
                            </div>

                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">TIPO:<span style="color:red">*</span></label>
                                    {{-- <input type="text" class="form-control"  name="tipo" placeholder="" required> --}}
                                    <select class="form-control" name="tipo" required>
                                        <option value="" disable="" selected="" hidden="">-- Selecionar o Tipo --</option>
                                        <option value="Sociedade Empresária Limitada (LTDA)">Sociedade Empresária Limitada (LTDA)</option>
                                        <option value="Empresa Individual de Responsabilidade Limitada (EIRELI)">Empresa Individual de Responsabilidade Limitada (EIRELI)</option>
                                        <option value="Empresa Individual">Empresa Individual</option>
                                        <option value="Microempreendedor Individual (MEI)">Microempreendedor Individual (MEI)</option>
                                        <option value="Sociedade Simples(SS)">Sociedade Simples(SS)</option>
                                        <option value="Sociedade Anônima(SA)">Sociedade Anônima(SA)</option>
                                    </select>
                                </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4" style="padding-right:15px;">
                                <label class="styleTituloDoInputCadastro" for="nome">Nome de Fantasia:</label>
                                <input class="styleInputCadastro" type="text" id="nome_fantasia" name="nome_fantasia" placeholder="">
                            </div>
                            <div class="form-group col-md-4" style="padding-right:15px;">
                                <label class="styleTituloDoInputCadastro" for="inputEmail4">E-mail do estabelecimento:</label>
                                <input class="styleInputCadastro" type="email" class="form-control" name="emailEmpresa" placeholder="">
                            </div>
                            <div class="form-group col-md-4" style="padding-right:15px;">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="styleTituloDoInputCadastro" for="inputEmail4">Telefone 1:<span style="color:red">*</span></label>
                                        <input class="styleInputCadastro" type="text" class="form-control" name="telefone1" id="inputEmail4" placeholder="" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="styleTituloDoInputCadastro" for="inputPassword4">Telefone 2:</label>
                                        <input class="styleInputCadastro" type="text" class="form-control" name="telefone2" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <div class="form-group col-md-12" >
                                            <label class="styleTituloDoInputCadastro" for="exampleFormControlSelect1">Áreas</label>
                                            <select required class="form-control" id="idSelecionarArea" onChange="selecionarArea(this)">
                                                <option value="">-- Selecionar a Área --</option>
                                                @foreach ($areas as $item)
                                                    <option value={{$item->id}}>{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="btn-group col-md-12 styleTituloDoInputCadastro">
                                                <div class="col-md-6" style="margin-left:-15px;margin-right:30px;margin-bottom:10px;">CNAE</div>
                                                <div class="col-md-12 input-group input-group-sm mb-2">
                                                    {{-- <input type="text" class="form-control" placeholder="Nome ou código do CNAE"> --}}
                                                </div>

                                            </div>
                                            <div class="form-row col-md-12">
                                                <div style="width:100%; height:250px; display: inline-block; border: 1.5px solid #f2f2f2; border-radius: 2px; overflow:auto;">
                                                    <table cellspacing="0" cellpadding="1"width="100%" >
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="styleTituloDoInputCadastro" for="exampleFormControlSelect1">CNAE selecionado</label>
                                    <div class="form-group col-md-12 areaMeusCnaes" id="adicionar">

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label style="font-size:19px;margin-bottom:-5px; font-family: 'Roboto', sans-serif;">ENDEREÇO DO ESTABELECIMENTO</label>
                                </div>
                                <div class="form-group col-md-4" style="padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">CEP:<span style="color:red">*</span></label>
                                    <input class="styleInputCadastro" value="{{old('cep')}}" onblur="pesquisacep(this.value);" id="cep" type="text"  name="cep" required autocomplete="cep" placeholder="" size="10" maxlength="9">
                                </div>
                                <div class="form-group col-md-4" style="margin-top:-5px; padding-right:15px">
                                    <label class="styleTituloDoInputCadastro" for="inputEmail4">UF:<span style="color:red">*</span></label>
                                    <input readonly type="text" class="form-control" name="uf" placeholder="" id="uf" value="{{ old('uf') }}">
                                </div>
                                <div class="form-group col-md-4" style="margin-top:-5px; padding-right:15px;">
                                    <label class="styleTituloDoInputCadastro" for="inputEmail4">Cidade:<span style="color:red">*</span></label>
                                    <input readonly id="cidade" type="text" class="form-control" name="cidade" placeholder="" required value="{{ old('cidade') }}">
                                </div>
                            </div>
                            <div class="form-row" style="padding-bottom:1.5rem;">
                                <div class="form-group col-md-4">
                                    <label class="styleTituloDoInputCadastro" for="inputEmail4">Bairro:<span style="color:red">*</span></label>
                                    <input value="{{old('bairro')}}" id="bairro" type="text" class="styleInputCadastro" name="bairro" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="styleTituloDoInputCadastro" for="inputEmail4">Rua:<span style="color:red">*</span></label>
                                    <input value="{{old('rua')}}" id="rua" type="text" class="styleInputCadastro" name="rua" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Número:<span style="color:red">*</span></label>
                                    <input type="text" class="styleInputCadastro" name="numero" placeholder="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="styleTituloDoInputCadastro" for="inputPassword4">Complemento:</label>
                                    <input type="text" class="styleInputCadastro" name="complemento" placeholder="">
                                </div>
                            </div>

                            <hr size = 7>
                            <div style="margin-bottom:1rem;">
                                    <div class="d-flex">
                                        <div class="mr-auto p-2">
                                            <label style="font-weight:bold; color:red; font-family:Arial, Helvetica, sans-serif"><span style="font-size:20px">*</span> campos obrigatórios</label>
                                        </div>
                                    <div class="p-2">
                                        <button type="submit" class="btn btn-success" style="width:340px;">Cadastrar</button>
                                    </div>
                                </div>
                            </div>
                        <form>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    window.selecionarArea = function(){
    //area
    var historySelectList = $('select#idSelecionarArea');
    var $id_area = $('option:selected', historySelectList).val();
    $.ajax({
        url:'{{ config('prefixo.PREFIXO') }}estabelecimento/lista/cnae',
        type:"get",
        dataType:'json',
        data: {"id_area": $id_area},
        success: function(response){
            $('tbody').html(response.table_data);
            // document.getElementById('idArea');
        }
    });
    }

    window.onload= function() {

        $('#cnpjcpf').blur(function(){

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


