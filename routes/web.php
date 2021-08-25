<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Servico;
use App\Empresa;
use App\Denuncia;
use App\Requerimento;
use App\Inspecao;
use App\Notificacao;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->tipo == "coordenador") {

            // $denunciasAcatado     = Denuncia::where('status', 'Acatado')->get();
            $denunciasTotal = Denuncia::all()->count();
            $denunciasArquivado = Denuncia::where('status', 'arquivado')->get();
            $denunciasAceito = Denuncia::where('status', 'aceito')->get();
            // $denunAcatado         = count($denunciasAcatado);
            $denunArquivado = count($denunciasArquivado);
            $denunAceito = count($denunciasAceito);

            $requerimentosAprovado = Requerimento::where('status', 'aprovado')->get();
            $requerimentosReprovado = Requerimento::where('status', 'reprovado')->get();
            $requerimentosPendente = Requerimento::where('status', 'pendente')->get();
            $reqAprovado = count($requerimentosAprovado);
            $reqReprovado = count($requerimentosReprovado);
            $reqPendente = count($requerimentosPendente);

            $inspecoesPendente = Inspecao::where('status', 'pendente')->get();
            $inspecoesCompleta = Inspecao::where('status', 'completo')->get();
            $inspecPendente = count($inspecoesPendente);
            $inspecCompleta = count($inspecoesCompleta);

            $empresasPendente = Empresa::where('status_cadastro', 'pendente')->get();
            $empresasAprovada = Empresa::where('status_cadastro', 'aprovado')->get();
            $empresasTotal = Empresa::all();
            $empPendente = count($empresasPendente);
            $empAprovada = count($empresasAprovada);
            $empTotal = count($empresasTotal);

            $notificacoesPendentes = Notificacao::where('status', 'pendente')->count();
            $notificacoesAprovadas = Notificacao::where('status', 'aprovado')->count();
            $notificacoesTotal = Notificacao::all()->count();

            return view('coordenador.home_coordenador',
                ['denunciasTotal' => $denunciasTotal,
                    'denunciasArquivado' => $denunArquivado,
                    'denunciasAceito' => $denunAceito,
                    'requerimentosAprovado' => $reqAprovado,
                    'requerimentosReprovado' => $reqReprovado,
                    'requerimentosPendente' => $reqPendente,
                    'inspecoesPendente' => $inspecPendente,
                    'inspecoesCompleta' => $inspecCompleta,
                    'empresasPendente' => $empPendente,
                    'empresasAprovada' => $empAprovada,
                    'empresasTotal' => $empTotal,
                    'notificacoesPendentes' => $notificacoesPendentes,
                    'notificacoesAprovadas' => $notificacoesAprovadas,
                    'notificacoesTotal' => $notificacoesTotal,
                ]);
        } elseif (Auth::user()->tipo == "empresa") {
            return redirect()->route('home.empresa');
        } elseif (Auth::user()->tipo == "inspetor") {
            return redirect()->route('home.inspetor');
        } elseif (Auth::user()->tipo == "agente") {
            return redirect()->route('home.agente');
        } elseif (Auth::user()->tipo == "rt") {
            return redirect()->route('home.rt');
        }
    } else {
        $resultado = Servico::orderBy('posicao', 'ASC')->take(4)->get();
        return view('naoLogado.home_naologado', ['servicos' => $resultado]);
    }
})->name("/");

Auth::routes();

// Completar cadastro de Inpetor
Route::get('/completar/cadastro/inspetor', 'InspetorController@create')->name('completar.cadastro.inspetor');
Route::post('/completar/cadastro/inspetor', 'InspetorController@store')->name('completar.cadastro.inspetor');

// Completar cadastro do Responsável Técnico
Route::get('/completar/cadastro/rt/criar', 'RespTecnicoController@criar')->name('completar.cadastro.rt.criar');
Route::post('/completar/cadastro/rt/salvar', 'RespTecnicoController@salvar')->name('completar.cadastro.rt.salvar');

