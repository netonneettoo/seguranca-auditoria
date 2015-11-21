<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportedPackage extends Model {

    protected $fillable = [
        'package_id',
        'source',
        'destination',
        'port',
        'protocol',
        'data'
    ];

}
