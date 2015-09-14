<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Package extends Model {

	private $blankSpace = ' ';

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
		$obj = new Package();

		$obj->package_id 	= str_pad(trim($data['package_id']), 4, $this->blankSpace, STR_PAD_RIGHT);
		$obj->source 		= str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT);
		$obj->destination 	= str_pad(trim($data['destination']), 15, $this->blankSpace, STR_PAD_RIGHT);
		$obj->port 			= str_pad(trim($data['port']), 4, $this->blankSpace, STR_PAD_RIGHT);
		$obj->protocol 		= str_pad(trim($data['protocol']), 4, $this->blankSpace, STR_PAD_RIGHT);
		$obj->data 			= str_pad(trim($data['data']), 50, $this->blankSpace, STR_PAD_RIGHT);

		return $obj;
	}

	public function put($data, $id)
	{
		$obj = Package::find($id);

		if ($obj == null)
			$obj = new Package();

		if (@isset($data['package_id'])		&& $data['package_id'] 	!= null)
			$obj->package_id 	= str_pad(trim($data['package_id']), 4, $this->blankSpace, STR_PAD_RIGHT);

		if (@isset($data['source'])			&& $data['source'] 		!= null)
			$obj->source 		= str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT);

		if (@isset($data['destination'])	&& $data['destination'] != null)
			$obj->destination 	= str_pad(trim($data['destination']), 15, $this->blankSpace, STR_PAD_RIGHT);

		if (@isset($data['port']) 			&& $data['port'] 		!= null)
			$obj->port 			= str_pad(trim($data['port']), 4, $this->blankSpace, STR_PAD_RIGHT);

		if (@isset($data['protocol']) 		&& $data['protocol'] 	!= null)
			$obj->protocol 		= str_pad(trim($data['protocol']), 4, $this->blankSpace, STR_PAD_RIGHT);

		if (@isset($data['data']) 			&& $data['data'] 		!= null)
			$obj->data 			= str_pad(trim($data['data']), 50, $this->blankSpace, STR_PAD_RIGHT);

		return $obj;
	}

	public function getAll()
	{
		$packages = [];
		foreach($this->all() as $package) {
			$package->protocol = strtoupper($package->protocol);
			$package->data = strtoupper(substr($package->data, 0, 20) . '...');
			$packages[] = $package;
		}
		return $packages;
	}

}
