<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Rule extends Model {

    protected $fillable = [
        'priority',
        'name',
        'source',
        'destination',
        'direction',
        'protocol',
        'start_port',
        'end_port',
        'action',
        'content',
    ];

    protected $rules = [
        'priority'      => 'required|between:2,2',
        'name'          => 'required|unique|between:20,20',
        'source'        => 'required|between:15,15',
        'destination'   => 'required|between:15,15',
        'direction'     => 'required|in:IN,OUT',
        'protocol'      => 'required|between:4,4',
        'start_port'    => 'required|between:5,5',
        'end_port'      => 'between:5,5',
        'action'        => 'required|in:ALLOW,DENY',
        'content'       => 'required',
    ];

    protected $rulesPut = [
        'priority'      => 'between:2,2',
        'name'          => 'unique|between:20,20',
        'source'        => 'between:15,15',
        'destination'   => 'between:15,15',
        'direction'     => 'in:IN,OUT',
        'protocol'      => 'between:4,4',
        'start_port'    => 'between:5,5',
        'end_port'      => 'between:5,5',
        'action'        => 'in:ALLOW,DENY',
        //'content'       => ''
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
        $obj = new Rule();

//        $obj->package_id 	= Crypt::encrypt(str_pad(trim($data['priority']), 2, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->source 		= Crypt::encrypt(str_pad(trim($data['name']), 15, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->destination 	= Crypt::encrypt(str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->port 			= Crypt::encrypt(str_pad(trim($data['destination']), 4, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->protocol 		= Crypt::encrypt(str_pad(trim($data['direction']), 4, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->data 			= Crypt::encrypt(str_pad(trim($data['protocol']), 50, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->data 			= Crypt::encrypt(str_pad(trim($data['start_port']), 50, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->data 			= Crypt::encrypt(str_pad(trim($data['end_port']), 50, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->data 			= Crypt::encrypt(str_pad(trim($data['action']), 50, $this->blankSpace, STR_PAD_RIGHT));
//        $obj->data 			= Crypt::encrypt(str_pad(trim($data['content']), 50, $this->blankSpace, STR_PAD_RIGHT));

        $obj->priority = $data['priority'];
        $obj->name = $data['name'];
        $obj->source = $data['source'];
        $obj->destination = $data['destination'];
        $obj->direction = $data['direction'];
        $obj->protocol = $data['protocol'];
        $obj->start_port = $data['start_port'];
        $obj->end_port = $data['end_port'];
        $obj->action = $data['action'];
        $obj->content = $data['content'];

        return $obj;
    }

    public function put($data, $id)
    {
        $obj = Rule::find($id);

        if ($obj == null)
            $obj = new Rule();

//        if (@isset($data['package_id'])		&& $data['package_id'] 	!= null)
//            $obj->package_id 	= Crypt::encrypt(str_pad(trim($data['package_id']), 4, $this->blankSpace, STR_PAD_RIGHT));
//
//        if (@isset($data['source'])			&& $data['source'] 		!= null)
//            $obj->source 		= Crypt::encrypt(str_pad(trim($data['source']), 15, $this->blankSpace, STR_PAD_RIGHT));
//
//        if (@isset($data['destination'])	&& $data['destination'] != null)
//            $obj->destination 	= Crypt::encrypt(str_pad(trim($data['destination']), 15, $this->blankSpace, STR_PAD_RIGHT));
//
//        if (@isset($data['port']) 			&& $data['port'] 		!= null)
//            $obj->port 			= Crypt::encrypt(str_pad(trim($data['port']), 4, $this->blankSpace, STR_PAD_RIGHT));
//
//        if (@isset($data['protocol']) 		&& $data['protocol'] 	!= null)
//            $obj->protocol 		= Crypt::encrypt(str_pad(trim($data['protocol']), 4, $this->blankSpace, STR_PAD_RIGHT));
//
//        if (@isset($data['data']) 			&& $data['data'] 		!= null)
//            $obj->data 			= Crypt::encrypt(str_pad(trim($data['data']), 50, $this->blankSpace, STR_PAD_RIGHT));

        if (@isset($data['priority']) && $data['priority'] != null)
            $obj->priority = $data['priority'];
        if (@isset($data['name']) && $data['name'] != null)
            $obj->name = $data['name'];
        if (@isset($data['source']) && $data['source'] != null)
            $obj->source = $data['source'];
        if (@isset($data['destination']) && $data['destination'] != null)
            $obj->destination = $data['destination'];
        if (@isset($data['direction']) && $data['direction'] != null)
            $obj->direction = $data['direction'];
        if (@isset($data['protocol']) && $data['protocol'] != null)
            $obj->protocol = $data['protocol'];
        if (@isset($data['start_port']) && $data['start_port'] != null)
            $obj->start_port = $data['start_port'];
        if (@isset($data['end_port']) && $data['end_port'] != null)
            $obj->end_port = $data['end_port'];
        if (@isset($data['action']) && $data['action'] != null)
            $obj->action = $data['action'];
        if (@isset($data['content']) && $data['content'] != null)
            $obj->content = $data['content'];

        return $obj;
    }

    public function getAll()
    {
        $all = [];
        foreach($this->all() as $data) {
            $obj = new \stdClass();
            $obj->priority      = $data->priority;
            $obj->name          = $data->name;
            $obj->source        = $data->source;
            $obj->destination   = $data->destination;
            $obj->direction     = $data->direction;
            $obj->protocol      = $data->protocol;
            $obj->start_port    = $data->start_port;
            $obj->end_port      = $data->end_port;
            $obj->action        = $data->action;
            $obj->content       = $data->content;
            $all[] = $obj;
        }
        return $all;
    }

    public function getFind($id)
    {
        $data = $this->find($id);
        if ($data != null) {
            $obj = new \stdClass();
            $obj->priority      = $data->priority;
            $obj->name          = $data->name;
            $obj->source        = $data->source;
            $obj->destination   = $data->destination;
            $obj->direction     = $data->direction;
            $obj->protocol      = $data->protocol;
            $obj->start_port    = $data->start_port;
            $obj->end_port      = $data->end_port;
            $obj->action        = $data->action;
            $obj->content       = $data->content;
        } else {
            $obj = new Rule();
        }
        return $obj;
    }
}