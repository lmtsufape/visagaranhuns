<?php

use Illuminate\Database\Seeder;

class RequerimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cnae = \App\Cnae::where('codigo', '=', '1')->first();
        \App\Requerimento::create(['tipo'=> 'Diversas',
            'status' => 'aprovado',
            'data' => today(),
            'aviso' => '',
            'cnae_id' => $cnae->id
        ]);
    }
}
