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
                    <div class="tituloBarraPrincipal">Cadastrar Inspeções</div>
                    <div>
                        <div style="margin-left:10px; font-size:13px;margin-top:2px; margin-bottom:-15px;color:gray;">Início > Inspeções</div>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="dropdown" style="margin-top:10px; margin-right:-100px">
                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ações
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('historico.inspecoes') }}">Histórico</a>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div style="width:70px">
                </div>
            </div>
        </div>
    </div>
    @if ($message = Session::get('error'))
        <div class="alert alert-success alert-block fade show">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{$message}}</strong>
        </div>
    @endif

    <form id="teste" method="POST" action="{{ route('cadastrar.inspecao') }}">
        @csrf

        <div class="barraMenu" style="margin-top:2rem; margin-bottom:4rem;padding:15px;">
            <div class="container" style="margin-top:1rem;">
                <div class="form-row">

                    <div class="form-group col-md-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block fade show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{$message}}</strong>
                            </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label style="font-size:19px;margin-top:10px; margin-bottom:-5px; font-family: 'Roboto', sans-serif;">DADOS DA INSPEÇÃO</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <label class="styleTituloDoInputCadastro" for="inputPassword4">Inspetor:<span style="color:red">*</span></label>
                                <select class="form-control" name="inspetor" required>
                                    <option value="" data-default selected> -- Selecione -- </option>
                                    @foreach ($inspetores as $item)
                                        <option value="{{$item->id}}">{{$item->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <label class="styleTituloDoInputCadastro" for="inputPassword4">Agente 1:<span style="color:red">*</span></label>
                                <input name="agenteRequired[]" type="hidden" id="agente1">
                                <select class="form-control agentes" id="agente1" onchange="retirarAgente(this, 1)" required>
                                    <option value="" data-default selected> -- Selecione -- </option>
                                    @foreach ($agentes as $item)
                                        <option id="y{{$item->id}}" value="{{$item->id}}">{{$item->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <label class="styleTituloDoInputCadastro" for="inputPassword4">Agente 2:<span style="color:red">*</span></label>
                                <input name="agenteRequired[]" type="hidden" id="agente2">
                                <select class="form-control agentes" id="agente2" onchange="retirarAgente(this, 2)" required>
                                    <option value="" data-default selected> -- Selecione -- </option>
                                    @foreach ($agentes as $item)
                                        <option id="y{{$item->id}}" value="{{$item->id}}">{{$item->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <div class="form-group">
                                    <label class="styleTituloDoInputCadastro">Data de Inspeção:*</label>
                                    <input class="form-control" type="date" name="data" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <label class="styleTituloDoInputCadastro" for="inputPassword4">Agente 3:</label>
                                <input name="agenteOpt[]" type="hidden" id="agente3">
                                <select class="form-control agentes" onchange="retirarAgente(this, 3)">
                                    <option value="" data-default selected> -- Selecione -- </option>
                                    @foreach ($agentes as $item)
                                        <option id="y{{$item->id}}" value="{{$item->id}}">{{$item->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4" style="padding-right:10px; margin-top:-7px;">
                                <label class="styleTituloDoInputCadastro" for="inputPassword4">Agente 4:</label>
                                <input name="agenteOpt[]" type="hidden" id="agente4">
                                <select class="form-control agentes" onchange="retirarAgente(this, 4)">
                                    <option value="" data-default selected> -- Selecione -- </option>
                                    @foreach ($agentes as $item)
                                        <option id="y{{$item->id}}" value="{{$item->id}}">{{$item->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form col-md-12">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-row">

                                    <div class="btn-group col-md-12">
                                        <div class="col-md-12 styleTituloDoInputCadastro" style="margin-left:-15px;margin-right:30px;margin-bottom:10px;">Requerimentos Aprovados ou Denúncias</div>
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
                                <label class="styleTituloDoInputCadastro" for="exampleFormControlSelect1">Requerimentos Selecionados</label>
                                <div class="form-group col-md-12 areaMeusCnaes" id="adicionar">

                                </div>
                            </div>
                        </div>

                        <div style="padding-top:1rem;padding-bottom:1.5rem;">
                                {{-- <hr size = 7 style="padding-top:1rem;"> --}}
                                <div class="d-flex">
                                    <div class="mr-auto p-2">
                                        <label style="font-weight:bold; color:red; font-family:Arial, Helvetica, sans-serif"><span style="font-size:20px">*</span> campos obrigatórios</label>
                                    </div>
                                <div class="p-2">
                                    <button type="submit" class="btn btn-success" style="width:200px;">Cadastrar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>


                </div>

            </div>
        </div>

    </form>

</div>
@endsection

<script type="text/javascript">
    window.onload= function() {
        $.ajax({
            url:'{{ route('requerimentos.aprovados') }}',
            type:"get",
            dataType:'json',
            // data: {"filtro": "all" },
            success: function(response){
                // console.log(response.table_data);
                $('tbody').html(response.table_data);
            }
        });
    };

    var arrayTemp = [];
    var arrayTempDenuncia = [];

    window.addRequerimento = function($id) {

        if(arrayTemp.findIndex(element => element == $id) == -1){ //condicao para add o requerimento na lista

            $.ajax({
                url:'{{ config('prefixo.PREFIXO') }}encontrar/requerimento',
                type:"get",
                dataType:'json',
                data: {"requerimentoId": $id},
                success: function(response){

                    // innerText sempre pegará o primero texto da lista
                    var elemento = document.getElementById($id).innerText;
                    linha = montarLinhaInputRequerimento($id,elemento, response.tipo, response.cnae);
                    $('#adicionar').append(linha);
                    arrayTemp.push($id);
                }
            });
        }
    }

    window.addDenuncia = function($id) {
        console.log('CAMINHOOOO');
        if(arrayTempDenuncia.findIndex(element => element == $id) == -1){ //condicao para add o requerimento na lista

            // innerText sempre pegará o primero texto da lista
            var elemento = document.getElementById('empresa'+$id).innerText;
            linha = montarLinhaInputDenuncia($id,elemento);
            $('#adicionar').append(linha);
            arrayTempDenuncia.push($id);

            // $.ajax({
            //     url:'{{ config('prefixo.PREFIXO') }}encontrar/requerimento',
            //     type:"get",
            //     dataType:'json',
            //     data: {"requerimentoId": $id},
            //     success: function(response){

            //         // innerText sempre pegará o primero texto da lista
            //         var elemento = document.getElementById($id).innerText;
            //         linha = montarLinhaInputRequerimento($id,elemento, response.tipo, response.cnae);
            //         $('#adicionar').append(linha);
            //         arrayTemp.push($id);
            //     }
            // });
        }
    }

    // Montar linha para requerimento
    window.montarLinhaInputRequerimento = function(id,elemento, tipo, cnae){
        console.log("AQUUUUUUUUUUUUUEEEEEEEEEEEEEEEEEEEEEEE");
        return " <div class='d-flex justify-content-center form-gerado cardMeuCnae'>\n"+
            "            <div class='mr-auto p-2'>\n"+
            "                <div class='btn-group' style='margin-bottom:-15px;'>\n"+
            "                    <div class='form-group' style='font-size:15px;'>\n"+
            "                        <div class='textoCampo' id='"+id+"'>"+elemento+"</div>\n"+
            "                        <div>Tipo: <span class='textoCampo'>"+tipo+"</span></div>\n"+
            "                        <div>Cnae: <span class='textoCampo'>"+cnae+"</span></div>\n"+
            "                    </div>\n"+
            "                </div>\n"+
            "               <input type='hidden' name='requerimentos[]' value='"+id+"' required>\n"+
            "            </div>\n"+
            "            <div style='width:140px; height:25px; text-align:right;'>\n"+
            "                <div id='cardSelecionado' class='btn-group'>\n"+
            "                    <button type='button' class='btn btn-danger' value='"+id+"' onclick='deletar(this)'>X</button>\n"+
            "                </div>\n"+
            "            </div>\n"+
            "    </div>\n";
    }

    // Montar linha para denuncia
    window.montarLinhaInputDenuncia = function(id,elemento){
        console.log("AQUI MERMO");
        return " <div class='d-flex justify-content-center form-gerado cardMeuCnae'>\n"+
            "            <div class='mr-auto p-2'>\n"+
            "                <div class='btn-group' style='margin-bottom:-15px;'>\n"+
            "                    <div class='form-group' style='font-size:15px;'>\n"+
            "                        <div class='textoCampo' id='"+id+"'>"+elemento+"</div>\n"+
            "                        <div>Tipo: <span class='textoCampo'>Denúncia</span></div>\n"+
            "                    </div>\n"+
            "                </div>\n"+
            "               <input type='hidden' name='denuncias[]' value='"+id+"' required>\n"+
            "            </div>\n"+
            "            <div style='width:140px; height:25px; text-align:right;'>\n"+
            "                <div id='cardSelecionado' class='btn-group'>\n"+
            "                    <button type='button' class='btn btn-danger' value='"+id+"' onclick='deletarDenuncia(this)'>X</button>\n"+
            "                </div>\n"+
            "            </div>\n"+
            "    </div>\n";
    }

    // agente1 = null;
    // agente2 = null;

    // function agent1() {

    //     $('#agente2 option[id=y'+ this.agente1 +']').prop('disabled', false);

    //     var x = $("#agente1 option:selected").val();
    //     $('#agente2 option[id=y'+ x +']').prop('disabled', true);
    //     agente1 = x;

    // }

    // function agent2() {

    //     $('#agente1 option[id=y'+ this.agente2 +']').prop('disabled', false);

    //     var y = $("#agente2 option:selected").val();
    //     $('#agente1 option[id=y'+ y +']').prop('disabled', true);
    //     agente2 = y;
    // }

    function retirarAgente(select, id) {
        var optSelected = select.options[select.selectedIndex];
        var inputSelected = document.getElementById("agente"+id);
        var agentesSelecionados = [];
        var selectsAgentes = document.getElementsByClassName('agentes');

        for(var i = 0; i < selectsAgentes.length; i++) {

            var optionSelecionadoTemp = selectsAgentes[i].options[selectsAgentes[i].selectedIndex];
            if (optionSelecionadoTemp.value != "") {
                agentesSelecionados.push(optionSelecionadoTemp.value);
            }
        }

        for(var i = 0; i < selectsAgentes.length; i++) {
            for (var j = 0; j < selectsAgentes[i].children.length; j++) {
                if (agentesSelecionados.includes(selectsAgentes[i].children[j].value)) {
                    selectsAgentes[i].children[j].disabled = true;
                } else {
                    selectsAgentes[i].children[j].disabled = false;
                }
            }
        }

        inputSelected.value = optSelected.value;
    }
</script>