// Completar cadastro de Agente
Route::get('/completar/cadastro/agente', 'AgenteController@create')->name('completar.cadastro.agente');
Route::post('/completar/cadastro/agente', 'AgenteController@store')->name('completar.cadastro.agente');

//Cadastro de empresa
Route::post("/empresa/cadastro", "EmpresaController@store")->name("cadastrar.empresa");
Route::get("/home/cadastro/empresa", "EmpresaController@create")->name("home.cadastrar");

// Denuncias
Route::get("/denuncias", function () {

    $empresa = Empresa::all();
    return view('naoLogado/denuncias', ['empresas' => $empresa]);
})->name("pagina.denuncia");
Route::post("/cadastrar/denuncia", "DenunciaController@cadastrarDenuncia")->name("cadastrar.denuncia");

// Aviso de pendencia de empresa
Route::get("/confirma/cadastro", function () {
    return view('empresa/aviso_empresa');
})->name("confirma.cadastro");

// Rota para busca de cnaes
Route::get("/cnaes/busca", "CnaeController@busca")->name("cnae.busca");

Route::middleware(['OnlyAdmin'])->group(function () {
    Route::post("/coordenador/cadastro", "CoordenadorController@store")->name("cadastrar.coordenador");
});

Route::get("/empresa/lista/cnae", "EmpresaController@ajaxCnaes")->name("ajax.lista.cnaes.comum");
Route::get("/empresa/dados", "EmpresaController@dadosEmpresa")->name("ajax.dados.empresa");
Route::get("/emcostrucao", function () {
    return view('em_construcao');
})->name("emconstrucao");
Route::get("/emcostrucao2", function () {
    return view('em_construcao2');
})->name("emconstrucao2");
Route::get("/emcostrucao3", function () {
    return view('em_construcao3');
})->name("emconstrucao3");
Route::get("/emcostrucao4", function () {
    return view('em_construcao4');
})->name("emconstrucao4");
Route::get("/servicos", "ServicoController@homeInformacoes")->name("home.informacao");
Route::get("/servicos/outros", "ServicoController@homeOutrasInformacoes")->name("home.outras.informacoes");


