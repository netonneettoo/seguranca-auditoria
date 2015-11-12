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
        'priority'      => 'required|between:1,2',
        'name'          => 'required|unique:rules|between:1,20',
        'source'        => 'required|between:1,15',
        'destination'   => 'required|between:1,15',
        'direction'     => 'required|in:in,out',
        'protocol'      => 'required|between:1,4',
        'start_port'    => 'required|between:1,5',
        'end_port'      => 'between:1,5',
        'action'        => 'required|in:allow,deny',
        'content'       => 'required|between:1,30',
    ];

    protected $rulesPut = [
        'priority'      => 'between:1,2',
        'name'          => 'unique:rules|between:1,20',
        'source'        => 'between:1,15',
        'destination'   => 'between:1,15',
        'direction'     => 'in:in,out',
        'protocol'      => 'between:1,4',
        'start_port'    => 'between:1,5',
        'end_port'      => 'between:1,5',
        'action'        => 'in:allow,deny',
        //'content'       => 'between:1,30',
    ];

    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';
    const ACTION_ALLOW = 'allow';
    const ACTION_DENY = 'deny';

    protected $messages = [];

    protected $messagesPut = [];

    public function validate($data)
    {
        $ipv4Regex = '/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
        if (preg_match($ipv4Regex, $data['source']) == 0) {
            if ($data['source'] == '*') {
                $this->rules['source'] = 'between:1,15';
            } else {
                $this->rules['source'] = 'between:1,15|ip';
            }
        }
        if (preg_match($ipv4Regex, $data['destination']) == 0) {
            if ($data['destination'] == '*') {
                $this->rules['destination'] = 'between:1,15';
            } else {
                $this->rules['destination'] = 'between:1,15|ip';
            }
        }
        return Validator::make($data, $this->rules, $this->messages);
    }

    public function validatePut($data)
    {
        $ipv4Regex = '/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
        if (preg_match($ipv4Regex, $data['source']) == 0) {
            if ($data['source'] == '*') {
                $this->rules['source'] = 'between:1,15';
            } else {
                $this->rules['source'] = 'between:1,15|ip';
            }
        }
        if (preg_match($ipv4Regex, $data['destination']) == 0) {
            if ($data['destination'] == '*') {
                $this->rules['destination'] = 'between:1,15';
            } else {
                $this->rules['destination'] = 'between:1,15|ip';
            }
        }
        return Validator::make($data, $this->rulesPut, $this->messagesPut);
    }

    public function store($data)
    {
        $obj = new Rule();

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
            $obj->id            = $data->id;
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
            $obj->id            = $data->id;
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