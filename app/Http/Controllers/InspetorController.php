<?php

namespace App\Http\Controllers;

use App\RelatorioAgentes;
use Illuminate\Http\Request;
use App\Inspetor;
use App\User;
use App\Inspecao;
use App\Endereco;
use App\InspecaoFoto;
use App\InspecaoRelatorio;
use App\Telefone;
use App\Notificacao;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class InspetorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listarInspetores()
    {
        $inspetores = User::where("tipo", "inspetor")->where("status_cadastro", "aprovado")->get();
        return view('coordenador/inspetores_coordenador', ['inspetores' => $inspetores]);
    }

    public function alterarDados(Request $request)
    {
        $inspetor = Inspetor::where('user_id', $request->user)->first();
        // dd($inspetor);
        return view('inspetor/editar_dados', [
            'nome' => $inspetor->user->name,
            'cpf' => $inspetor->cpf,
            'formacao' => $inspetor->formacao,
            'especializacao' => $inspetor->especializacao,
            'telefone' => $inspetor->telefone,
        ]);
    }

    public function atualizarDados(Request $request)
    {

        $messages = [
            'required' => 'O campo :attribute não foi passado!',
            'string' => 'O campo :attribute deve ser do tipo texto!',
        ];

        $validator = Validator::make($request->all(), [

            'name' => 'required|string',
            'cpf' => 'required|string',
            'formacao' => 'nullable|string',
            'especializacao' => 'nullable|string',
            'telefone' => 'required|string',

        ], $messages);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $inspetor = Inspetor::where("user_id", Auth::user()->id)->first();
        $user = User::find($inspetor->user_id);
        // dd($inspetor);

        $user->name = $request->name;
        $inspetor->cpf = $request->cpf;
        $inspetor->telefone = $request->telefone;
        $inspetor->formacao = $request->formacao;
        $inspetor->especializacao = $request->especializacao;

        $inspetor->save();
        $user->save();

        session()->flash('success', 'Dados atualizados!');
        return back();
    }

    public function alterarSenha(Request $request)
    {
        // $inspetor = Inspetor::where('user_id', $request->user)->first();
        // dd($inspetor->user->password);
        // $senha = Crypt::decrypt($inspetor->user->password);
        return view('inspetor/editar_senha');
    }

    public function atualizarSenha(Request $request)
    {
        if (Hash::check($request->senhaAtual, Auth::user()->password) == true && $request->novaSenha1 == $request->novaSenha2) {
            $user = Auth::user();
            $user->password = Hash::make($request->novaSenha1);
            $user->save();
            return redirect()->back()->with('success', "Senha alterada com sucesso!");
        } else {
            return redirect()->back()->with('error', "Verifique suas senhas e tente novamente!");
        }
    }

    public function home()
    {
        $token = User::where('id', '=', Auth::user()->id)->first();
        $inspetor = Inspetor::where('user_id', '=', Auth::user()->id)->first();
        $pendente = Inspecao::where('inspetor_id', $inspetor->id)->where('status', 'pendente')->orderBy('data', 'ASC')->count();
        $aprovado = Inspecao::where('inspetor_id', $inspetor->id)->where('status', 'aprovado')->orderBy('data', 'ASC')->count();

        $aviso = $token->app_token;
        if ($aviso == null) {
            return view('inspetor.home_inspetor', ['pendente' => $pendente, 'aprovado' => $aprovado, 'aviso' => 0]);
        } else {
            return view('inspetor.home_inspetor', ['pendente' => $pendente, 'aprovado' => $aprovado, 'aviso' => 1]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::user()->id);
        // Tela de conclusão de cadastro de agente
        return view('inspetor.cadastrar_inspetor')->with(["user" => $user->email]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $messages = [
            'unique' => 'Um campo igual a :attribute já está cadastrado no sistema!',
            'required' => 'O campo :attribute não foi passado!',
            'string' => 'O campo :attribute deve ser texto!',
        ];

        $validator = Validator::make($request->all(), [

            'nome' => 'required|string',
            'formacao' => 'nullable|string',
            'especializacao' => 'nullable|string',
            'cpf' => 'required|string|unique:agente,cpf',
            'telefone' => 'required|string',
            'password' => 'required',

        ], $messages);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        // Atualiza dados de user para inspetor
        $user->name = $request->nome;
        $user->password = bcrypt($request->password);
        $user->status_cadastro = "aprovado";
        $user->save();

        $inspetor = Inspetor::create([
            'formacao' => $request->formacao,
            'especializacao' => $request->especializacao,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'user_id' => $user->id,
        ]);


        return redirect()->route('/');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function criarNotificacao(Request $request)
    {
        $inspecao = Inspecao::find(Crypt::decrypt($request->inspecao));
        $notificacao = Notificacao::where('inspecoes_id', '=', $inspecao->id)->first();

        if ($notificacao == null) {
            return view('inspetor/criar_notificacao', ['inspecao_id' => $inspecao->id, 'notificacao' => ""]);
            // return view('inspetor/relatorio_inspetor',['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => ""]);
        } else {
            return view('inspetor/criar_notificacao', ['inspecao_id' => $inspecao->id, 'notificacao' => $notificacao->notificacao]);
            // return view('inspetor/relatorio_inspetor',['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => $relatorio->relatorio]);
        }
    }

    public function editarNotificacao(Request $request)
    {

        $inspecao = Inspecao::find(Crypt::decrypt($request->inspecao));
        $notificacao = Notificacao::where('inspecoes_id', '=', $inspecao->id)->get();

        if ($notificacao == null) {
            return view('inspetor/editar_notificacao', ['inspecao_id' => $inspecao->id, 'notificacao' => ""]);
            // return view('inspetor/relatorio_inspetor',['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => ""]);
        } else {
            return view('inspetor/editar_notificacao', ['inspecao_id' => $inspecao->id, 'notificacao' => $notificacao]);
            // return view('inspetor/relatorio_inspetor',['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => $relatorio->relatorio]);
        }
    }

    public function saveNotificacao(Request $request)
    {
        $verifica = Notificacao::where('inspecoes_id', '=', $request->inspecao_id)->exists();
        // $numAgentes = InspecAgente::where('inspecoes_id',$request->inspecao_id)->count();

        if ($verifica == true) { //atualizo

            // $atualizar = Notificacao::where('inspecoes_id','=',$request->inspecao_id)->first();
            // $atualizar->update(['notificacao'=>$request->notificacao]);
            // $atualizar->status = "pendente";

            // $atualizar->save();
            // return redirect()->route('show.programacao')->with('success', "Notificação foi atualizada com sucesso e reenviada para nova análise do coordenador!");

        } else { //salvo

            for ($i = 0; $i < count($request->item); $i++) {

                $notificacao = new Notificacao;
                $notificacao->inspecoes_id = $request->inspecao_id;
                $notificacao->exigencia = $request->exigencia[$i];
                $notificacao->status = "pendente";
                $notificacao->item = $request->item[$i];
                $notificacao->prazo = $request->prazo[$i];
                $notificacao->save();
            }

            return redirect()->route('show.programacao')->with('success', "Notificação foi salva e enviada para análise do coordenador!");
        }
    }

    public function updateNotificacao(Request $request)
    {

        if (!isset($request->item) || !isset($request->exigencia) || !isset($request->prazo)) {
            session()->flash('error', 'Lista de notificações anteriormente criada não pode estar vazia!');
            return back();
        }

        $verifica = Notificacao::where('inspecoes_id', '=', $request->inspecao_id)->delete();
        // $numAgentes = InspecAgente::where('inspecoes_id',$request->inspecao_id)->count();

        for ($i = 0; $i < count($request->item); $i++) {

            $notificacao = new Notificacao;
            $notificacao->inspecoes_id = $request->inspecao_id;
            $notificacao->exigencia = $request->exigencia[$i];
            $notificacao->status = "pendente";
            $notificacao->item = $request->item[$i];
            $notificacao->prazo = $request->prazo[$i];
            $notificacao->save();
        }

        return redirect()->route('show.programacao')->with('success', "As notificações foram atualizadas e voltaram para análise do coordenador!");

        // if($verifica == true){

        // $atualizar = Notificacao::where('inspecoes_id','=',$request->inspecao_id)->first();
        // $atualizar->update(['notificacao'=>$request->notificacao]);
        // $atualizar->status = "pendente";

        // $atualizar->save();
        // return redirect()->route('show.programacao')->with('success', "Notificação foi atualizada com sucesso e reenviada para nova análise do coordenador!");

        // }else{

        //     for ($i=0; $i < count($request->item); $i++) {

        //         $notificacao = new Notificacao;
        //         $notificacao->inspecoes_id = $request->inspecao_id;
        //         $notificacao->exigencia = $request->exigencia[$i];
        //         $notificacao->status = "pendente";
        //         $notificacao->item = $request->item[$i];
        //         $notificacao->prazo = $request->prazo[$i];
        //         $notificacao->save();
        //     }

        //     return redirect()->route('show.programacao')->with('success', "Notificação foi salva e enviada para análise do coordenador!");
        // }
    }

    public function inspecoes(Request $request)
    {
        $inspecoes = Inspecao::where('inspetor_id', 1)
            ->where('status', 'pendente')->get();
        $temp = [];

        foreach ($inspecoes as $indice) {
            $endereco = Endereco::where('empresa_id', $indice->requerimento->empresa->id)
                ->first();
            $telefone = Telefone::where('empresa_id', $indice->requerimento->empresa->id)
                ->first();

            $obj = (object)array(
                'empresa_nome' => $indice->requerimento->empresa->nome,
                'rua' => $endereco->rua,
                'numero' => $endereco->numero,
                'bairro' => $endereco->bairro,
                'cep' => $endereco->cep,
                'cnpjcpf' => $indice->requerimento->empresa->cnpjcpf,
                'representante_legal' => $indice->requerimento->empresa->user->name,
                'telefone1' => $telefone->telefone1,
                'telefone2' => $telefone->telefone2,
                'data' => $indice->data,
                'status' => $indice->status,
            );
            array_push($temp, $obj);
        }
    }

    /*
    * FUNCAO: Mostrar a pagina de programacao
    * ENTRADA:
    * SAIDA: Listar inspecoes programadas para o inspetor
    */
    public function showProgramacao()
    {
        $inspetor = Inspetor::where('user_id', '=', Auth::user()->id)->first();
        $inspecao = Inspecao::where('inspetor_id', $inspetor->id)->orderBy('data', 'ASC')->get();
        $inspecoes = [];

        foreach ($inspecao as $indice) {
            $relatorio = InspecaoRelatorio::where('inspecao_id', $indice->id)
                ->first();
            $notificacao = Notificacao::where('inspecoes_id', $indice->id)->first();

            if ($indice->requerimento_id == null) {
                if ($relatorio != null) {
                    if ($notificacao != null) {
                        $obj = (object)array(
                            'data' => $indice->data,
                            'statusInspecao' => $indice->status,
                            'motivoInspecao' => $indice->motivo,
                            'inspetor_id' => $indice->inspetor_id,
                            'requerimento_id' => null,
                            'nomeEmpresa' => $indice->denuncia->empresa,

                            'relatorio_id' => $relatorio->id,
                            'inspecao_id' => $indice->id,
                            'relatorio_status' => $relatorio->status,
                            'notificacao_status' => $notificacao->status,
                        );
                        array_push($inspecoes, $obj);
                    } else {
                        $obj = (object)array(
                            'data' => $indice->data,
                            'statusInspecao' => $indice->status,
                            'motivoInspecao' => $indice->motivo,
                            'inspetor_id' => $indice->inspetor_id,
                            'requerimento_id' => null,
                            'nomeEmpresa' => $indice->denuncia->empresa,

                            'relatorio_id' => $relatorio->id,
                            'inspecao_id' => $indice->id,
                            'relatorio_status' => $relatorio->status,
                            'notificacao_status' => null,
                        );
                        array_push($inspecoes, $obj);
                    }
                } else {
                    $obj = (object)array(
                        'data' => $indice->data,
                        'statusInspecao' => $indice->status,
                        'motivoInspecao' => $indice->motivo,
                        'inspetor_id' => $indice->inspetor_id,
                        'requerimento_id' => null,
                        'nomeEmpresa' => $indice->denuncia->empresa,

                        'relatorio_id' => null,
                        'inspecao_id' => $indice->id,
                        'relatorio_status' => null,
                        'notificacao_status' => null,
                    );
                    array_push($inspecoes, $obj);
                }
            } else {
                if ($relatorio != null) {
                    if ($notificacao != null) {
                        if ($indice->nome_empresa != null) {
                            $obj = (object)array(
                                'data' => $indice->data,
                                'statusInspecao' => $indice->status,
                                'motivoInspecao' => $indice->motivo,
                                'inspetor_id' => $indice->inspetor_id,
                                'cnae' => $indice->requerimento->cnae->descricao,
                                'nomeEmpresa' => $indice->nome_empresa,

                                'relatorio_id' => $relatorio->id,
                                'inspecao_id' => $indice->id,
                                'relatorio_status' => $relatorio->status,
                                'notificacao_status' => $notificacao->status,
                            );
                        } else {
                            $obj = (object)array(
                                'data' => $indice->data,
                                'statusInspecao' => $indice->status,
                                'motivoInspecao' => $indice->motivo,
                                'inspetor_id' => $indice->inspetor_id,
                                'cnae' => $indice->requerimento->cnae->descricao,
                                'nomeEmpresa' => $indice->empresa->nome,

                                'relatorio_id' => $relatorio->id,
                                'inspecao_id' => $indice->id,
                                'relatorio_status' => $relatorio->status,
                                'notificacao_status' => $notificacao->status,
                            );
                        }
                        array_push($inspecoes, $obj);
                    } else {
                        if ($indice->nome_empresa != null) {
                            $obj = (object)array(
                                'data' => $indice->data,
                                'statusInspecao' => $indice->status,
                                'motivoInspecao' => $indice->motivo,
                                'inspetor_id' => $indice->inspetor_id,
                                'cnae' => $indice->requerimento->cnae->descricao,
                                'nomeEmpresa' => $indice->nome_empresa,

                                'relatorio_id' => $relatorio->id,
                                'inspecao_id' => $indice->id,
                                'relatorio_status' => $relatorio->status,
                                'notificacao_status' => null,
                            );
                        } else {
                            $obj = (object)array(
                                'data' => $indice->data,
                                'statusInspecao' => $indice->status,
                                'motivoInspecao' => $indice->motivo,
                                'inspetor_id' => $indice->inspetor_id,
                                'cnae' => $indice->requerimento->cnae->descricao,
                                'nomeEmpresa' => $indice->empresa->nome,

                                'relatorio_id' => $relatorio->id,
                                'inspecao_id' => $indice->id,
                                'relatorio_status' => $relatorio->status,
                                'notificacao_status' => null,
                            );
                        }
                        array_push($inspecoes, $obj);
                    }
                } else {
                    if ($indice->nome_empresa != null) {
                        $obj = (object)array(
                            'data' => $indice->data,
                            'statusInspecao' => $indice->status,
                            'motivoInspecao' => $indice->motivo,
                            'inspetor_id' => $indice->inspetor_id,
                            'cnae' => $indice->requerimento->cnae->descricao,
                            'nomeEmpresa' => $indice->nome_empresa,

                            'relatorio_id' => null,
                            'inspecao_id' => $indice->id,
                            'relatorio_status' => null,
                            'notificacao_status' => null,
                        );
                    } else {
                        $obj = (object)array(
                            'data' => $indice->data,
                            'statusInspecao' => $indice->status,
                            'motivoInspecao' => $indice->motivo,
                            'inspetor_id' => $indice->inspetor_id,
                            'cnae' => $indice->requerimento->cnae->descricao,
                            'nomeEmpresa' => $indice->empresa->nome,

                            'relatorio_id' => null,
                            'inspecao_id' => $indice->id,
                            'relatorio_status' => null,
                            'notificacao_status' => null,
                        );
                    }
                    array_push($inspecoes, $obj);
                }
            }
        }

        return view('inspetor/programacao_inspetor', ['inspecoes' => $inspecoes]);
    }

    public function verificarNotificacao(Request $request)
    {
        $notificacao = Notificacao::where('inspecoes_id', '=', Crypt::decrypt($request->inspecao))->get();

        return view('inspetor/verificar_notificacao', ['inspecao_id' => Crypt::decrypt($request->inspecao), 'notificacao' => $notificacao]);
    }

    /*
    * FUNCAO: Mostrar as imagens capturadas pela camera
    * ENTRADA: inspecao_id
    * SAIDA: listagem com as imagens da camera
    */
    public function showAlbum(Request $request)
    {
        $resultado = InspecaoFoto::where('inspecao_id', '=', Crypt::decrypt($request->value))->orderBy('created_at', 'ASC')->get();
        return view('inspetor/album_inspetor', ['album' => $resultado]);
    }

    /*
    * FUNCAO:  Deletar uma imagem
    * ENTRADA: inspecao_id, imagem_id
    * SAIDA:
    */
    public function deleteFoto(Request $request)
    {
        $nomeDoArquivo = "";
        $resultado = InspecaoFoto::where('id', '=', Crypt::decrypt($request->value))->first();
        $nomeDoArquivo = $resultado->imagemInspecao;
        $resultado->delete();
        unlink("imagens/inspecoes/" . $nomeDoArquivo);
        return redirect()->back()->with('success', "Foto deletada com sucesso!");
    }

    /*
    * FUNCAO: mostrar a pagina de relatorio
    * ENTRADA: inspecao_id
    * SAIDA:
    */
    public function showRelatorio(Request $request)
    {
        $resultado = InspecaoFoto::where('inspecao_id', '=', Crypt::decrypt($request->value))->orderBy('created_at', 'ASC')->get();
        $relatorio = InspecaoRelatorio::where('inspecao_id', '=', Crypt::decrypt($request->value))->first();
        if ($relatorio == null) {
            return view('inspetor/relatorio_inspetor', ['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => "", 'relatorio_status' => $request->relatorio_status]);
        } else {
            return view('inspetor/relatorio_inspetor', ['album' => $resultado, 'inspetor_id' => Crypt::decrypt($request->value), 'relatorio' => $relatorio->relatorio, 'relatorio_status' => $request->relatorio_status]);
        }
    }

    /*
    * FUNCAO: mostrar a pagina de historico
    * ENTRADA:
    * SAIDA:
    */
    public function showHistorico()
    {
        $inspetor = Inspetor::where('user_id', '=', Auth::user()->id)->first();
        $inspecoes = Inspecao::where('inspetor_id', $inspetor->id)->where('status', 'concluido')->orderBy('data', 'ASC')->get();
        return view('inspetor/historico_inspetor', ['inspecoes' => $inspecoes]);
    }

    /*
    * FUNCAO: Add descricao a imagem
    * ENTRADA: inspecao_id, descricao
    * SAIDA:
    */
    public function saveDescricao(Request $request)
    {
        $resultado = InspecaoFoto::where('id', '=', $request->inspecao_id)->first();
        $resultado->descricao = $request->descricao;
        $resultado->save();
        return redirect()->back()->with('success' . $resultado->id, "Comentário salvo com sucesso!");
    }

    /*
    * FUNCAO: salvar/atualizar o relatorio
    * ENTRADA: relatorio
    * SAIDA:
    */
    public function saveRelatorio(Request $request)
    {

        $verifica = InspecaoRelatorio::where('inspecao_id', '=', $request->inspecao_id)->exists();
        $inspecao = Inspecao::find($request->inspecao_id);
        if ($verifica == true) { //atualizo
            $atualizar = InspecaoRelatorio::where('inspecao_id', '=', $request->inspecao_id)->first();
            $atualizar->update(['relatorio' => $request->relatorio]);
            $atualizar->status = "avaliacao";
            foreach ($atualizar->agentes as $agente) {
                $agente->pivot->aprovacao = "avaliacao";
                $agente->pivot->update();
            }
            $atualizar->coordenador = "avaliacao";
            $atualizar->save();
            return redirect()->route('show.programacao')->with('success', "Relatório atualizado com sucesso e reenviado para nova análise dos avaliadores!");
        } else { //salvo

            $relatorio = new InspecaoRelatorio;
            $relatorio->inspecao_id = $request->inspecao_id;
            $relatorio->relatorio = $request->relatorio;
            $relatorio->status = "avaliacao";
            $relatorio->coordenador = "avaliacao";
            $relatorio->save();

            foreach ($inspecao->agentes as $agente) {
                $relatorio->agentes()->attach($agente->id, ['aprovacao' => 'avaliacao']);
            }

            return redirect()->route('show.programacao')->with('success', "Relatório salvo com sucesso e enviado para análise dos avaliadores!");
        }
    }
}