// Grupo de rotas para coordenador
Route::middleware(['IsCoordenador'])->group(function () {
    Route::get('/home/coordenador', 'CoordenadorController@home')->name('home.coordenador');
    Route::post("/inspetor/cadastro", "InspetorController@store")->name("cadastrar.inspetor");
    Route::get("/inspetor/paginaCadastro", "InspetorController@create")->name("pagina.inspetor");
    Route::post("/agente/cadastro", "AgenteController@store")->name("cadastrar.agente");
    Route::get("/agente/paginaCadastro", "AgenteController@create")->name("pagina.agente");
    Route::post("/area/cadastro", "AreaController@store")->name("cadastrar.area");
    Route::get("/area/paginaCadastro", "AreaController@create")->name("pagina.area");
    Route::get("/area/listagem", "AreaController@index")->name("listagem.area");
    Route::post("/cnae/cadastro", "CnaeController@store")->name("cadastrar.cnae");
    Route::get("/cnae/paginaCadastro", "CnaeController@create")->name("pagina.cnae");
    Route::get("/cnae/listagem", "CnaeController@index")->name("listagem.cnae");
    Route::get("/listar/inspetores", "InspetorController@listarInspetores")->name("listar.inspetores");
    Route::get("/listar/agentes", "AgenteController@listarAgentes")->name("listar.agentes"); //requerimento_coordenador

    //Tela de Requerimento
    Route::get("/requerimento/inspetor", "CoordenadorController@listarRequerimentoInspetorEAgente")->name("pagina.requerimento");
    Route::get("/requerimento", "CoordenadorController@ajaxListarRequerimento")->name("lista.requerimento");
    Route::get("/denuncia", "CoordenadorController@ajaxListarDenuncia")->name("lista.denuncia");

    // Listar e baixar arquivos de uma empresa
    Route::get("/empresa/arquivos", "EmpresaController@listarArquivos")->name("empresa.arquivos");
    Route::get("/baixar/arquivos", "EmpresaController@baixarArquivos")->name("baixar.arquivos");
    // Rota para listar empresas com cadastro pendentes
    Route::get("/cadastros/pendentes", "CoordenadorController@listarPendente")->name("listar.cadastroPendente");
    // Rota para avaliação de primeiro cadastro de usuario e empresa
    Route::post("/pagina/detalhes", "CoordenadorController@paginaDetalhes")->name("pagina.detalhes");
    Route::post("/julgar/cadastro", "CoordenadorController@julgar")->name("julgar.cadastro");


    Route::get("/empresa/listagem", "EmpresaController@index")->name("listagem.empresas");
    Route::get("/show/empresa", "EmpresaController@show")->name("mostrar.empresas");

    // Rotas para convidar Inspetores e agente
    Route::post("/convidar/inspetor", "CoordenadorController@convidarEmail")->name("convidar.inspetor");
    Route::post("/convidar/agente", "CoordenadorController@convidarEmail")->name("convidar.agente");

    Route::post("/licenca", "CoordenadorController@licenca")->name("licenca");
    Route::post("/dispensa", "CoordenadorController@dispensa")->name("dispensa");
    Route::get('/dispensa/{id_dispensa}/download','EmpresaController@download')->name('dispensa.download');

    Route::post("/julgar/requerimento", "CoordenadorController@julgarRequerimento")->name("julgar.requerimento");

    Route::get('/download/arquivo/avaliar/requerimento', 'CoordenadorController@downloadArquivo')->name('download.arquivo.avaliar.requerimento');

    // Rota para localizar
    Route::get("/coordenador/localizar", "CoordenadorController@localizar");

    Route::get("/criar/inspecao", "CoordenadorController@criarInspecao")->name("criar.inspecao");
    Route::get("/requerimentos/aprovados", "CoordenadorController@requerimentosAprovados")->name("requerimentos.aprovados");
    Route::get("/cadastro/inspecao/{inspecao_id}", "CoordenadorController@InspecaoDiversa")->name("cadastro.inspecao");
    Route::post("/cadastrar/inspecao", "CoordenadorController@cadastrarInspecao")->name("cadastrar.inspecao");
    Route::post("/cadastrar/inspecaoDiversa", "CoordenadorController@cadastrarInspecaoDiversa")->name("cadastrar.inspecaoDiversa");
    Route::get("/encontrar/requerimento", "CoordenadorController@encontrarRequerimento")->name("encontrar.requerimento");
    Route::get("/historico/inspecoes", "CoordenadorController@historico")->name("historico.inspecoes");
    Route::get("/pdf", "CoordenadorController@nameMethod")->name("gerar.pdf");
    Route::get("/imprimirRelatorio", "CoordenadorController@imprimirRelatorio")->name("imprimir.relatorio");
    // Rota para gerenciar conteudo
    Route::get("/coordenador/gerenciarconteudo", "ServicoController@index")->name("servico.index");
    Route::get("/coordenador/gerenciarconteudo/criar/servico", "ServicoController@ajaxCriarServico");
    Route::get("/coordenador/gerenciarconteudo/deletar/servico", "ServicoController@ajaxDeletarServico");
    Route::get("/coordenador/gerenciarconteudo/editar/servico", "ServicoController@ajaxEditarServico");
    Route::get("/coordenador/gerenciarconteudo/editar/subir/servico", "ServicoController@ajaxEditarSubirServico");
    Route::get("/coordenador/gerenciarconteudo/editar/descer/servico", "ServicoController@ajaxEditarDescerServico");
    // Rota para gerenciar a secao
    Route::get("/coordenador/gerenciarconteudo/secao", "ServicoController@indexSecao")->name("secao.index");
    Route::get("/coordenador/gerenciarconteudo/criar/secao", "ServicoController@ajaxCriarSecao");
    Route::get("/coordenador/gerenciarconteudo/editar/secao", "ServicoController@ajaxEditarSecao");
    Route::get("/coordenador/gerenciarconteudo/editar/subir/secao", "ServicoController@ajaxEditarSubirSecao");
    Route::get("/coordenador/gerenciarconteudo/deletar/secao", "ServicoController@ajaxDeletarSecao");

    // Página de denúncia
    Route::get("/pagina/denuncia/coordenador", "CoordenadorController@paginaDenuncias")->name("pagina.denuncia.coordenador");
    Route::get("/pagina/detalhes/denuncia", "CoordenadorController@paginaDetalhesDenuncia")->name("pagina.detalhes.denuncia");
    Route::post("/avaliar/denuncia", "CoordenadorController@avaliarDenuncia")->name("avaliar.denuncia");

    // Deletar inspecao
    Route::post("/deletar/inspecao", "CoordenadorController@deletarInspecao")->name("deletar.inspecao");

    // Pagina de relatorio
    Route::get('/programacao/coordenador/inspecao/relatorio', 'CoordenadorController@showRelatorio')->name('show.relatorio.coordenador');
    Route::get('/programacao/coordenador/inspecao/relatorio/verificar', 'CoordenadorController@showRelatorioVerificar')->name('show.relatorio.coordenador.verificar');
    Route::post("/coordenador/julgar/relatorio", "CoordenadorController@julgarRelatorio")->name("julgar.relatorio.coordenador");

    // Pagina de notificacao
    Route::get('/programacao/coordenador/inspecao/notificacao', 'CoordenadorController@showNotificacao')->name('show.notificacao.coordenador');
    Route::get('/programacao/coordenador/inspecao/notificacao/verificar', 'CoordenadorController@showNotificacaoVerificar')->name('show.notificacao.coordenador.verificar');
    Route::post("/coordenador/julgar/notificacao", "CoordenadorController@julgarNotificacao")->name("julgar.notificacao.coordenador");

    Route::get("/listar/rts", "CoordenadorController@listarRts")->name("listar.rts"); //Listagem de responsáveis técnicos para o coordenadaor
    Route::get("/documentos/rt", "CoordenadorController@documentosRt")->name("documentos.rt");

    Route::get('/coordenador/download/arquivo/rt', 'CoordenadorController@baixarArquivosRt')->name('coordenador.download.arquivo.rt');
    Route::get('/denuncia/inspecao', 'CoordenadorController@denunciaInspecao')->name('denuncia.inspecao');
    Route::get('/criar/area', 'CoordenadorController@criarArea')->name('criar.area');
    Route::get('/criar/cnae', 'CoordenadorController@criarCnae')->name('criar.cnae');
    Route::get('/denuncia/imagens', 'CoordenadorController@imagensDenuncia')->name('denuncia.imagens');
    Route::get('/editar/tipodocumentos', 'CoordenadorController@tipodocumentos')->name('editar.tipodocumentos');
    Route::post('/editar/tipodoc', 'CoordenadorController@editartipodoc')->name('editar.tipodoc');
    Route::post('/criar/tipodoc', 'CoordenadorController@criartipodoc')->name('criar.tipodoc');
    Route::get('/editar/areas', 'CoordenadorController@editarArea')->name('editar.area');
    Route::get('/editar/cnae', 'CoordenadorController@editarCnae')->name('editar.cnae');
    Route::get('/buscar/tiposDocs', 'CoordenadorController@buscarTiposDocs')->name('marcar.checkbox.tiposDocs');
    Route::post('/area/editar', 'CoordenadorController@areaEditar')->name('area.editar');
    Route::post('/cnae/editar', 'CoordenadorController@cnaeEditar')->name('cnae.editar');
    Route::get('/encontrar/agente', 'CoordenadorController@encontrarAgente')->name('encontrar.agente');
    Route::get('/encontrar/inspetor', 'CoordenadorController@encontrarInspetor')->name('encontrar.inspetor');

});

