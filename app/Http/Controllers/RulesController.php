<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Rule;
use Illuminate\Http\Request;
use League\Flysystem\File;

class RulesController extends Controller {

    private $model;

    public function __construct(Rule $model)
    {
        //$this->middleware('auth');
        $this->model = $model;
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data = $this->model->getAll();
        return view('rules.index')->with(['rules' => $data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('rules.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        try
        {
            $validation = $this->model->validate($request->all());

            if ($validation->fails())
            {
                return redirect('rules/create')
                    ->withErrors($validation->messages()->all())
                    ->withInput();
            }

            $data = $this->model->store($request->all());

            if (! $data->save())
            {
                throw new \Exception('Could not save', 500);
            }
            else
            {
                return redirect('rules');
            }
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $data = $this->model->getFind($id);
        return view('rules.show')->with(['rule' => $data]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $data = $this->model->getFind($id);
        return view('rules.edit')->with(['rule' => $data]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        try
        {
            $validation = $this->model->validatePut($request->all());

            if ($validation->fails())
            {
                return redirect('rules/'.$id.'/edit')
                    ->withErrors($validation->messages()->all())
                    ->withInput();
            }
            else
            {
                $data = $this->model->put($request->all(), $id);

                if (! $data->save())
                {
                    return redirect('rules/edit')
                        ->withErrors('Could not update the package')
                        ->withInput();
                }
            }

            return redirect('rules');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try
        {
            if (intval($id) > 0)
            {
                $data = $this->model->find($id);

                if ($data != null)
                {
                    $data->delete();

                    return redirect('rules');
                }
            }

            return redirect('rules/create')
                ->withErrors('Could not delete the rule:' . $id)
                ->withInput();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
	}

    private function getObjFromLine($line) {
        $explode = explode(',', $line);
        if (count($explode) == 6) {
            $obj = new \stdClass();
            $obj->packageId = intval($explode[0]);
            $obj->source = trim($explode[1]);
            $obj->destination = trim($explode[2]);
            $obj->port = intval($explode[3]);
            $obj->protocol = trim($explode[4]);
            $obj->content = trim($explode[5]);
            return $obj;
        } else {
            return false;
        }
    }

    private function getArrayFromFile($file) {
        $lines = file($file);
        $countLines = count($lines);
        $arrayObj = array();
        foreach($lines as $line_num => $line) {
            // não pega a última linha que é o EOF
            if ($countLines - 1 == $line_num) {
                if (htmlspecialchars($line) == 'EOF') {
                    break;
                }
            }
            $objFromLine = $this->getObjFromLine(htmlspecialchars($line));
            if($objFromLine !== false) {
                $arrayObj[] = $objFromLine;
            }
        };
        return $arrayObj;
    }

    private function validateIpRange($ip, $start, $end) {
        $ipLong = ip2long($ip);
        $startLong = ip2long($start);
        $endLong = ip2long($end);
        echo "ip[{$ip}], start[{$start}], end[{$end}]<br/>";
        echo "ipLong[{$ipLong}], startLong[{$startLong}], endLong[{$endLong}]<br/>";
        echo "ip >= start: ".($ipLong >= $startLong)."<br/>";
        echo "ip <= end: ".($ipLong <= $endLong)."<br/>";
        echo "<br/>";
        return $ipLong >= $startLong && $ipLong <= $endLong;
    }

    private function validatePortRange($port, $start, $end) {
        if($start === '*') {
            return true;
        } else {
            if($end === '*') {
                return $port >= $start;
            } else {
                return $port >= $start && $port <= $end;
            }
        }
    }

    private function validateProtocol($protocol, $protocolRule) {
        if ($protocolRule === '*') {
            return true;
        } else {
            return $protocol == $protocolRule;
        }
    }

    private function validateObj($obj, $rules) {
        foreach($rules as $rule) {
            // priority, name, source, destination, direction, protocol, start_port, end_port, action, content,

            if ($rule['direction'] == 'out') {
                // retorna true, pois nao vai precisar validar o restante dos campos
                // return true;
            } else {

                if(!($this->validateIpRange($obj->source, $rule['source'], $rule['destination']) && $this->validateIpRange($obj->destination, $rule['source'], $rule['destination']))) {
                    // retorna false, pois a validacao deu erro
                    echo "#{$obj->packageId} [{$rule['name']} = source, destination]" . '<br/>';
                    return false;
                }

                if(!$this->validatePortRange($obj->port, $rule['start_port'], $rule['end_port'])) {
                    // retorna false pois a porta nao esta no range correto
                    echo "#{$obj->packageId} [{$rule['name']} = port]" . '<br/>';
                    return false;
                }

                if(!$this->validateProtocol($obj->protocol, $rule['protocol'])) {
                    // retorna false, pois o protocolo nao corresponde
                    echo "#{$obj->packageId} [{$rule['name']} = protocol]" . '<br/>';
                    return false;
                }

                if($rule['action'] == 'deny') {
                    // retorna false, pois se chegar um pacote com essas caracteristicas precisa-se bloquear
                    echo "#{$obj->packageId} [{$rule['name']} = action]" . '<br/>';
                    return false;
                }
            }

            echo "#{$obj->packageId} - ok" . '<br/>';
        }
    }

    public function import(Request $request)
    {
        $arrayObj = $this->getArrayFromFile($request->file('file'));

        $rules = Rule::orderBy('priority', 'ASC')->get()->toArray();

        $errors[] = array();
        foreach($arrayObj as $obj) {
            $this->validateObj($obj, $rules);
        }
    }

    public function sortable(Request $request) {

        try {
            foreach ($request->get('rules') as $rule) {
                $r = Rule::find($rule['id']);
                $r->priority = $rule['priority'];
                $r->save();
            }
            return response()->json($request->all(), 200);
        } catch (\Exception $e) {
            return response()->json($request->all(), 500);
        }
    }
}
