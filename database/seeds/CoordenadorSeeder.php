<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoordenadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'coordenador',
            'email' => 'coordenador@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'coordenador',
            'status_cadastro' => 'aprovado',
        ]);

        \App\User::create([
            'name' => 'Empresa',
            'email' => 'empresa@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'empresa',
            'status_cadastro' => 'aprovado',
        ]);

        // \App\User::create([
        //     'name' => 'Empresa 2',
        //     'email' => 'empresa2@teste.com',
        //     'password' => Hash::make('123456'),
        //     'tipo' => 'empresa',
        //     'status_cadastro' => 'aprovado',
        // ]);

        \App\Empresa::create([
            'nome' => 'Ferreira Costa',
            'cnpjcpf' => '10325647899',
            'status_inspecao' => 'pendente',
            'status_cadastro' => 'aprovado',
            'tipo' => 'mei',
            'user_id' => '2',
        ]);

        // \App\Empresa::create([
        //     'nome' => 'Sobral Jóias',
        //     'cnpjcpf' => '10325647899',
        //     'status_inspecao' => 'pendente',
        //     'status_cadastro' => 'pendente',
        //     'tipo' => 'mei',
        //     'user_id' => '3',
        // ]);

        \App\Endereco::create([
            'rua' => 'Rivendell',
            'numero' => '888',
            'bairro' => 'Eriador',
            'cidade' => 'Eregion',
            'uf' => 'TM',
            'cep' => '00000000',
            'complemento' => 'Sudeste de Rhudaur',
            'empresa_id' => '1',
        ]);

        // \App\Endereco::create([
        //     'rua' => 'Ithil',
        //     'numero' => '000',
        //     'bairro' => 'White Tower',
        //     'cidade' => 'Minas Tirith',
        //     'uf' => 'TM',
        //     'cep' => '00000000',
        //     'complemento' => 'Noroeste da Terra Média',
        //     'empresa_id' => '2',
        // ]);

        \App\Telefone::create([
            'telefone1' => '37626159',
            'empresa_id' => '1',
        ]);

        // \App\Telefone::create([
        //     'telefone1' => '37956447',
        //     'empresa_id' => '2',
        // ]);

        \App\User::create([
            'name' => 'inspetor',
            'email' => 'inspetor@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'inspetor',
            'status_cadastro' => 'aprovado',
        ]);
        \App\User::create([
            'name' => 'agente',
            'email' => 'agente@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'agente',
            'status_cadastro' => 'aprovado',
        ]);
        \App\User::create([
            'name' => 'agente2',
            'email' => 'agente2@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'agente',
            'status_cadastro' => 'aprovado',
        ]);
        \App\User::create([
            'name' => 'agente3',
            'email' => 'agente3@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'agente',
            'status_cadastro' => 'aprovado',
        ]);
        \App\User::create([
            'name' => 'agente4',
            'email' => 'agente4@teste.com',
            'password' => Hash::make('123456'),
            'tipo' => 'agente',
            'status_cadastro' => 'aprovado',
        ]);

        \App\Inspetor::create([
            'formacao' => 'Doc1',
            'especializacao' => 'Doc2',
            'cpf' => Hash::make('123456'),
            'telefone' => '87981692110',
            'user_id' => '3',
        ]);

        \App\Agente::create([
            'formacao' => 'Doc3',
            'especializacao' => 'Doc4',
            'cpf' => Hash::make('123456'),
            'telefone' => '87981692110',
            'user_id' => '4',
        ]);

        \App\Agente::create([
            'formacao' => 'Doc5',
            'especializacao' => 'Doc6',
            'cpf' => Hash::make('123456'),
            'telefone' => '87981692110',
            'user_id' => '5',
        ]);

        \App\Agente::create([
            'formacao' => 'Doc7',
            'especializacao' => 'Doc8',
            'cpf' => Hash::make('123456'),
            'telefone' => '87981692110',
            'user_id' => '6',
        ]);

        \App\Agente::create([
            'formacao' => 'Doc9',
            'especializacao' => 'Doc10',
            'cpf' => Hash::make('123456'),
            'telefone' => '87981692110',
            'user_id' => '7',
        ]);

        \App\CnaeEmpresa::create([ 'empresa_id' => '1', 'cnae_id' => '1' ]);
        \App\CnaeEmpresa::create([ 'empresa_id' => '1', 'cnae_id' => '50' ]);
        \App\CnaeEmpresa::create([ 'empresa_id' => '1', 'cnae_id' => '110' ]);
        // \App\CnaeEmpresa::create([ 'empresa_id' => '2', 'cnae_id' => '3' ]);


    }
}