Route::post('/solicitar/dispensa/empresa', 'EmpresaController@solicitarDispensa')->name('solicitar.dispensa.empresa');
Route::get('/solicitar/dispensa', 'EmpresaController@dispensa')->name('solicitar.dispensa');

// Grupo de rotas para empresa
Route::middleware(['IsEmpresa'])->group(function () {
    //Empresa - Gerente
    Route::get('/home/empresa', 'EmpresaController@home')->name('home.empresa');
    Route::post("/empresa/arquivos", "EmpresaController@anexarArquivos")->name("anexar.arquivos");
    Route::post("/empresa/editar/arquivos", "EmpresaController@editarArquivos")->name("editar.arquivos");
    Route::get("/listar/arquivos", "EmpresaController@listarArquivos")->name("listar.arquivos");
    Route::get("/empresa/pagina/responsavelTecnico", "RespTecController@create")->name("pagina.respTec");
    Route::post("/empresa/cadastro/responsavelTecnico", "RespTecController@store")->name("cadastrar.respTec");
    Route::get("/estabelecimento/adicionar/", "EmpresaController@paginaCadastrarEmpresa")->name("pagina.adicionar.empresa");
    Route::get("/estabelecimento/perfil/", "EmpresaController@showEmpresa")->name("pagina.mostrar.empresa");
    Route::get("/estabelecimento/documentacao/", "EmpresaController@showDocumentacao")->name("pagina.mostrar.documentacao");
    Route::post("/empresa/cadastro/responsavelTecnico", "EmpresaController@adicionarEmpresa")->name("adicionar.empresa");
    Route::get("/listar/empresas/", "EmpresaController@listarEmpresas")->name("listar.empresas");
    Route::get("/estabelecimento/lista/cnae", "EmpresaController@ajaxCnaes")->name("ajax.lista.cnaes");
    Route::get("/listar/responsavelTecnico", "EmpresaController@listarResponsavelTec")->name("listar.responsavelTec");

    //tela de editar dados do gerente
    Route::get('/editar/meusdados', "EmpresaController@editarMeusDados")->name('editar.gerente');
    Route::post('/atualizar/meusdados', "EmpresaController@atualizarMeusDados")->name('atualizar.gerente.nome');

    //tela de editar senha de acesso do gerente
    Route::get('/editar/gerente/senha', function () {
        return view('empresa/editar_senha_de_acesso');
    })->name('editar.senha.gerente');
    Route::post('/atualizar/gerente/senha', "EmpresaController@atualizarSenhaDeAcesso")->name('atualizar.gerente');

    //Tela de editar
    Route::get("/pagina/editar", "EmpresaController@edit")->name("pagina.editar.empresa");
    Route::post("/editar/empresa", "EmpresaController@editarEmpresa")->name("editar.empresa");
    Route::get("/listar/cnae/empresa", "EmpresaController@ajaxCnaesEmpresa");
    Route::get("/listar/cnae/add/empresa", "EmpresaController@ajaxAddCnae_editarEmpresa");


    // Cadastro de Responsável Técnico
    Route::get('/cadastro/respTecnico', 'RespTecnicoController@create')->name('cadastrar.rt.pagina');
    Route::post('/cadastro/respTecnico', 'RespTecnicoController@store')->name('cadastrar.rt');

    // Encontrar item da checklist
    Route::get('/foundChecklist', 'EmpresaController@foundChecklist')->name('found.checklist');

    // Download de arquivos anexados
    Route::get('/download/arquivo', 'EmpresaController@downloadArquivo')->name('download.arquivo');

    Route::get('/encontrar/doc', 'EmpresaController@findDoc')->name('find.doc');

    // Apagar registro em CnaeEmpr
    Route::get('/apagar/cnae/empresa', 'EmpresaController@apagarCnaeEmpresa')->name('apagar.cnae.empresa');

    Route::get('/requerimentos/representante', 'EmpresaController@requerimentos')->name('mostrar.requerimentos');
    Route::get('/cnae/encontrar/empresa', 'EmpresaController@encontrarCnae')->name('encontrar.cnae.empresa');
    Route::post('/cadastro/requerimento/empresa', 'EmpresaController@cadastrarRequerimento')->name('cadastrar.requerimento.empresa');

    Route::get('/verificar/requerimento/inspecao', 'EmpresaController@verificarRequerimentoInspecao')->name('varificar.requerimento.inspecao');

    Route::get('/notificacoes/representante', 'EmpresaController@notificacoes')->name('mostrar.notificacoes');

    Route::get("/empresa/documentos/rt", "EmpresaController@documentosRt")->name("empresa.documentos.rt");

    Route::post("/empresa/rt/deletar", "EmpresaController@deletarRespTecnico")->name("deletar.respTecnico");

    Route::get('/empresa/download/arquivo/rt', 'EmpresaController@baixarArquivosRt')->name('empresa.download.arquivo.rt');

    Route::get("/gerar/situacao/", "EmpresaController@gerarSituacao")->name("gerar.situacao");

    Route::get("/estabelecimento/encontrar/notificacoes", "EmpresaController@encontrarNotificacoes")->name("encontrar.notificacoes");
});

