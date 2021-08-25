<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inspeções do Dia</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        p {
            margin: 0 !important;
            padding: 0;
            display: inline;
        }

        img {
            width: 3.66cm;
            height: 2.52cm;
            margin-left: 80px;
            margin-top: 0.4cm;
        }

        main {
            margin-top: 1.2cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        h1 {
            font-size: 18px;
            text-align: center;
        }

        h2 {
            margin-left: 5px !important;
            font-size: 18px;
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        .pagenum:before {
            content: counter(page);
        }

        table {
            width: 100%;
            margin: 0px;
            padding: 0px;
        }

        tr {
            margin: 0px;
            padding: 0px;
        }

        td {
            margin: 0px;
            padding: 0px;
        }

        .page_break {
            page-break-before: always;
        }

        .center {
            margin-left: auto;
            margin-right: auto;
        }
    </style>

</head>
<body>
<header>
    <hr style="width: 98%; height: 3px; background-color: black; margin-bottom: 0px">
    <table width="100%">
        <tr>
            <td width="30%">
                <img src='{{public_path('imagens/logo_secSaude.png')}}'>
            </td>
            <td style="text-align: center; font-weight: bolder;">
                Prefeitura Municipal de Garanhuns<br>
                Secretaria Municipal de Saúde<br>
                Vigilância Sanitária
            </td>

        </tr>
    </table>
    <div style="margin-top: -3px">
        <hr style="width: 98%; height: 3px; background-color: red; margin-top: 0px; margin-bottom: 0px">
        <hr style="width: 98%; height: 3px; background-color: yellow; margin-top: 0px; margin-bottom: 0px">
        <hr style="width: 98%; height: 3px; background-color: darkgreen; margin-top: 0px; margin-bottom: 0px">
    </div>
</header>

<footer>
    <div style="font-size: 10px; text-align: center;">
        <p>
            SECRETARIA DE SAÚDE, VIGILÂNCIA SANITÁRIA, CENTRO ADMINISTRATIVO MUNICIPAL ARLINDA DA MOTA VALENÇA<br>
            RUA JOAQUIM TÁVORA, S/N, HELIÓPOLIS - FONE (87) 3761-7750, CEP: 55295-410 – GARANHUNS PE
        </p><br>
        <span style="text-align: center">Página <b><span class="pagenum"></span></b> de <b>2</b></span>
    </div>
</footer>

<main>
    <div>
        <h1>Relatório</h1><br>

        <div style="border-style: groove; border: 1px;"><h2 style="margin-left: 25px;">I. IDENTIFICAÇÃO DO
                ESTABELECIMENTO</h2></div>
        <div style="margin-left: 3%;margin-top: 10px;">
            <ul>
                @if($empresa != null)
                    <li><b>Razão Social: </b>{{$empresa->nome}}</li>
                    <li><b>CNPJ: </b> {{$empresa->cnpjcpf}}</li>
                    @if($empresa->nome_fantasia != null)
                        <li><b>Nome de Fantasia: </b>{{$empresa->nome_fantasia}}</li>
                    @endif
                    <li>
                        <table>
                            <tr>
                                <td style="width: 60%">
                                    <b>Endereço: </b>{{$endereço->rua}}
                                </td>
                                <td>
                                    <b>N°: </b>{{$endereço->numero}}
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        <table>
                            <tr>
                                <td style="width: 60%">
                                    <b>CEP: </b>{{$endereço->cep}}
                                </td>
                                <td>
                                    <b>Bairro: </b>{{$endereço->bairro}}
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        <table>
                            <tr>
                                <td style="width: 60%">
                                    <b>Complemento:</b>
                                    @if($endereço->complemento != null)
                                        {{$endereço->complemento}}
                                    @else
                                        Nenhum
                                    @endif
                                </td>
                                <td>
                                    <b>Cidade/UF: </b>{{$endereço->cidade}}/{{$endereço->uf}}
                                </td>
                            </tr>
                        </table>
                    </li>
                @else
                    <li><b>Razão Social: </b>{{$inspecao->nome_empresa}}</li>
                    <li><b>CNPJ: </b> Indefinido</li>
                    <li><b>Endereço: </b> {{$inspecao->endereco}}</li>
                @endif

            </ul>
        </div>

        <div style="border-style: groove; border: 1px;"><h2 style="margin-left: 25px;">II. DADOS DA INSPEÇÃO</h2></div>
        <div style="margin-left: 3%; margin-top: 10px;">
            <ul>
                <li>
                    <b>Data: </b><?php
                    $date = new DateTime($inspecao->data);
                    echo $date->format('d/m/Y');
                    ?>
                </li>
                <li>
                    <b>Objetivo: </b>{{$inspecao->motivo}}
                </li>
                <li>
                    <b>Equipe Fiscalização:</b>
                    {{$inspetor->user->name}}@foreach($agentesInspec as $agente), {{\App\Agente::where('id', '=', $agente->agente_id)->first()->user->name}}@endforeach.
                </li>
            </ul>
        </div>
        <div style="border-style: groove; border: 1px;"><h2 style="margin-left: 25px;">III. RELATÓRIO</h2></div>
        <div style="margin-left: 1%; font-size: 13px; margin-top: 10px; height: 43%">
            {!!$relatorio->relatorio !!}<br>
        </div>
        <div class="page_break"></div>
    </div>
    <div>
        <div style="border-style: groove; border: 1px; margin-top: 50px"><h2 style="margin-left: 25px;">IV. EQUIPE</h2>
        </div>
        <br><br>
        <div class="center text-center" style="line-height: 80%">
            <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 40%">
            <p>
                {{\App\User::where('tipo', '=', 'coordenador')->first()->name}}<br>
                Coordenador de Vig. Sanitária
            </p>
        </div>

        <div style="height: 30%"></div>
        <table>
            <tr>
                <td>
                    <div class="center text-center" style="line-height: 80%">
                        <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 80%">
                        <p>
                            {{$inspetor->user->name}}<br>
                            Inspetor Sanitário
                        </p>
                    </div>
                </td>
                <td>
                    <div class="center text-center" style="line-height: 80%">
                        <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 80%">
                        <p>
                            {{\App\Agente::where('id', '=', $agentesInspec[0]->agente_id)->first()->user->name}}<br>
                            Agente Sanitário
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <div style="height: 10%"></div>
        <table>
            <tr>
                <td>
                    <div class="center text-center" style="line-height: 80%">

                        @if(count($agentesInspec) > 2)
                            <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 80%">
                        @else
                            <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 40%">
                        @endif
                        <p>
                            {{\App\Agente::where('id', '=', $agentesInspec[1]->agente_id)->first()->user->name}}<br>
                            Agente Sanitário
                        </p>
                    </div>

                </td>
                @if(count($agentesInspec) > 2)
                    <td>
                        <div class="center text-center" style="line-height: 80%">

                            <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 80%">
                            <p>
                                {{\App\Agente::where('id', '=', $agentesInspec[2]->agente_id)->first()->user->name}}<br>
                                Agente Sanitário
                            </p>

                        </div>
                    </td>
                @endif
            </tr>
        </table>
        @if(count($agentesInspec) > 3)
            <div class="center text-center" style="line-height: 80%">
                <hr style="height:2px;border-width:0;color:gray;background-color:gray;margin-bottom: 0px;width: 40%">
                <p>

                    {{\App\Agente::where('id', '=', $agentesInspec[3]->agente_id)->first()->user->name}}<br>
                    Agente Sanitário
                </p>
            </div>
        @endif
    </div>
</main>
</body>
</html>
