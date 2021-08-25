<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespTecnico extends Model
{
    protected $table = 'resptecnicos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'formacao', 'especializacao', 'cpf', 'telefone', 'user_id', 'area_id', 'empresa_id'
        'formacao', 'especializacao', 'cpf', 'telefone', 'conselho', 'num_conselho', 'user_id'
    ];

    public function empresa() {
        return $this->belongsToMany("\App\Empresa");
    }

    public function user() {
        return $this->belongsTo("\App\User");
    }

    public function area() {
        return $this->hasOne("\App\Area");
    }

}