// Grupo de rotas para inspetor
Route::middleware(['IsInspetor'])->group(function () {
    Route::get('/home/inspetor', 'InspetorController@home')->name('home.inspetor');
    Route::get('/programacao/inspetor', 'InspetorController@showProgramacao')->name('show.programacao');
    Route::get('/programacao/inspecao/album', 'InspetorController@showAlbum')->name('show.album');
    Route::post('/delete/foto', 'InspetorController@deleteFoto')->name('delete.foto');
    Route::post('/save/descricao', 'InspetorController@saveDescricao')->name('save.descricao');
    Route::get('/programacao/inspecao/relatorio', 'InspetorController@showRelatorio')->name('show.relatorio');
    Route::post('/save/relatorio', 'InspetorController@saveRelatorio')->name('save.relatorio');
    Route::get('/historico/inspetor', 'InspetorController@showHistorico')->name('show.historico');
    Route::get('/criar/notificacao', 'InspetorController@criarNotificacao')->name('criar.notificacao');
    Route::get('/verificar/notificacao', 'InspetorController@verificarNotificacao')->name('verificar.notificacao');
    Route::post('/apagar/notificacao', 'InspetorController@apagarNotificacao')->name('apagar.notificacao');
    Route::post('/save/notificacao', 'InspetorController@saveNotificacao')->name('save.notificacao');
    Route::get('/editar/inspetor/dados', 'InspetorController@alterarDados')->name('editar.dados.inspetor');
    Route::get('/editar/inspetor/senha', 'InspetorController@alterarSenha')->name('editar.senha.inspetor');
    Route::post('/atualizar/dados/inspetor', 'InspetorController@atualizarDados')->name('atualizar.dados.inspetor');
    Route::post('/atualizar/senha/inspetor', 'InspetorController@atualizarSenha')->name('atualizar.senha.inspetor');
    Route::get('/editar/notificacao', 'InspetorController@editarNotificacao')->name('editar.notificacao');
    Route::post('/update/notificacao', 'InspetorController@updateNotificacao')->name('update.notificacao');

});

