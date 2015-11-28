<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ImportedPackage extends Model {

    protected $fillable = [
        'package_id',
        'source',
        'destination',
        'port',
        'protocol',
        'data'
    ];

    protected $rules = [
        'package_id' 	=> 'required|integer|between:1,9999',
        'source' 		=> 'required|between:7,15',
        'destination' 	=> 'required|between:7,15',
        'port' 			=> 'required|integer|between:1,9999',
        'protocol' 		=> 'required|in:tcp,udp,icmp',
        'data' 			=> 'required|between:1,50'
    ];

    protected $rulesPut = [
        'package_id' 	=> 'integer|between:1,9999',
        'source' 		=> 'between:7,15',
        'destination' 	=> 'between:7,15',
        'port' 			=> 'integer|between:1,9999',
        'protocol' 		=> 'in:tcp,udp,icmp',
        'data' 			=> 'between:1,50'
    ];

    protected $messages = [];

    protected $messagesPut = [];

    public function validate($data)
    {
        return Validator::make($data, $this->rules, $this->messages);
    }

    public function validatePut($data)
    {
        return Validator::make($data, $this->rulesPut, $this->messagesPut);
    }

    public function store($data)
    {
        $obj = new ImportedPackage();

        $obj->package_id 	= Crypt::encrypt(str_pad(trim($data['package_id']), 4, $this->blankSpace, STR_PAD_RIGHT));
        $obj->source 		= Crypt::encrypt(str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT));
        $obj->destination 	= Crypt::encrypt(str_pad(trim($data['destination']), 15, $this->blankSpace, STR_PAD_RIGHT));
        $obj->port 			= Crypt::encrypt(str_pad(trim($data['port']), 4, $this->blankSpace, STR_PAD_RIGHT));
        $obj->protocol 		= Crypt::encrypt(str_pad(trim($data['protocol']), 4, $this->blankSpace, STR_PAD_RIGHT));
        $obj->data 			= Crypt::encrypt(str_pad(trim($data['data']), 50, $this->blankSpace, STR_PAD_RIGHT));

        return $obj;
    }

    public function put($data, $id)
    {
        $obj = ImportedPackage::find($id);

        if ($obj == null)
            $obj = new ImportedPackage();

        if (@isset($data['package_id'])		&& $data['package_id'] 	!= null)
            $obj->package_id 	= Crypt::encrypt(str_pad(trim($data['package_id']), 4, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['source'])			&& $data['source'] 		!= null)
            $obj->source 		= Crypt::encrypt(str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['destination'])	&& $data['destination'] != null)
            $obj->destination 	= Crypt::encrypt(str_pad(trim($data['destination']), 15, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['port']) 			&& $data['port'] 		!= null)
            $obj->port 			= Crypt::encrypt(str_pad(trim($data['port']), 4, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['protocol']) 		&& $data['protocol'] 	!= null)
            $obj->protocol 		= Crypt::encrypt(str_pad(trim($data['protocol']), 4, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['data']) 			&& $data['data'] 		!= null)
            $obj->data 			= Crypt::encrypt(str_pad(trim($data['data']), 50, $this->blankSpace, STR_PAD_RIGHT));

        return $obj;
    }

    public function getAll()
    {
        $packages = [];
        foreach($this->all() as $package) {
            $package->package_id 	= Crypt::decrypt($package->package_id);
            $package->source 		= Crypt::decrypt($package->source);
            $package->destination 	= Crypt::decrypt($package->destination);
            $package->port 			= Crypt::decrypt($package->port);
            $package->protocol 		= Crypt::decrypt($package->protocol);
            $package->data 			= Crypt::decrypt($package->data);
            $packages[] = $package;
        }
        return $packages;
    }

    public function getFind($id)
    {
        $data = $this->find($id);
        if ($data != null) {
            $data->package_id 	= Crypt::decrypt($data->package_id);
            $data->source 		= Crypt::decrypt($data->source);
            $data->destination 	= Crypt::decrypt($data->destination);
            $data->port 		= Crypt::decrypt($data->port);
            $data->protocol 	= Crypt::decrypt($data->protocol);
            $data->data 		= Crypt::decrypt($data->data);
        } else {
            $data = new Package();
        }
        return $data;
    }

}
