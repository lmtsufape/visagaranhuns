<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\RespTecnico;
use App\User;
use App\Area;
use App\Endereco;
use App\Telefone;
use App\AreaTipodocresp;
use App\Tipodocresp;
use App\Empresa;
use App\Docresptec;
use App\Docempresa;
use App\Requerimento;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\RtEmpresa;
use App\CnaeEmpresa;
use App\Cnae;
use App\Notificacao;
use App\Checklistresp;
use App\Checklistemp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PDF;
use App\Inspecao;

class RespTecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function home()
    {

        $user = User::find(Auth::user()->id);
        $rt = RespTecnico::where('user_id', $user->id)->first();
        $notificacao = Notificacao::all();
        $temp = [];
        $empresas = [];
        $notificacoes = [];
        $notificacoesFinal = [];

        $empresa = RtEmpresa::where('resptec_id', $rt->id)->pluck('empresa_id');

        foreach ($empresa as $indice) {
            array_push($temp, RtEmpresa::where('empresa_id', $indice)->first());
        }
        $empresas = array_unique($temp);


        $countPendente = 0;
        $countAnexado = 0;

        // $empresa = Auth::user()->empresa;
        foreach ($empresas as $indice) {

            $checklistPendente = Checklistemp::where('empresa_id', $indice->empresa_id)
                ->where('anexado', 'false')
                // ->where('areas_id', $indice->area_id)
                ->get();
            $countPendente = $countPendente + count($checklistPendente);

            $checklistAnexado = Checklistemp::where('empresa_id', $indice->empresa_id)
                ->where('anexado', 'true')
                // ->where('areas_id', $indice->area_id)
                ->get();
            $countAnexado = $countAnexado + count($checklistAnexado);
        }

        foreach ($empresas as $key) {
            foreach ($notificacao as $indice) {
                if ($indice->inspecao->empresas_id != null && $indice->inspecao->empresas_id == $key->empresa_id) {
                    if ($indice->inspecao->requerimento->resptecnicos_id != null && $indice->inspecao->requerimento->resptecnicos_id == $rt->id) {
                        array_push($notificacoesFinal, $indice);
                    }
                } elseif ($indice->inspecao->denuncias_id != null && $indice->inspecao->denuncia->empresa_id != null && $indice->inspecao->denuncia->empresa_id == $key->empresa_id) {
                    array_push($notificacoesFinal, $indice);
                }
            }
        }


        return view('responsavel_tec/home_rt',
            ['empresas' => $empresas,
                'anexados' => $countAnexado,
                'pendentes' => $countPendente,
                'totalNotificacao' => count($notificacoesFinal),
            ]);
    }

    public function listarEmpresas(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $rt = RespTecnico::where('user_id', $user->id)->first();
        $temp = [];
        $empresas = [];

        $empresa = RtEmpresa::where('resptec_id', $rt->id)->pluck('empresa_id');

        foreach ($empresa as $indice) {
            array_push($temp, RtEmpresa::where('empresa_id', $indice)->first());
        }
        $empresas = array_unique($temp);

        return view('responsavel_tec/listar_empresas', ['empresas' => $empresas, 'tipo' => 'estabelecimentos', 'flag' => $request->flag]);
    }

    public function showEmpresa(Request $request)
    {
        $id = Crypt::decrypt($request->empresa);
        $empresa = Empresa::find($id);
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();
        $telefone = Telefone::where('empresa_id', $empresa->id)->first();
        $cnaeEmpresa = CnaeEmpresa::where('empresa_id', $id)->get();

        return view('responsavel_tec/empresa', [
            'empresa' => $empresa,
            'endereco' => $endereco,
            'telefone' => $telefone,
            'cnae' => $cnaeEmpresa,
            'empresaId' => $empresa->id,
        ]);
    }

    public function encontrarCnae(Request $request)
    {

        $requerimento = Requerimento::where('empresas_id', $request->empresa)
            // ->where('resptecnicos_id', $request->respTecnico)
            // ->orWhere('resptecnicos_id', null)
            ->where('cnae_id', $request->cnaeId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($requerimento == null) {
            $data = array(
                'tipo' => "nenhum",
                'valor' => "nenhum",
            );

            echo json_encode($data);

        } else {
            $data = array(
                'tipo' => $requerimento->tipo,
                'valor' => $requerimento->status,
            );

            echo json_encode($data);
        }
    }

    public function criarRequerimento(Request $request)
    {
        $id = Crypt::decrypt($request->empresa);
        $empresa = Empresa::find($id); // Empresa
        $rt = RespTecnico::where("user_id", Auth::user()->id)->first(); //Responsavel Técnico
        $areas = RtEmpresa::where("resptec_id", $rt->id)->where('empresa_id', $empresa->id)->pluck('area_id'); //Areas especificas do responsavel técnico
        $cnaesEmpresa = CnaeEmpresa::where("empresa_id", $id)->get(); //Cnaes especificos da empresa
        $requerimentos = Requerimento::where('empresas_id', $empresa->id)
            ->where('resptecnicos_id', $rt->id)->orderBy('created_at', 'desc')->get(); // Requerimentos da empresa
        $notificacoes = Notificacao::all();
        $check = [];
        $temp0 = [];
        $temp = [];
        $resultado = Empresa::find($id);
        $areasIds = [];


        // Pegando os ids dos cnaes da empresa
        foreach ($cnaesEmpresa as $indice0) {
            array_push($temp0, $indice0->cnae_id);
        }

        // Pegando os ids de todas as áreas de atuação do estabelecimento
        foreach ($temp0 as $indice) {
            $cnae = Cnae::find($indice);
            array_push($areasIds, $cnae->areas_id);
        }

        // Removendo areas repetidas
        $areasEstabelecimento = array_unique($areasIds);

        // Pegando os cnaes especificos das áreas do responsavel técnico
        foreach ($areas as $indice) {
            $cnaes = Cnae::where('areas_id', $indice)->get();
            foreach ($cnaes as $indice2) {
                if (in_array($indice2->id, $temp0)) {
                    array_push($temp, $indice2);
                }
            }
        }

        // Verificando se a checklist de documentos desta empresa (Tabela: checklistemp) está completa (True) ou incompleta (False), por áreas
        foreach ($areas as $key) {
            $pendencia = "completo";
            $checklist = Checklistemp::where('empresa_id', $empresa->id)
                ->where('areas_id', $key)->get();
            foreach ($checklist as $key2) {
                if ($key2->anexado == "false") {
                    $pendencia = "pendente";
                }
            }

            if ($pendencia == "completo") {
                $obj = (object)array(
                    'area' => $key,
                    'status' => "completo",
                );
                array_push($check, $obj);
            } else {
                $obj = (object)array(
                    'area' => $key,
                    'status' => "pendente",
                );
                array_push($check, $obj);
            }
        }

        return view('responsavel_tec/requerimento', [
            'nome' => $empresa->nome,
            'cnaes' => $temp,
            'resptecnico' => $rt->id,
            'empresas' => $resultado,
            'status' => $empresa->status_cadastro,
            'requerimentos' => $requerimentos,
            // 'resultados'        => $arrayResultado,
            'check' => $check,
            'notificacoes' => $notificacoes,
            'areas' => $areasEstabelecimento,
        ]);
    }

    public function gerarSituacao(Request $request)
    {

        $empresa = Empresa::find($request->empresa);
        $telefone = Telefone::where('empresa_id', $empresa->id)->first();
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();
        $areas = [];
        $pendenciaDocs = [];

        foreach ($request->areas as $key) {

            $checklist = Checklistemp::where('empresa_id', $empresa->id)
                ->where('areas_id', $key)->get();

            foreach ($checklist as $key2) {
                if ($key2->anexado == "false") {

                    // Criando uma lista de documentos que faltam ou não anexar
                    $docsPendencia = (object)array(
                        'area' => $key,
                        'status' => "false",
                        'nome' => $key2->nomeDoc,
                    );
                    array_push($pendenciaDocs, $docsPendencia);
                } else {

                    // Criando uma lista de documentos que faltam ou não anexar
                    $docsPendencia = (object)array(
                        'area' => $key,
                        'status' => "true",
                        'nome' => $key2->nomeDoc,
                    );
                    array_push($pendenciaDocs, $docsPendencia);
                }
            }
        }

        foreach ($request->areas as $indice) {
            $area = Area::find($indice);

            $obj = (object)array(
                'areaId' => strval($area->id),
                'areaNome' => $area->nome,
            );

            array_push($areas, $obj);
        }


        date_default_timezone_set('America/Recife');
        $emissao = date('d/m/Y \à\s H:i:s');

        asort($pendenciaDocs);

        $pdf = PDF::loadView('empresa/situacao_documentos', compact('areas', 'pendenciaDocs', 'empresa', 'endereco', 'telefone', 'emissao'));
        return $pdf->setPaper('a4')->stream('documentos.pdf');
    }

    public function notificacaoEmpresa(Request $request)
    {
        $rt = RespTecnico::where('user_id', Auth::user()->id)->first();
        $empresa = Empresa::find(Crypt::decrypt($request->empresa));
        $notificacao = Notificacao::all();
        $inspecao = Inspecao::all();
        // dd($notificacao);
        $inspecoes = [];

        foreach ($inspecao as $key) {

            // if ($indice->inspecao->empresas_id == null) {
            //     if ($indice->inspecao->denuncia->empresa_id != null) {
            //         if ($indice->inspecao->denuncia->empresa_id == $empresa->id) {
            //             array_push($notificacoes, $indice);
            //         }
            //     }
            // } else {
            //     if($indice->inspecao->requerimento->resptecnicos_id == $rt->id){
            //         array_push($notificacoes, $indice);
            //     }
            // }
            if ($key->empresas_id != null && $key->empresas_id == $empresa->id && $key->requerimento->resptecnicos_id != null && $key->requerimento->resptecnicos_id == $rt->id) {
                array_push($inspecoes, $key);
            } elseif ($key->denuncias_id != null && $key->denuncia->empresa_id != null && $key->denuncia->empresa_id == $empresa->id) {
                array_push($inspecoes, $key);
            }
        }
        // dd($inspecoes);

        return view('responsavel_tec/notificacao', [
            'inspecoes' => $inspecoes,
            'empresa' => $empresa,
        ]);
    }

    public function cadastrarRequerimento(Request $request)
    {

        $validator = $request->validate([
            'tipo' => 'required',
            'cnae' => 'required',
        ]);

        $empresa = Empresa::find($request->empresa);

        if ($request->tipo == "Dispensa CNAE") {
            return Redirect::route('solicitar.dispensa', ['empresa' => $empresa, 'cnae' => $request->cnae, 'resptecnico' => $request->resptecnico]);
        }

        $data = date('Y-m-d');

        $requerimento = Requerimento::create([
            'tipo' => $request->tipo,
            'status' => "pendente",
            'aviso' => "",
            'cnae_id' => $request->cnae,
            'data' => $data,
            'resptecnicos_id' => $request->resptecnico,
            'empresas_id' => $request->empresa,
        ]);

        session()->flash('success', 'O seu requerimento foi enviado para análise!');
        return back();

    }

    public function documentacaoEmpresa(Request $request)
    {
        $idEmpresa = Crypt::decrypt($request->empresa);
        $empresa = Empresa::where('id', $idEmpresa)->first();
        $docsempresa = Docempresa::where('empresa_id', $empresa->id)->get();
        $rt = RespTecnico::where('user_id', Auth::user()->id)->first();
        $rtempresa = RtEmpresa::where('resptec_id', $rt->id)->where('empresa_id', $empresa->id)->get();
        $rtempresa2 = RtEmpresa::where('resptec_id', $rt->id)->where('empresa_id', $empresa->id)->pluck('area_id');
        $checklisttemp = [];
        $checklist = [];
        $check = [];

        foreach ($rtempresa2 as $key) {
            array_push($checklisttemp, Checklistemp::where('empresa_id', $empresa->id)->where('areas_id', $key)->orderBy('nomeDoc', 'ASC')->get());
        }

        foreach ($checklisttemp as $indice) {
            foreach ($indice as $indice2) {
                array_push($checklist, $indice2);
            }
        }

        for ($i = 0; $i < count($checklist); $i++) {
            if (count($check) == 0) {
                array_push($check, $checklist[$i]);
            } else {
                $temp = false;
                for ($j = 0; $j < count($check); $j++) {
                    if ($checklist[$i]->tipodocemp_id == $check[$j]->tipodocemp_id) {
                        $temp = true;
                    }
                }
                if ($temp == false) {
                    array_push($check, $checklist[$i]);
                }
            }
        }

        //tipos: lista de objetos checklist sem repetições, para serem escolhidos os tipos de documentos que serão enviados. $tipo->tipodocemp_id

        return view('responsavel_tec/empresa_docs', ['nome' => $empresa->nome,
            'empresaId' => $empresa->id,
            'checklist' => $checklist,
            'docsempresa' => $docsempresa,
            'rtempresa' => $rtempresa,
            'tipos' => $check,
        ]);
    }

    public function downloadArquivo(Request $request)
    {

        return response()->download(storage_path('app/public/' . $request->file));
    }

    public function editarArquivosEmpRt(Request $request)
    {

        $validatedData = $request->validate([

            'arquivo' => ['nullable', 'file', 'mimes:pdf', 'max:5000'],

        ]);

        $docempresa = Docempresa::where("nome", $request->file)
            ->where('empresa_id', $request->empresa_id)
            ->first();

        if ($docempresa == null) {
            session()->flash('error', 'Erro ao procurar arquivo que será substituido!');
            return back();
        }

        if ($request->arquivo != null) {

            Storage::delete($docempresa->nome);

            $fileDocemp = $request->arquivo;

            $pathDocemp = 'empresas/' . $docempresa->empresa_id . '/' . $docempresa->tipodocemp_id . '/';

            $nomeDocemp = $request->arquivo->getClientOriginalName();

            $docempresa->nome = $pathDocemp . $nomeDocemp;

            if ($request->data_emissao_editar != null) {
                $docempresa->data_emissao = $request->data_emissao_editar;
            }
            if ($request->data_validade_editar != null) {
                $docempresa->data_validade = $request->data_validade_editar;
            }

            $docempresa->save();

            Storage::putFileAs($pathDocemp, $fileDocemp, $nomeDocemp);

            session()->flash('success', 'Arquivo salvo com sucesso!');
            return back();

        } else {
            if ($request->data_emissao_editar != null) {
                $docempresa->data_emissao = $request->data_emissao_editar;
            }
            if ($request->data_validade_editar != null) {
                $docempresa->data_validade = $request->data_validade_editar;
            }
            $docempresa->save();

            session()->flash('success', 'Datas atualizadas!');
            return back();
        }

    }

    public function findDoc(Request $request)
    {

        $docempresa = Docempresa::find($request->id);

        $data = array(
            'nome' => $docempresa->nome,
            'data_emissao' => $docempresa->data_emissao,
            'data_validade' => $docempresa->data_validade,
        );

        echo json_encode($data);
    }

    public function anexarArquivosEmpresa(Request $request)
    {

        $messages = [
            'max' => 'O arquivo não pode ser maior que 5mb!',
            'required' => 'O campo :attribute não foi passado!',
            'mimes' => 'O arquivo anexado não está no formato pdf!',
            'date' => 'Campo data está inválido!',
            'file' => 'Um arquivo deve ser anexado!',
        ];

        $validator = Validator::make($request->all(), [
            'arquivo' => 'required|file|mimes:pdf|max:5000',
            'tipodocempresa' => 'required',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date',
        ], $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        if ($request->arquivo == null) {
            session()->flash('error', 'Selecione um aquivo e tente novamente!');
            return back();
        }

        $checklist = Checklistemp::where('tipodocemp_id', $request->tipodocempresa)
            ->where('empresa_id', $request->empresaId)
            ->where('areas_id', $request->area)->first();
        // dd($request->arquivo);
        if ($checklist == null) {
            session()->flash('error', 'O tipo de documento específico não consta em sua checklist!');
            return back();
        }

        // foreach ($checklist as $indice) {
        //     if ($indice->tipodocemp_id == $request->tipodocempresa && $indice->anexado == "true") {
        //         session()->flash('error', 'Este tipo de arquivo já foi anexado!');
        //         return back();
        //     }

        //     $indice->anexado = "true";
        //     $indice->save();
        // }

        if ($checklist->tipodocemp_id == $request->tipodocempresa && $checklist->anexado == "true") {
            session()->flash('error', 'Este tipo de arquivo já foi anexado para essa área!');
            return back();
        }

        $checklist->anexado = "true";
        $checklist->save();

        $empresa = Empresa::find($request->empresaId);

        $fileDocemp = $request->arquivo;

        // $pathDocemp = 'empresas/' . $empresa->id . '/' . $request->tipodocempresa . '/';
        $pathDocemp = 'empresas/' . $empresa->id . '/' . $request->area . '/' . $request->tipodocempresa . '/';

        $nomeDocemp = $request->arquivo->getClientOriginalName();

        Storage::putFileAs($pathDocemp, $fileDocemp, $nomeDocemp);

        $docEmpresa = Docempresa::create([
            'nome' => $pathDocemp . $nomeDocemp,
            'area' => $request->area,
            'data_emissao' => $request->data_emissao,
            'data_validade' => $request->data_validade,
            'empresa_id' => $empresa->id,
            'tipodocemp_id' => $request->tipodocempresa,
        ]);


        // return view('empresa.home_empresa');
        session()->flash('success', 'O arquivo foi anexado com sucesso!');
        return back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $empresa = Empresa::find($request->empresaId);
        $user = User::find(Auth::user()->id);
        $cnaeEmpresa = CnaeEmpresa::where('empresa_id', $request->empresaId)->get();
        $rtempresa = RtEmpresa::where('empresa_id', $request->empresaId)->get();

        $resptecnicos = [];

        foreach ($rtempresa as $indice) {
            array_push($resptecnicos, RespTecnico::find($indice->resptec_id));
        }

        $temp = array_unique($resptecnicos);

        $cnae = array();
        $areas = array();

        foreach ($cnaeEmpresa as $indice) {
            $cnaes = Cnae::find($indice->cnae_id);
            array_push($cnae, $cnaes);
        }

        foreach ($cnae as $indice) {
            $area = Area::find($indice->areas_id);
            array_push($areas, $area);
        }

        $resultAreasTemp = array_unique($areas);

        $areasOrdenado = [];

        foreach ($resultAreasTemp as $indice) {
            array_push($areasOrdenado, $indice);
        }

        return view('responsavel_tec.cadastrar_responsavel_tec')->with(["user" => $user,
            "empresaId" => $request->empresaId,
            'areas' => $areasOrdenado,
            'respTecnicos' => $temp,
            'rtempresa' => $rtempresa,
            'empresaNome' => $empresa->nome,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $empresa = Empresa::find($request->empresaId);
        $user = User::where("email", $request->email)->first();

        if ($user != null) {

            // Verificar aqui se há algum rt já relacionado com alguma das áreas que foram escolhidas.
            for ($i = 0; $i < count($request->area); $i++) {
                $rtempresa = RtEmpresa::where('area_id', $request->area[$i])
                    ->where('empresa_id', $request->empresaId)->first();
                if ($rtempresa != null) {
                    session()->flash('error', 'Já existe um responsável técnico cadastrado nessa área!');
                    return back();
                }
            }

            $resptecnico = RespTecnico::where('user_id', $user->id)->first();

            if ($user->status_cadastro == "pendente" && $resptecnico != null) {

                $passwordTemporario = Str::random(8);
                $user->password = bcrypt($passwordTemporario);
                $user->save();

                \Illuminate\Support\Facades\Mail::send(new \App\Mail\CadastroRTEmail($request->email, $passwordTemporario, $empresa->nome));

                $hoje = date('d/m/Y');

                for ($i = 0; $i < count($request->area); $i++) {
                    $rtempresa = RtEmpresa::create([
                        'horas' => $request->carga_horaria,
                        'data_inicio' => $hoje,
                        'status' => "ativo",
                        'resptec_id' => $resptecnico->id,
                        'empresa_id' => $request->empresaId,
                        'area_id' => $request->area[$i],
                    ]);
                }

                session()->flash('success', 'Responsável técnico convidado com sucesso!');
                return back();

            } elseif ($resptecnico != null) {
                $validator = $request->validate([
                    'carga_horaria' => 'required|integer',
                ]);

                $passwordTemporario = Str::random(8);
                \Illuminate\Support\Facades\Mail::send(new \App\Mail\CadastroRTcadastrado($request->email, $empresa->nome));

                $hoje = date('d/m/Y');

                for ($i = 0; $i < count($request->area); $i++) {
                    $rtempresa = RtEmpresa::create([
                        'horas' => $request->carga_horaria,
                        'data_inicio' => $hoje,
                        'status' => "ativo",
                        'resptec_id' => $resptecnico->id,
                        'empresa_id' => $request->empresaId,
                        'area_id' => $request->area[$i],
                    ]);
                }

                $checklistRespTecnico = Checklistresp::where('resptecnicos_id', $resptecnico->id)->exists();

                if ($checklistRespTecnico == false) {
                    for ($i = 0; $i < count($request->area); $i++) {
                        $areatipodocresp = AreaTipodocresp::where('area_id', $request->area[$i])->get();

                        foreach ($areatipodocresp as $indice) {

                            $checklistresp = Checklistresp::create([
                                'anexado' => 'false',
                                'areas_id' => $request->area[$i],
                                'nomeDoc' => $indice->tipodocresp->nome,
                                'tipodocres_id' => $indice->tipodocresp->id,
                                'resptecnicos_id' => $resptecnico->id,
                            ]);
                        }
                    }
                }

                session()->flash('success', 'Responsável técnico convidado com sucesso!');
                return back();
            } else {
                session()->flash('error', 'O Responsável Técnico deve concluir seu cadastro antes!');
                return back();
            }
        } else {

            for ($i = 0; $i < count($request->area); $i++) {
                $rtempresa = RtEmpresa::where('area_id', $request->area[$i])
                    ->where('empresa_id', $request->empresaId)->first();
                if ($rtempresa != null) {
                    session()->flash('error', 'Já existe um responsável técnico cadastrado nessa área!');
                    return back();
                }
            }

            $hoje = date('d/m/Y');

            // Passar esse valdiator para outra parte
            $validator = $request->validate([
                // 'nome'     => 'required|string',
                'email' => 'required|email',
                // 'formacao' => 'required|string',
                // 'especializacao' => 'nullable|string',
                // 'cpf'            => 'required|string',
                // 'telefone'       => 'required|string',
                'carga_horaria' => 'required|integer',
            ]);

            $passwordTemporario = Str::random(8);

            $user = User::create([
                'name' => "Pendente",
                'email' => $request->email,
                'password' => bcrypt($passwordTemporario),
                'tipo' => "rt",
                'status_cadastro' => "pendente",
            ]);

            \Illuminate\Support\Facades\Mail::send(new \App\Mail\CadastroRTEmail($request->email, $passwordTemporario, $empresa->nome));

            // Responsável Técnico será criado em outra parte também
            $respTec = RespTecnico::create([
                'formacao' => "Pendente",
                'especializacao' => "Pendente",
                'cpf' => Str::random(8),
                'telefone' => "Pendente",
                'conselho' => "Pendente",
                'num_conselho' => "Pendente",
                'user_id' => $user->id,
                // 'area_id'        => $request->area,
                // 'empresa_id'     => $request->empresaId,
            ]);

            for ($i = 0; $i < count($request->area); $i++) {
                $rtempresa = RtEmpresa::create([
                    'horas' => $request->carga_horaria,
                    'data_inicio' => $hoje,
                    'status' => "ativo",
                    'resptec_id' => $respTec->id,
                    'empresa_id' => $request->empresaId,
                    'area_id' => $request->area[$i],
                ]);
            }

            $rtempresatemp = RtEmpresa::where('resptec_id', $respTec->id)->get();
            $areastemp = [];

            foreach ($rtempresatemp as $indice) {
                array_push($areastemp, $indice->area_id);
            }

            for ($i = 0; $i < count($areastemp); $i++) {
                $areatipodocresp = AreaTipodocresp::where('area_id', $areastemp[$i])->get();

                foreach ($areatipodocresp as $indice) {
                    // dd("Antes");
                    $checklistresp = Checklistresp::create([
                        'anexado' => 'false',
                        'areas_id' => $areastemp[$i],
                        'nomeDoc' => $indice->tipodocresp->nome,
                        'tipodocres_id' => $indice->tipodocresp->id,
                        'resptecnicos_id' => $respTec->id,
                    ]);
                    // dd($checklistresp);
                }
            }

            session()->flash('success', 'O responsável técnico foi cadastrado com sucesso!');
            return back();
        }
    }

    public function baixarArquivos(Request $request)
    {
        return response()->download(storage_path('app/public/' . $request->file));
    }

    public function findDocRt(Request $request)
    {

        $docrt = Docresptec::find($request->id);

        $data = array(
            'nome' => $docrt->nome,
            'data_emissao' => $docrt->data_emissao,
            'data_validade' => $docrt->data_validade,
        );

        echo json_encode($data);
    }

    public function editarArquivos(Request $request)
    {

        $validatedData = $request->validate([

            'arquivo' => ['nullable', 'file', 'mimes:pdf', 'max:5000000'],

        ]);

        $docrt = Docresptec::where("nome", $request->file)->first();

        if ($docrt == null) {
            session()->flash('error', 'Erro ao procurar arquivo que será substituido!');
            return back();
        }

        if ($request->arquivo != null) {

            Storage::delete($docrt->nome);

            $fileDocemp = $request->arquivo;

            $pathDocemp = 'empresas/' . $docrt->empresa_id . '/' . $docrt->tipodocemp_id . '/';

            $nomeDocemp = $request->arquivo->getClientOriginalName();

            $docrt->nome = $pathDocemp . $nomeDocemp;
            $docrt->save();

            if ($request->data_emissao_editar != null) {
                $docrt->data_emissao = $request->data_emissao_editar;
            }
            if ($request->data_validade_editar != null) {
                $docrt->data_validade = $request->data_validade_editar;
            }
            $docrt->save();

            Storage::putFileAs($pathDocemp, $fileDocemp, $nomeDocemp);

            session()->flash('success', 'Arquivo salvo com sucesso!');
            return back();

        } else {

            if ($request->data_emissao_editar != null) {
                $docrt->data_emissao = $request->data_emissao_editar;
            }
            if ($request->data_validade_editar != null) {
                $docrt->data_validade = $request->data_validade_editar;
            }
            $docrt->save();

            session()->flash('success', 'Arquivo salvo com sucesso!');
            return back();
        }
    }

    public function showDocumentacao(Request $request)
    {
        $user = Auth::user()->id;
        $rt = RespTecnico::where('user_id', $user)->first();
        $docsrt = Docresptec::where('resptecnicos_id', $rt->id)->get();
        $temp = [];
        $checkrespt = [];

        $checklistresp = Checklistresp::where('resptecnicos_id', $rt->id)->orderBy('nomeDoc', 'ASC')->pluck('tipodocres_id');
        for ($i = 0; $i < count($checklistresp); $i++) {
            array_push($temp, $checklistresp[$i]);
        }

        $array = array_unique($temp);

        foreach ($array as $indice) {
            array_push($checkrespt, Checklistresp::where('tipodocres_id', $indice)
                ->where('resptecnicos_id', $rt->id)->first());
        }
        // dd($checkrespt);

        $tipodocresp = Tipodocresp::all();

        return view('responsavel_tec/documentos', [
            'checklist' => $checkrespt,
            'tipodocs' => $tipodocresp,
            'docsrt' => $docsrt,
        ]);

    }

    public function atualizarSenhaDeAcesso(Request $request)
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

    public function anexarArquivos(Request $request)
    {

        $messages = [
            // 'size'      => 'O arquivo não pode ser maior que 5mb!',
            'required' => 'O campo :attribute não foi passado!',
            'mimes' => 'O arquivo anexado não está no formato pdf!',
            'date' => 'Campo data está inválido!',
            'file' => 'Um arquivo deve ser anexado!',
        ];

        $validator = Validator::make($request->all(), [
            // 'arquivo'        => 'required|file|mimes:pdf|size:5000',
            'tipodocres' => 'required',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date',
        ], $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        if ($request->tipodocres == "Tipos de documentos") {
            session()->flash('error', 'Selecione um documento!');
            return back();
        }

        $user = Auth::user()->id;
        $rt = RespTecnico::where('user_id', $user)->first();
        $checklist = Checklistresp::where('tipodocres_id', $request->tipodocres)
            ->where('resptecnicos_id', $rt->id)->get();

        foreach ($checklist as $indice) {
            if ($indice->tipodocres_id == $request->tipodocres && $indice->anexado == "true") {
                session()->flash('error', 'Este tipo de arquivo já foi anexado!');
                return back();
            }

            $indice->anexado = "true";
            $indice->save();
        }

        $fileDocemp = $request->arquivo;

        $pathDocemp = 'rts/' . $rt->id . '/' . $request->tipodocres . '/';

        $nomeDocemp = $request->arquivo->getClientOriginalName();

        Storage::putFileAs($pathDocemp, $fileDocemp, $nomeDocemp);

        $docEmpresa = Docresptec::create([
            'nome' => $pathDocemp . $nomeDocemp,
            'data_emissao' => $request->data_emissao,
            'data_validade' => $request->data_validade,
            'resptecnicos_id' => $rt->id,
            'tipodocresp_id' => $request->tipodocres,
        ]);


        // return view('empresa.home_empresa');
        session()->flash('success', 'O arquivo foi anexado com sucesso!');
        return back();

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
    public function edit(Request $request)
    {
        $user = User::find($request->user);
        $respTecnico = RespTecnico::where('user_id', $user->id)->first();

        return view('responsavel_tec/editar_dados_responsavel_tec',
            ['user' => $user,
                'respTecnico' => $respTecnico]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $respTecnico = RespTecnico::find($request->respTecnico);
        $user = User::where('id', $respTecnico->user_id)->first();

        $validator = $request->validate([
            'nome' => 'required|string',
            'formacao' => 'required|string',
            'especializacao' => 'nullable|string',
            'cpf' => 'required|string',
            'telefone' => 'required|string',
            'conselho' => 'required|string|max:5',
            'num_conselho' => 'required|string|max:6',
        ]);

        $user->name = $request->nome;
        // $user->password = bcrypt($request->password);
        $user->save();

        $respTecnico->formacao = $request->formacao;
        if (isset($request->especializacao)) {
            $respTecnico->especializacao = $request->especializacao;
        }
        $respTecnico->cpf = $request->cpf;
        $respTecnico->telefone = $request->telefone;
        $respTecnico->conselho = $request->conselho;
        $respTecnico->num_conselho = $request->num_conselho;
        $respTecnico->save();

        session()->flash('success', 'Dados alterados com sucesso!');
        return redirect()->back();
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

    public function criar()
    {

        $user = User::find(Auth::user()->id);

        // Tela de conclusão de cadastro do responsável técnico
        return view('responsavel_tec.cadastrar_rt')->with(["user" => $user->email]);
    }

    public function salvar(Request $request)
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
            'conselho' => 'required|string|max:5',
            'num_conselho' => 'required|string|max:6',
            'senha' => 'required',

        ], $messages);


        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Atualiza dados de user para o responsável técnico
        $user->name = $request->nome;
        $user->password = bcrypt($request->senha);
        $user->status_cadastro = "aprovado";
        $user->save();

        // Atualizar dados do model para responsável técnico
        $respTecnico = RespTecnico::where('user_id', $user->id)->first();
        $respTecnico->formacao = $request->formacao;
        $respTecnico->especializacao = $request->especializacao;
        $respTecnico->cpf = $request->cpf;
        $respTecnico->telefone = $request->telefone;
        $respTecnico->conselho = $request->conselho;
        $respTecnico->num_conselho = $request->num_conselho;
        $respTecnico->save();

        $coords = User::where('tipo', '=', 'coordenador')->get();
        $empres = RtEmpresa::where('resptec_id', $respTecnico->id)->pluck('empresa_id');
        $empresa = Empresa::find($empres)->first();
        foreach ($coords as $coord) {
            \Illuminate\Support\Facades\Mail::send(new \App\Mail\EntradaRT($coord, $respTecnico, $empresa));
        }


        return redirect()->route('/');
    }

    public function encontrarNotificacoes(Request $request)
    {

        $notificacoes = Notificacao::where('inspecoes_id', $request->id)->get();

        $output = '';
        if ($notificacoes->count() > 0) {
            foreach ($notificacoes as $key) {
                $output .= '
                <tr>
                    <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">' . $key->item . '</th>
                    <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">' . $key->exigencia . '</th>
                    <th class="subtituloBarraPrincipal" style="font-size:15px; color:black">' . $key->prazo . '</th>
                </tr>
                ';
            }
        } else {
            $output .= '
                    <label></label>
                ';
        }
        $data = array(
            'table_data' => $output,
        );

        echo json_encode($data);
    }
}
