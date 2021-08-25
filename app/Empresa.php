<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'email', 'nome_fantasia', 'cnpjcpf', 'status_inspecao', 'status_cadastro', 'tipo', 'user_id',
    ];

    public function user() {
        return $this->belongsTo("\App\User");
    }

    // Responsaveis tecnicos
    public function resptecnicos() {
        return $this->hasMany("\App\RespTecnico");
    }

    public function telefone() {
        return $this->hasMany("\App\Telefone");
    }

    public function endereco() {
        return $this->hasOne("\App\Endereco");
    }

    //Documentos da empresa
    public function docsempresa() {
        return $this->hasMany("\App\Docempresa");
    }

    //A pensar
    public function cnae() {
        return $this->hasMany("\App\Cnae");
    }

    public function cnaeEmpresa() {
        return $this->hasMany("\App\CnaeEmpresa");
    }

    public function denuncia() {
        return $this->hasMany("\App\Denuncia");
    }

    public function inspecao() {
        return $this->hasMany("\App\Inspecao");
    }

}