// Grupo de rotas para Agente
Route::middleware(['IsAgente'])->group(function () {
    Route::get('/home/agente', 'AgenteController@home')->name('home.agente');
    Route::get('cadastrar/agente', function () {
        return view('agente/cadastrar_agente');
    })->name('cadastrar.agente');
    Route::get('/programacao/agente', 'AgenteController@showProgramacao')->name('show.programacao.agente');
    Route::get('/programacao/agente/inspecao/relatorio', 'AgenteController@showRelatorio')->name('show.relatorio.agente');
    Route::get('/programacao/agente/inspecao/relatorio/verificar', 'AgenteController@showRelatorioVerificar')->name('show.relatorio.agente.verificar');
    Route::post("/julgar/relatorio", "AgenteController@julgar")->name("julgar.relatorio");
    Route::get('/editar/agente/dados', 'AgenteController@alterarDados')->name('editar.dados.agente');
    Route::get('/editar/agente/senha', 'AgenteController@alterarSenha')->name('editar.senha.agente');
    Route::post('/atualizar/dados/agente', 'AgenteController@atualizarDados')->name('atualizar.dados.agente');
    Route::post('/atualizar/senha/agente', 'AgenteController@atualizarSenha')->name('atualizar.senha.agente');
});

// Grupo de rotas para responsável técnico
Route::middleware(['IsRespTecnico'])->group(function () {
    Route::get('/editar/dados', 'RespTecnicoController@edit')->name('editar.dados');

    Route::get('/home/rt', 'RespTecnicoController@home')->name('home.rt');

    Route::post('/atualizar/rt', 'RespTecnicoController@update')->name('update.rt');

    Route::get('/rt/documentos', 'RespTecnicoController@showDocumentacao')->name('rt.documentos');

    Route::post("/rt/arquivos", "RespTecnicoController@anexarArquivos")->name("anexar.arquivos.rt");

    Route::get('/download/arquivo/rt', 'RespTecnicoController@baixarArquivos')->name('download.arquivo.rt');

    Route::get('/encontrar/doc/rt', 'RespTecnicoController@findDocRt')->name('find.doc.rt');

    Route::post("/rt/editar/arquivos", "RespTecnicoController@editarArquivos")->name("editar.arquivos.rt");

    Route::get('/empresas', 'RespTecnicoController@listarEmpresas')->name('listar.empresa.rt');

    Route::get('/empresa', 'RespTecnicoController@showEmpresa')->name('empresa');

    Route::get('/empresa/documentacao', 'RespTecnicoController@documentacaoEmpresa')->name('rt.documentacao.empresa');

    Route::get('/download/arquivo/empresa/rt', 'RespTecnicoController@downloadArquivo')->name('download.arquivo.empresa');

    Route::get('/rt/requerimento', 'RespTecnicoController@criarRequerimento')->name('criar.requerimento');

    Route::post('/cadastro/requerimento', 'RespTecnicoController@cadastrarRequerimento')->name('cadastrar.requerimento');

    Route::post("/empresa/arquivos/rt", "RespTecnicoController@anexarArquivosEmpresa")->name("anexar.arquivos.empresa.rt");

    Route::get('/encontrar/doc/empresa/rt', 'RespTecnicoController@findDoc')->name('find.doc.empresa.rt');

    Route::post("/empresa/editar/arquivos/rt", "RespTecnicoController@editarArquivosEmpRt")->name("editar.arquivos.empresa.rt");

    Route::get('/cnae/encontrar', 'RespTecnicoController@encontrarCnae')->name('encontrar.cnae');

    //tela de editar senha de acesso do rt
    Route::get('/editar/rt/senha', function () {
        return view('responsavel_tec/editar_senha_de_acesso');
    })->name('editar.senha.rt');
    Route::post('/atualizar/rt/senha', "RespTecnicoController@atualizarSenhaDeAcesso")->name('atualizar.rt');

    Route::get('/empresa/notificacao', 'RespTecnicoController@notificacaoEmpresa')->name('rt.notificacao.empresa');
    Route::get("/gerar/situacao/rt", "RespTecnicoController@gerarSituacao")->name("gerar.situacao.rt");
    Route::get("/estabelecimento/encontrar/notificacoes/rt", "RespTecnicoController@encontrarNotificacoes")->name("encontrar.notificacoes.rt");

});

//Adicionais
/*
    * Enviar dados de formulario de inspeção
*/
