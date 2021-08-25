<?php

namespace App\Http\Controllers;

use App\Dispensa;
use Illuminate\Http\Request;
use App\Empresa;
use App\User;
use App\Telefone;
use App\Endereco;
use App\Docempresa;
use App\Docresptec;
use App\Requerimento;
use App\Area;
use App\Cnae;
use App\CnaeEmpresa;
use App\RespTecnico;
use App\RtEmpresa;
use App\Tipodocempresa;
use App\Tipodocresp;
use App\Inspecao;
use App\Notificacao;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Auth;
use DateTime;
use App\AreaTipodocemp;
use App\Checklistemp;
use App\Checklistresp;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PDF;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $cnaeEmp = CnaeEmpresa::where("cnae_id", Crypt::decrypt($request->value))->get();
        $empresas = array();
        foreach ($cnaeEmp as $indice) {
            $empresa = Empresa::find($indice->empresa_id);
            array_push($empresas, $empresa);
        }
        return view('coordenador/empresas_coordenador', ['empresas' => $empresas]);
    }

    public function listarResponsavelTec()
    {

        $empresas = Empresa::where("user_id", Auth::user()->id);
        return view('empresa/responsavel_tec_empresa');
    }

    public function home()
    {
        $countPendente = 0;
        $countAnexado = 0;

        $empresa = Auth::user()->empresa;
        foreach ($empresa as $indice) {
            $checklistPendente = Checklistemp::where('empresa_id', $indice->id)
                ->where('anexado', 'false')
                ->get();
            $countPendente = $countPendente + count($checklistPendente);

            $checklistAnexado = Checklistemp::where('empresa_id', $indice->id)
                ->where('anexado', 'true')
                ->get();
            $countAnexado = $countAnexado + count($checklistAnexado);
        }
        // dd($countPendente);

        $notificacao = Notificacao::all();
        $notificacoes = [];

        foreach ($empresa as $key) {
            foreach ($notificacao as $indice) {
                if ($indice->inspecao->empresas_id == $key->id) {
                    array_push($notificacoes, $indice);
                }
            }
        }

        $totalNotificacao = count($notificacoes);

        return view(
            'empresa.home_empresa',
            [
                "empresas" => $empresa,
                'anexados' => $countAnexado,
                'pendentes' => $countPendente,
                'totalNotificacao' => $totalNotificacao,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::orderBy('nome', 'ASC')->get();
        return view('naoLogado/cadastrar_empresa', ['areas' => $areas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $userFind = User::where('email', $request->email)->first();

        if ($userFind != null) {
            session()->flash('error', 'Já existe um usuário no sistema com este email!');
            return back();
        }

        $messages = [
            'unique' => 'Um campo igual a :attribute já está cadastrado no sistema!',
            'required' => 'O campo :attribute não foi passado!',
            'string' => 'O campo :attribute deve ser texto!',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nome' => 'required|string',
            'nome_fantasia' => 'nullable|string',
            'cnpjcpf' => 'required|string|unique:empresas,cnpjcpf',
            'tipo' => 'required|string',
            'emailEmpresa' => 'nullable|email|unique:empresas,email',
            'telefone1' => 'required|string',
            'telefone2' => 'nullable|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'complemento' => 'nullable|string',
            'uf' => 'required|string',
            'cep' => 'required|string',
        ], $messages);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        if ($request['cnae'] == null) {
            session()->flash('error', 'Atenção! Uma empresa deve possuir pelo menos um CNAE. (Lista: CNAE Selecionado)');
            return back();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tipo' => "empresa",
            'status_cadastro' => "pendente",
        ]);

        $empresa = Empresa::create([
            'nome' => $request->nome,
            'nome_fantasia' => $request->nome_fantasia,
            'email' => $request->emailEmpresa,
            'cnpjcpf' => $request->cnpjcpf,
            'status_inspecao' => "pendente",
            'status_cadastro' => "pendente",
            'tipo' => $request->tipo,
            'user_id' => $user->id,
        ]);

        // Cadastro de telefones
        $telefone = Telefone::create([
            'telefone1' => $request->telefone1,
            'telefone2' => $request->telefone2,
            'empresa_id' => $empresa->id,
        ]);

        // Cadastro de endereços
        $endereco = Endereco::create([
            'rua' => $request->rua,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
            'cep' => $request->cep,
            'complemento' => $request->complemento,
            'empresa_id' => $empresa->id,
        ]);

        // Área para cadastro de cnaes
        $cnae = $request['cnae'];

        for ($i = 0; $i < count($cnae); $i++) {
            $cnaes = Cnae::find($cnae[$i]);
            $cnaeEmpresa = CnaeEmpresa::create([
                'empresa_id' => $empresa->id,
                'cnae_id' => $cnaes->id,
            ]);
        }

        // Cnaes por empresa
        $cnaempresa = CnaeEmpresa::where("empresa_id", $empresa->id)->pluck('cnae_id');
        $cnaes = [];
        $areas = [];
        $areasOrdenado = [];
        $areas_cont_ordenado = [];

        foreach ($cnaempresa as $indice) {
            array_push($cnaes, Cnae::find($indice));
        }
        foreach ($cnaes as $indice) {
            array_push($areas, $indice->areas_id);
        }

        $areas_cont = array_count_values($areas);
        $resultAreas = array_unique($areas);

        foreach ($resultAreas as $indice) {
            array_push($areasOrdenado, $indice);
        }
        foreach ($areas_cont as $indice) {
            array_push($areas_cont_ordenado, $indice);
        }

        for ($i = 0; $i < count($areasOrdenado); $i++) {
            $areatipodocemp = AreaTipodocemp::where('area_id', $areasOrdenado[$i])->get();

            foreach ($areatipodocemp as $indice) {

                // ABAIXO SAI, CASO SEJA DUPLICADO
                // $checklist = Checklistemp::where('nomeDoc', $indice->tipodocemp->nome)
                // ->where('empresa_id', $empresa->id)
                // ->first();

                $cnaeEmpresa = Checklistemp::create([
                    'anexado' => 'false',
                    'areas_id' => $areasOrdenado[$i],
                    'num_cnae' => $areas_cont_ordenado[$i],
                    'nomeDoc' => $indice->tipodocemp->nome,
                    'tipodocemp_id' => $indice->tipodocemp->id,
                    'empresa_id' => $empresa->id,
                ]);
            }
        }


        return redirect()->route('confirma.cadastro');
    }

    /*
    *   FUNCAO: abrir a pagina editar_meus_dados
    *   TELA: empresa/editar_meus_dados
    *
    */
    public function editarMeusDados()
    {
        $user = Auth::user();
        return view('empresa/editar_meus_dados', ["nome" => $user->name, "email" => $user->email]);
    }

    /*
    *   FUNCAO: atualizar o nome do usuario
    *   REQUEST: name
    *
    */
    public function atualizarMeusDados(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string',
        ]);
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        return redirect()->route('editar.gerente')->with('success', "Nome do usuário alterado com sucesso!");
    }

    /*
    *   FUNCAO: atualizar senha de acesso do gerente
    *   REQUEST: senhaAtual, novaSenha1, novaSenha2
    *
    */
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

    public function adicionarEmpresa(Request $request)
    {

        $user_id = Auth::user()->id;
        $empRepetida = Empresa::where('nome', $request->nome)->first();

        if ($empRepetida != null) {
            return redirect()->route('confirma.cadastro');
        }

        $messages = [
            'unique' => 'Um campo igual a :attribute já está cadastrado no sistema!',
            'required' => 'O campo :attribute não foi passado!',
            'string' => 'O campo :attribute deve ser texto!',
        ];

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'nome_fantasia' => 'nullable|string',
            'cnpjcpf' => 'required|string|unique:empresas,cnpjcpf',
            'tipo' => 'required|string',
            'email' => 'nullable|email',
            'telefone1' => 'required|string',
            'telefone2' => 'nullable|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'uf' => 'required|string',
            'cep' => 'required|string',
            'complemento' => 'nullable|string',
        ], $messages);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $empresa = Empresa::create([
            'nome' => $request->nome,
            'nome_fantasia' => $request->nome_fantasia,
            'email' => $request->emailEmpresa,
            'cnpjcpf' => $request->cnpjcpf,
            'status_inspecao' => "pendente",
            'status_cadastro' => "pendente",
            'tipo' => $request->tipo,
            'user_id' => $user_id,
        ]);

        // Cadastro de telefones
        $telefone = Telefone::create([
            'telefone1' => $request->telefone1,
            'telefone2' => $request->telefone2,
            'empresa_id' => $empresa->id,
        ]);

        // Cadastro de endereços
        $endereco = Endereco::create([
            'rua' => $request->rua,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
            'cep' => $request->cep,
            'complemento' => $request->complemento,
            'empresa_id' => $empresa->id,
        ]);

        // Área para cadastro de cnaesQ
        $cnae = $request['cnae'];


        for ($i = 0; $i < count($cnae); $i++) {
            $cnaes = Cnae::find($cnae[$i]);
            $cnaeEmpresa = CnaeEmpresa::create([
                'empresa_id' => $empresa->id,
                'cnae_id' => $cnaes->id,
            ]);
        }

        // Cnaes por empresa
        $cnaempresa = CnaeEmpresa::where("empresa_id", $empresa->id)->pluck('cnae_id');
        $cnaes = [];
        $areas = [];
        $areas_cont_ordenado = [];

        foreach ($cnaempresa as $indice) {
            array_push($cnaes, Cnae::find($indice));
        }
        foreach ($cnaes as $indice) {
            array_push($areas, $indice->areas_id);
        }

        $areas_cont = array_count_values($areas);
        $resultAreas = array_unique($areas);
        $areasOrdenado = [];

        foreach ($resultAreas as $indice) {
            array_push($areasOrdenado, $indice);
        }

        foreach ($areas_cont as $indice) {
            array_push($areas_cont_ordenado, $indice);
        }

        // for ($i=0; $i < count($areasOrdenado); $i++) {
        //     $areatipodocemp = AreaTipodocemp::where('area_id', $areasOrdenado[$i])->get();

        //     foreach ($areatipodocemp as $indice) {

        //         // ABAIXO SAI, CASO SEJA DUPLICADO
        //         $checklist = Checklistemp::where('nomeDoc', $indice->tipodocemp->nome)
        //         ->where('empresa_id', $empresa->id)
        //         ->first();

        //         if ($checklist == null) {
        //             $cnaeEmpresa = Checklistemp::create([
        //                 'anexado' => 'false',
        //                 'areas_id' => $areasOrdenado[$i],
        //                 'nomeDoc' => $indice->tipodocemp->nome,
        //                 'tipodocemp_id' => $indice->tipodocemp->id,
        //                 'empresa_id' => $empresa->id,
        //             ]);
        //         }
        //     }
        // }

        for ($i = 0; $i < count($areasOrdenado); $i++) {
            $areatipodocemp = AreaTipodocemp::where('area_id', $areasOrdenado[$i])->get();

            foreach ($areatipodocemp as $indice) {

                // ABAIXO SAI, CASO SEJA DUPLICADO
                // $checklist = Checklistemp::where('nomeDoc', $indice->tipodocemp->nome)
                // ->where('empresa_id', $empresa->id)
                // ->first();

                $cnaeEmpresa = Checklistemp::create([
                    'anexado' => 'false',
                    'areas_id' => $areasOrdenado[$i],
                    'num_cnae' => $areas_cont_ordenado[$i],
                    'nomeDoc' => $indice->tipodocemp->nome,
                    'tipodocemp_id' => $indice->tipodocemp->id,
                    'empresa_id' => $empresa->id,
                ]);
            }
        }

        return redirect()->route('confirma.cadastro');
    }

    public function baixarArquivosRt(Request $request)
    {
        return response()->download(storage_path('app/public/' . $request->file));
    }

    public function deletarRespTecnico(Request $request)
    {
        // $docs         = Docresptec::where('resptecnicos_id', $request->idRespTecnico)->delete();
        // $checklist    = Checklistresp::where('resptecnicos_id', $request->idRespTecnico)->delete();

        $rtempresa = RtEmpresa::where('empresa_id', $request->idEmpresa)
            ->where('resptec_id', $request->idRespTecnico)
            ->where('area_id', $request->idArea)
            ->delete();

        $respTecnico = RespTecnico::where('id', '=', $request->idRespTecnico)->first();
        $empresa = Empresa::find($request->idEmpresa)->first();
        $coords = User::where('tipo', '=', 'coordenador')->get();
        foreach ($coords as $coord) {
            \Illuminate\Support\Facades\Mail::send(new \App\Mail\SaidaRT($coord, $respTecnico, $empresa));
        }


        // $respTecnico  = RespTecnico::find($request->idRespTecnico);
        // $user         = User::find($respTecnico->user_id);

        // $caminho          = Str::random(8);
        // $respTecnico->cpf = Str::random(11);
        // $user->email      = $caminho."@gmail.com";
        // $respTecnico->save();
        // $user->save();

        session()->flash('success', 'Responsável Técnico Removido!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $id = Crypt::decrypt($request->value);
        $empresa = Empresa::find($id);
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();
        $telefone = Telefone::where('empresa_id', $empresa->id)->first();
        $cnaeEmpresa = CnaeEmpresa::where('empresa_id', $id)->get();
        // $respTecnicos = RespTecnico::where("empresa_id", $empresa->id)->get();
        $rtempresa = RtEmpresa::where('empresa_id', $empresa->id)->get();

        $resptecnicos = [];


        foreach ($rtempresa as $indice) {
            array_push($resptecnicos, RespTecnico::find($indice->resptec_id));
        }

        $temp = array_unique($resptecnicos);

        // for ($i=0; $i < count($rtempresa); $i++) {
        //     if (count($resptecnicos) == 0) {
        //         array_push($resptecnicos, RespTecnico::find($rtempresa[$i]->resptec_id));
        //     }
        //     else {
        //         for ($j=0; $j < count($resptecnicos); $j++) {
        //             if($rtempresa[$i]->resptec_id != $resptecnicos[$j]->id) {
        //                 array_push($resptecnicos, RespTecnico::find($rtempresa[$i]->resptec_id));
        //             }
        //         }
        //     }
        // }

        return view('coordenador/show_empresa_coordenador', ['empresa' => $empresa, 'endereco' => $endereco, 'telefone' => $telefone, 'cnae' => $cnaeEmpresa, 'rt' => $resptecnicos]);
    }

    public static function download($id_dispensa)
    {
        $arquivo = Dispensa::find($id_dispensa);
        return response()->download(public_path('/dispensas/' . $arquivo->cnpj));
    }

    public function dispensa(Request $request){
        $empresa = Empresa::find($request->empresa);
        return view('dispensa.dispensaCNAE', [
            'empresa' => $empresa,
            'cnae' => $request->cnae,
            'resptecnico' => $request->resptecnico,
        ]);

    }

    public function solicitarDispensa(Request $request)
    {

        $messages = [
            'max' => 'O tamanho máximo do arquivo deve ser de 5mb!',
            'required' => 'O campo :attribute não foi passado!',
            'mimes' => 'O arquivo anexado não está no formato pdf!',
            'file' => 'Um arquivo deve ser anexado!',
        ];

        $validator = Validator::make($request->all(), [
            'cnpj' => 'required|file|mimes:pdf|max:5000',
            'dispensa' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $empresa = Empresa::find($request->empresa);
        $data = date('Y-m-d');

        $requerimento = Requerimento::create([
            'tipo' => "Dispensa CNAE",
            'status' => "pendente",
            'aviso' => "",
            'cnae_id' => $request->cnae,
            'data' => $data,
            'resptecnicos_id' => $request->resptecnico,
            'empresas_id' => $request->empresa,
        ]);

        if ($request->hasfile('cnpj')) {
            $file = $request->file('cnpj');
            $name = preg_replace("/[^a-zA-Z0-9]+/", "", 'dispensaCNAE') . '-' . time() . '.' . $file->extension();
            $extensao = $file->extension();
            $file->move(public_path() . '/dispensas/', $name);

        }

        $dispensa = new Dispensa();
        $dispensa->cnpj = $name;
        $dispensa->dispensa = $request->dispensa;
        $dispensa->requerimento_id = $requerimento->id;
        $dispensa->save();

        session()->flash('success', 'A sua solicitação foi enviada para análise!');
        if ($request->resptecnico != null) {
            return redirect(route('criar.requerimento', ['empresa' => Crypt::encrypt($request->empresa)]));
        } else {
            return redirect(route('mostrar.requerimentos', ["value" => Crypt::encrypt($empresa->id)]));
        }
    }

    public function cadastrarRequerimento(Request $request)
    {
        $validator = $request->validate([
            'tipo' => 'required',
            'cnae' => 'required',
        ]);

        $empresa = Empresa::find($request->empresa);

        $data = date('Y-m-d');

        if ($request->tipo == "Dispensa CNAE") {
            return Redirect::route('solicitar.dispensa', ['empresa' => $empresa, 'cnae' => $request->cnae]);
        }

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

    public function requerimentos(Request $request)
    {

        $id = Crypt::decrypt($request->value);
        $empresa = Empresa::find($id);
        // $rt = RespTecnico::where("user_id", Auth::user()->id)->first();
        // $areas = RtEmpresa::where("resptec_id",$rt->id)->pluck('area_id');
        $cnaesEmpresa = CnaeEmpresa::where("empresa_id", $id)->get();
        $requerimentos = Requerimento::where('empresas_id', $empresa->id)
            ->orderBy('created_at', 'desc')->get();
        $areasIds = [];
        $check = [];
        $cnaes = [];
        $temp = [];
        $temp0 = [];
        $resultado = Empresa::find($id);
        $pendenciaDocs = [];

        // Pegando todos os ids dos cnaes da empresa
        foreach ($cnaesEmpresa as $indice0) {
            array_push($temp0, $indice0->cnae_id);
        }

        // Pegando os cnaes e a área especifica de cada cnae
        foreach ($temp0 as $indice) {
            $cnae = Cnae::find($indice);
            array_push($cnaes, $cnae);
            array_push($areasIds, $cnae->areas_id);
        }

        // Removendo areas repetidas
        $areas = array_unique($areasIds);

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

        return view('empresa/requerimento_empresa', [
            'nome' => $empresa->nome,
            'cnaes' => $cnaes,
            // 'resptecnico'       => $rt->id,
            'empresas' => $resultado,
            'status' => $empresa->status_cadastro,
            'requerimentos' => $requerimentos,
            // 'resultados'        => $arrayResultado,
            'check' => $check,
            'areas' => $areas,
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

    public function notificacoes(Request $request)
    {
        $empresa = Empresa::find(Crypt::decrypt($request->value));
        $notificacao = Notificacao::all();
        $inspecao = Inspecao::all();
        $inspecoes = [];

        foreach ($inspecao as $key) {
            if ($key->empresas_id != null && $key->empresas_id == $empresa->id) {
                array_push($inspecoes, $key);
            } elseif ($key->denuncias_id != null && $key->denuncia->empresa_id != null && $key->denuncia->empresa_id == $empresa->id) {
                array_push($inspecoes, $key);
            }
        }

        // dd($inspecao);
        // $notificacoes = [];
        // $inspecoes = [];

        // foreach ($notificacao as $indice) {
        //     if($indice->inspecao->requerimento_id != null) {
        //         if ($indice->inspecao->requerimento->empresas_id == $empresa->id) {
        //             array_push($notificacoes, $indice);
        //         }
        //     }
        //     elseif ($indice->inspecao->denuncias_id != null) {
        //         if ($indice->inspecao->denuncia->empresa_id == $empresa->id) {
        //             array_push($notificacoes, $indice);
        //         }
        //     }
        // }
        // dd($inspecoes);

        return view('empresa/notificacao', [
            'inspecoes' => $inspecao,
            'empresa' => $empresa,
        ]);
    }

    /**
     * Listar empresas
     * View: empresa/listar_empresas.blade.php
     */
    public function listarEmpresas(Request $request)
    {
        //Preciso da função para carregar a página
        // $empresa = Empresa::where('user_id', Crypt::decrypt($request->user))->paginate(20);
        // return view('empresa/listar_empresas',['empresas' => $empresa]);
        if ($request->tipo == 'estabelecimentos') {
            $empresa = Empresa::where('user_id', Crypt::decrypt($request->user))->paginate(20);
            return view('empresa/listar_empresas', ['empresas' => $empresa, 'tipo' => 'estabelecimentos']);
        } elseif ($request->tipo == 'documentacao') {
            $empresa = Empresa::where('user_id', Crypt::decrypt($request->user))->paginate(20);
            return view('empresa/listar_empresas', ['empresas' => $empresa, 'tipo' => 'documentacao']);
        } elseif ($request->tipo == 'requerimento') {
            $empresa = Empresa::where('user_id', Crypt::decrypt($request->user))->paginate(20);
            return view('empresa/listar_empresas', ['empresas' => $empresa, 'tipo' => 'requerimentos']);
        } elseif ($request->tipo == 'notificacao') {
            $empresa = Empresa::where('user_id', Crypt::decrypt($request->user))->paginate(20);
            return view('empresa/listar_empresas', ['empresas' => $empresa, 'tipo' => 'notificacao']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editarEmpresa(Request $request)
    {

        $empresa = Empresa::find($request->empresaId);
        $user = User::find(Auth::user()->id);
        $telefone = Telefone::where('empresa_id', $empresa->id)->first();
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();

        $validator = $request->validate([
            'nome' => 'nullable|string',
            'nome_fantasia' => 'nullable|string',
            'cnpjcpf' => 'nullable|string',
            'tipo' => 'nullable|string',
            'telefone1' => 'nullable|string',
            'telefone2' => 'nullable|string',
            'rua' => 'nullable|string',
            'numero' => 'nullable|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'uf' => 'nullable|string',
            'cep' => 'nullable|string',
            'complemento' => 'nullable|string',
        ]);

        if ($request['cnae'] == null) {
            session()->flash('error', 'Atenção! Uma empresa deve possuir pelo menos um CNAE. (Lista: CNAE Selecionado)');
            return back();
        }

        $verificarCnaes = array_count_values($request->cnae);
        foreach ($verificarCnaes as $key => $value) {
            if ($value > 1) {
                session()->flash('error', 'Atenção! Há um cnae repetido na sua lista de cnaes.');
                return back();
            }
        }

        $empresa->cnpjcpf = $request->cnpjcpf;
        $empresa->tipo = $request->tipo;
        $empresa->nome = $request->nome;
        $empresa->nome_fantasia = $request->nome_fantasia;
        $empresa->save();

        $telefone->telefone1 = $request->telefone1;
        $telefone->telefone2 = $request->telefone2;
        $telefone->save();

        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->bairro = $request->bairro;
        $endereco->cidade = $request->cidade;
        $endereco->uf = $request->uf;
        $endereco->cep = $request->cep;
        $endereco->complemento = $request->complemento;

        $endereco->save();

        $cnae = $request['cnae'];

        $cnaeempresa = CnaeEmpresa::where('empresa_id', $empresa->id)->pluck('cnae_id');
        $temp = [];
        foreach ($cnaeempresa as $indice) {
            array_push($temp, $indice);
        }

        for ($i = 0; $i < count($cnae); $i++) {
            if (!in_array($cnae[$i], $temp)) {

                $cnaeTemp = Cnae::find($cnae[$i]);

                // Verifica se a area especifica desse novo cnae adicionado já é uma área na checklist dessa empresa
                $checklist = Checklistemp::where('empresa_id', $request->empresaId)
                    ->where('areas_id', $cnaeTemp->areas_id)->first();

                // Caso sim, apenas incrementa o numero de cnaes associado a essa area
                if ($checklist != null) {

                    $checklist = Checklistemp::where('empresa_id', $request->empresaId)
                        ->where('areas_id', $cnaeTemp->areas_id)->update(['num_cnae' => $checklist->num_cnae + 1]);

                    $cnaeEmpresa = CnaeEmpresa::create([
                        'empresa_id' => $empresa->id,
                        'cnae_id' => $cnae[$i],
                    ]);
                } // Caso não, cria a checklist dessa nova área que antes não havia, para esta empresa
                else {
                    $areatipodocemp = AreaTipodocemp::where('area_id', $cnaeTemp->areas_id)->get();

                    foreach ($areatipodocemp as $indice) {

                        // ABAIXO SAI, CASO SEJA DUPLICADO
                        // $checklist = Checklistemp::where('nomeDoc', $indice->tipodocemp->nome)
                        // ->where('empresa_id', $empresa->id)
                        // ->first();

                        $cnaeEmpresa = Checklistemp::create([
                            'anexado' => 'false',
                            'areas_id' => $cnaeTemp->areas_id,
                            'num_cnae' => 1,
                            'nomeDoc' => $indice->tipodocemp->nome,
                            'tipodocemp_id' => $indice->tipodocemp->id,
                            'empresa_id' => $request->empresaId,
                        ]);
                    }

                    $cnaeEmpresa = CnaeEmpresa::create([
                        'empresa_id' => $empresa->id,
                        'cnae_id' => $cnae[$i],
                    ]);
                }
            }
        }

        session()->flash('success', 'Os dados da empresa foram atualizados!');
        return back();
    }

    public function edit(Request $request)
    {
        $empresa = Empresa::find(decrypt($request->empresaId));
        // $cnaeEmpresa = CnaeEmpresa::where('empresa_id','=',decrypt($request->empresaId))->get();
        $areas = Area::orderBy('nome', 'ASC')->get();

        // $resultados = CnaeEmpresa::where('empresa_id',$empresa->id)->get();
        // $resultado = [];

        // foreach ($resultados as $indice) {
        //     array_push($resultado, Cnae::find($indice->cnae_id));
        //     // dd($indice->cnae_id);
        // }

        // dd($resultado[1]);
        // // return view('coordenador/cnaes_coordenador', ['cnaes' => $cnaes]);
        // $arrayTemp = [];
        // $output = '';
        //     if($resultado->count() > 0){
        //         foreach($resultado as $item){
        //             $output .= '
        //             <div class="d-flex justify-content-center form-gerado cardMeuCnae" onmouseenter="mostrarBotaoAdicionar('.$item->id.')">
        //                 <div class="mr-auto p-2>OPA</div>
        //                     <div class="mr-auto p-2" id="'.$item->id.'">'.$item->cnae->descricao.'</div>
        //                     <input type="hidden" name="cnae[]" value="'.$item->id.'">
        //                     <div style="width:140px; height:25px; text-align:right;">
        //                         <div id="cardSelecionado'.$item->id.'" class="btn-group" style="display:none;">
        //                             <div class="btn btn-danger btn-sm" onclick="deletar_EditarCnaeEmpresa('.$item->id.')" >X</div>
        //                         </div>
        //                     </div>
        //             </div>
        //             ';
        //             array_push($arrayTemp, $item->id);
        //         }
        //     }elseif($idEmpresa == ""){
        //         $output .= '
        //                 <label></label>
        //             ';
        //     }else{
        //         $output .= '
        //                 <label>vazio</label>
        //             ';
        //     }
        //     $data = array(
        //         'success'   => true,
        //         'table_data' => $output,
        //         'arrayTemp' => $arrayTemp, //atualizar o array temp
        //     );
        //     echo json_encode($data);


        return view('empresa/editar_empresa', ["empresa" => $empresa, "areas" => $areas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function listarArquivos($id)
    {
        $docempresa = Docempresa::where("empresa_id", $id)->get();
        // Definir a página para a listagem de arquivos de uma empresa
        return view('/', ["arquivos" => $docempresa]);
    }

    public function encontrarCnae(Request $request)
    {

        $requerimento = Requerimento::where('empresas_id', $request->empresa)
            // ->where('resptecnicos_id', 1)
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

    public function baixarArquivos(Request $request)
    {
        return response()->download(storage_path('app/public/' . $request->arquivo));
    }

    public function editarArquivos(Request $request)
    {
        $validatedData = $request->validate([
            'arquivo' => ['nullable', 'file', 'mimes:pdf', 'max:5000'],
        ]);

        $docempresa = Docempresa::where("nome", $request->file)
            ->where('empresa_id', $request->empresa_id)->first();

        if ($docempresa == null) {
            session()->flash('error', 'Erro ao procurar arquivo que será substituido!');
            return back();
        }

        if ($request->arquivo != null) {

            $tipodocempresa = $docempresa->tipodocemp_id;

            Storage::delete($docempresa->nome);

            $fileDocemp = $request->arquivo;

            // $pathDocemp = 'empresas/' . $docempresa->empresa_id . '/' . $docempresa->tipodocemp_id . '/';
            $pathDocemp = 'empresas/' . $request->empresa_id . '/' . $docempresa->area . '/' . $tipodocempresa . '/';

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

    public function anexarArquivos(Request $request)
    {

        $messages = [
            'max' => 'O tamanho máximo do arquivo deve ser de 5mb!',
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

        if ($request->tipodocempresa == "Tipos de documentos") {
            session()->flash('error', 'Selecione um documento!');
            return back();
        } elseif ($request->arquivo == null) {
            session()->flash('error', 'Selecione um aquivo e tente novamente!');
            return back();
        }

        $checklist = Checklistemp::where('tipodocemp_id', $request->tipodocempresa)
            ->where('empresa_id', $request->empresaId)->where('areas_id', $request->area)->first();

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

        // $docempresa = Docempresa::where('empresa_id', $empresa->id)->where('tipodocemp_id', $request->tipodocempresa)->first();

        // if ($docempresa != null) {

        //     Storage::delete($docempresa->nome);
        //     // dd($pathDocemp . $nomeDocemp);
        //     $docempresa->nome = $pathDocemp . $nomeDocemp;
        //     $docempresa->save();

        //     Storage::putFileAs($pathDocemp, $fileDocemp, $nomeDocemp);

        //     session()->flash('success', 'Arquivo salvo com sucesso!');
        //     return back();

        // }

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function paginaCadastrarEmpresa()
    {
        // dd("opa");
        $areas = Area::orderBy('nome', 'ASC')->get();
        return view('empresa/cadastrar_empresa', ['areas' => $areas]);
    }

    /**
     * Funcao: Redireciona o dono do estabelecimento para a tela de perfil do estabelecimento
     * View de destino: empresa/show_empresa.blade.php
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showEmpresa(Request $request)
    {
        $id = Crypt::decrypt($request->value);
        $empresa = Empresa::find($id);
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();
        $telefone = Telefone::where('empresa_id', $empresa->id)->first();
        $cnaeEmpresa = CnaeEmpresa::where('empresa_id', $id)->get();
        // $respTecnicos = RespTecnico::where("empresa_id", $empresa->id)->get();
        $rtempresa = RtEmpresa::where('empresa_id', $empresa->id)->get();

        $resptecnicos = [];

        foreach ($rtempresa as $indice) {
            array_push($resptecnicos, RespTecnico::find($indice->resptec_id));
        }

        $temp = array_unique($resptecnicos);

        return view('empresa/show_empresa', [
            'empresa' => $empresa,
            'endereco' => $endereco,
            'telefone' => $telefone,
            'cnae' => $cnaeEmpresa,
            'respTecnico' => $temp,
            'empresaId' => $empresa->id,
            'empresa_status' => $empresa->status_cadastro,
        ]);
    }

    public function documentosRt(Request $request)
    {
        // $rtId = Crypt::decrypt($request->rt_id);
        $rtId = Crypt::decrypt($request->respTecnico);

        $rt = RespTecnico::find($rtId);
        $docsrt = Docresptec::where('resptecnicos_id', $rt->id)->get();
        $temp = [];
        $checkrespt = [];
        // $docsRt = [];

        $checklistresp = Checklistresp::where('resptecnicos_id', $rt->id)->orderBy('nomeDoc', 'ASC')->pluck('tipodocres_id');
        for ($i = 0; $i < count($checklistresp); $i++) {
            array_push($temp, $checklistresp[$i]);
        }

        $array = array_unique($temp);

        foreach ($array as $indice) {
            array_push($checkrespt, Checklistresp::where('tipodocres_id', $indice)
                ->where('resptecnicos_id', $rt->id)->first());
        }

        $tipodocresp = Tipodocresp::all();

        // foreach ($checkrespt as $key) {
        //     foreach ($docsrt as $indice) {
        //         if ($key->tipodocres_id == $indice->tipodocresp_id && $key->resptecnicos_id == $indice->resptecnicos_id) {
        //             $obj = (object) array(
        //                 'nomeDoc'    => $key->nomeDoc,
        //                 'anexado'    => $key->anexado,
        //                 'caminho'    => $key->nome,
        //             );
        //             array_push($docsRt, $obj);
        //         }else {
        //             $obj = (object) array(
        //                 'nomeDoc'    => $key->nomeDoc,
        //                 'anexado'    => $key->anexado,
        //                 'caminho'    => null,
        //             );
        //             array_push($docsRt, $obj);
        //         }
        //     }
        // }

        return view('empresa/documentos_rt', [
            'checklist' => $checkrespt,
            'tipodocs' => $tipodocresp,
            'docsrt' => $docsrt,
        ]);
    }

    public function showDocumentacao(Request $request)
    {

        $idEmpresa = Crypt::decrypt($request->value);
        $empresa = Empresa::where('id', $idEmpresa)->first();
        $docsempresa = Docempresa::where('empresa_id', $empresa->id)->get();
        $cnaempresa = CnaeEmpresa::where("empresa_id", $idEmpresa)->pluck('cnae_id');
        $tipos = Tipodocempresa::orderBy('nome', 'ASC')->get();
        $cnaes = [];
        $areas = [];
        $area = [];

        // dd($tipos);

        foreach ($cnaempresa as $indice) {
            array_push($cnaes, Cnae::find($indice));
        }
        foreach ($cnaes as $indice) {
            array_push($areas, $indice->areas_id);
        }

        $resultAreas = array_unique($areas);

        foreach ($resultAreas as $indice) {
            array_push($area, Area::find($indice));
        }

        $checklisttemp = Checklistemp::where('empresa_id', $empresa->id)->orderBy('nomeDoc', 'ASC')->get();
        $checklist = [];
        // dd($checklisttemp);
        // for ($i=0; $i < count($checklisttemp); $i++) {
        //     if (count($checklist) == 0) {
        //         array_push($checklist, $checklisttemp[$i]);
        //     }
        //     else {
        //         $temp = false;
        //         for ($j=0; $j < count($checklist); $j++) {
        //             if($checklisttemp[$i]->tipodocemp_id == $checklist[$j]->tipodocemp_id) {
        //                 if ($checklist[$j]->anexado == "true") {
        //                     $temp = true;
        //                 }
        //                 else {
        //                     $checklist[$j] = $checklisttemp[$i];
        //                     $temp = true;
        //                 }
        //             }
        //         }
        //         if ($temp == false) {
        //             array_push($checklist, $checklisttemp[$i]);
        //         }
        //     }
        // }


        return view('empresa/documentacao_empresa', [
            'nome' => $empresa->nome,
            'areas' => $area,
            'empresaId' => $empresa->id,
            'checklist' => $checklisttemp,
            'docsempresa' => $docsempresa,
            'tipos' => $tipos
        ]);
    }

    public function ajaxCnaes(Request $request)
    {
        $this->listar($request->id_area);
    }

    public function listar($idArea)
    {
        $resultado = Cnae::where('areas_id', '=', $idArea)->orderBy('descricao', 'ASC')->get();
        // return view('coordenador/cnaes_coordenador', ['cnaes' => $cnaes]);
        $output = '';
        if ($resultado->count() > 0) {
            foreach ($resultado as $item) {
                $output .= '
                    <div class="d-flex justify-content-center cardMeuCnae" onmouseenter="mostrarBotaoAdicionar(' . $item->id . ')">
                        <div class="mr-auto p-2>OPA</div>
                            <div class="mr-auto p-2" id="' . $item->id . '">' . $item->descricao . '</div>
                            <div style="width:140px; height:25px; text-align:right;">
                                <div id="cardSelecionado' . $item->id . '" class="btn-group" style="display:none;">
                                    <div class="btn btn-success btn-sm"  onclick="add(' . $item->id . ')" >Adicionar</div>
                                </div>
                            </div>

                    </div>

                    ';
            }
        } elseif ($idArea == "") {
            $output .= '
                        <label></label>
                    ';
        } else {
            $output .= '
                        <label>vazio</label>
                    ';
        }
        $data = array(
            'success' => true,
            'table_data' => $output,
        );
        echo json_encode($data);
    }

    /*
    * Função para add cnae
    * Tela: editar_empresa.blade
    */
    public function ajaxAddCnae_editarEmpresa(Request $request)
    {
        $this->listarCnae_editarEmpresa($request->id_area);
    }

    public function listarCnae_editarEmpresa($idArea)
    {
        $resultado = Cnae::where('areas_id', '=', $idArea)->orderBy('descricao', 'ASC')->get();
        // return view('coordenador/cnaes_coordenador', ['cnaes' => $cnaes]);
        $output = '';
        if ($resultado->count() > 0) {
            foreach ($resultado as $item) {
                $output .= '
                    <div class="d-flex justify-content-center cardMeuCnae" onmouseenter="mostrarBotaoAdicionar(' . $item->id . ')">
                        <div class="mr-auto p-2>OPA</div>
                            <div class="mr-auto p-2" id="' . $item->id . '">' . $item->descricao . '</div>
                            <div style="width:140px; height:25px; text-align:right;">
                                <div id="cardSelecionado' . $item->id . '" class="btn-group" style="display:none;">
                                    <div class="btn btn-success btn-sm"  onclick="add_EditarCnaeEmpresa(' . $item->id . ')" >Adicionar</div>
                                </div>
                            </div>

                    </div>

                    ';
            }
        } elseif ($idArea == "") {
            $output .= '
                        <label></label>
                    ';
        } else {
            $output .= '
                        <label>vazio</label>
                    ';
        }
        $data = array(
            'success' => true,
            'table_data' => $output,
            // 'arrayTemp' => $arrayTemp, //atualizar o array temp
        );
        echo json_encode($data);
    }

    public function verificarRequerimentoInspecao(Request $request)
    {
        $requerimento = Requerimento::where('empresas_id', $request->empresaId)
            ->where('cnae_id', $request->cnaeId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($requerimento == null) {
            $data = array(
                'success' => false,
            );
            echo json_encode($data);
        } elseif ($requerimento->status == "pendente") {

            $data = array(
                'success' => true,
            );
            echo json_encode($data);
        } elseif ($requerimento->status == "aprovado") {
            $inspecao = Inspecao::where('requerimento_id', $requerimento->id)->first();

            if ($inspecao == null) {
                $data = array(
                    'success' => false,
                );
                echo json_encode($data);
            } else {
                if ($inspecao->status == "aprovado") {
                    $data = array(
                        'success' => false,
                    );
                    echo json_encode($data);
                } elseif ($inspecao->status == "reprovado") {
                    $data = array(
                        'success' => false,
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        'success' => true,
                    );
                    echo json_encode($data);
                }
            }
        } elseif ($requerimento->status == "reprovado") {
            $data = array(
                'success' => false,
            );
            echo json_encode($data);
        }
    }


    /*
    * Função para mostrar na tela os cnaes da empresa
    * Tela: editar_empresa.blade
    */
    public function ajaxCnaesEmpresa(Request $request)
    {

        $this->listarCnaes($request->id_empresa);
    }

    public function listarCnaes($idEmpresa)
    {
        $resultado = CnaeEmpresa::where('empresa_id', $idEmpresa)->get();

        $arrayTemp = [];
        $output = '';
        if ($resultado->count() > 0) {
            foreach ($resultado as $item) {
                $output .= '
                <div class="d-flex justify-content-center form-gerado cardMeuCnae" onmouseenter="mostrarBotaoAdicionar(' . $item->id . ')">
                    <div class="mr-auto p-2>OPA</div>
                        <div class="mr-auto p-2" id="' . $item->id . '">' . $item->cnae->descricao . '</div>
                        <input type="hidden" name="cnae[]" value="' . $item->cnae->id . '" required>
                        <div style="width:140px; height:25px; text-align:right;">
                            <div id="cardSelecionado' . $item->id . '" class="btn-group" style="display:none;">
                                <div class="btn btn-danger btn-sm" onclick="deletar_EditarCnaeEmpresa(' . $item->id . ',' . $item->empresa_id . ',' . $item->cnae->id . ')" >X</div>
                            </div>
                        </div>
                </div>
                ';
                array_push($arrayTemp, $item->id);
            }
        } elseif ($idEmpresa == "") {
            $output .= '
                    <label></label>
                ';
        } else {
            $output .= '
                    <label></label>
                ';
        }
        $data = array(
            'success' => true,
            'table_data' => $output,
            'arrayTemp' => $arrayTemp, //atualizar o array temp
        );
        echo json_encode($data);
    }

    public function apagarCnaeEmpresa(Request $request)
    {
        $cnaeEmpresa = CnaeEmpresa::find($request->idCnaeEmp);
        $cnae = Cnae::where('id', $cnaeEmpresa->cnae_id)->first();
        $area = $cnae->areas_id;

        // Encontra o primeiro item da checklist da area do cnae que foi apagado na lista (Página de editar estabelecimento, lista da direita)
        $checklist = Checklistemp::where('empresa_id', $request->empresaId)
            ->where('areas_id', $area)->first();

        if ($checklist->num_cnae == 1) {
            // Remove uma área da checklist da empresa, quando não ouver mais cnaes escolhido durante o cadastro, relacionados a essa área
            $checklist = Checklistemp::where('empresa_id', $request->empresaId)
                ->where('areas_id', $area)->delete();

            // Remover também registro da tabela "rtempresa"
            $rtempresa = RtEmpresa::where('empresa_id', $request->empresaId)
                ->where('area_id', $area)->delete();

            // Remove o cnae da empresa
            $delete = CnaeEmpresa::destroy($request->idCnaeEmp);

            // Removendo os documentos que foram anexados a essa área
            $docsempresa = Docempresa::where('empresa_id', $request->empresaId)
                ->where('area', $area)->delete();

            $data = array(
                'valor' => "Área Removida",
            );
            echo json_encode($data);
        } elseif ($checklist->num_cnae > 1) {
            // Decrementa em 1 o numero de cnaes relacionados a essa area. Dado que um cnae foi removido da lista
            $checklist = Checklistemp::where('empresa_id', $request->empresaId)
                ->where('areas_id', $area)->update(['num_cnae' => $checklist->num_cnae - 1]);

            // Remove o cnae da empresa
            $delete = CnaeEmpresa::destroy($request->idCnaeEmp);

            $data = array(
                'valor' => "Valor decrementado",
            );
            echo json_encode($data);
        }


        // $data = array(
        //     'success'   => true,
        //     'valor'     => $checklist->num_cnae - 1,
        // );
        // echo json_encode($data);
    }

    public function foundChecklist(Request $request)
    {
        $empresa = Empresa::find($request->empresaId);
        $checklist = Checklistemp::find($request->checklistId);

        $data = array(
            'success' => true,
            'checklist' => $checklist->id,
            'empresa' => $empresa->id,
        );
        return $data;
    }

    public function downloadArquivo(Request $request)
    {
        return response()->download(storage_path('app/public/' . $request->file));
    }

    public function dadosEmpresa(Request $request)
    {
        $empresa = Empresa::find($request->id_empresa);
        $endereco = Endereco::where('empresa_id', $empresa->id)->first();

        $data = array(
            'nome' => $empresa->nome,
            'endereco' => $endereco->rua . ' ' . $endereco->numero . ' ' . $endereco->bairro,
        );

        echo json_encode($data);
    }

    public function encontrarNotificacoes(Request $request)
    {

        $notificacoes = Notificacao::where([['inspecoes_id', $request->id], ['status', 'aprovado']])->get();

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
