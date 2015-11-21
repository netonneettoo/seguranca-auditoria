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
    
    private function validateIpRangeSource($ip, $min) {
        $ipLong = intval(str_replace('.', '', $ip));//ip2long($ip);
        $minLong = intval(str_replace('.', '', $min));//ip2long($min);
        return boolval($ipLong >= $minLong);
    }

    private function validateIpRangeDestination($ip, $max) {
        $ipLong = ip2long($ip);
        $maxLong = ip2long($max);
        return boolval($ipLong <= $maxLong);
    }

    private function validatePortRange($port, $start, $end) {
        if($start === '*') {
            return true;
        } else {
            if($end === '*') {
                return $port >= $start;
            } else {
                return (($port >= $start) && ($port <= $end));
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

            $objReturn = new \stdClass();
            $objReturn->fail = false;
            $objReturn->package = $obj;
            $objReturn->messages = array();

            if ($rule['direction'] == 'out') {
                // retorna true, pois nao vai precisar validar o restante dos campos
                $objReturn->messages[] = "PackageId: #{$obj->packageId}, RuleName: #{$rule['name']} - Regra de saída, sem necessidade de validar.";
                $objReturn->fail = false;
            } else {

                if(!($this->validateIpRangeSource($obj->source, $rule['source']))) {
                    // retorna false, pois a validacao deu erro
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->packageId}, RuleName: #{$rule['name']} - O ip de origem ({$obj->source}) está fora do range ({$rule['source']}, {$rule['destination']}).";
                }

                if(!($this->validateIpRangeDestination($obj->destination, $rule['destination']))) {
                    // retorna false, pois a validacao deu erro
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->packageId}, RuleName: #{$rule['name']} - O ip de destino ({$obj->destination}) está fora do range ({$rule['source']}, {$rule['destination']}).";
                }

                if(!$this->validatePortRange($obj->port, $rule['start_port'], $rule['end_port'])) {
                    // retorna false pois a porta nao esta no range correto
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->packageId}, Rule: #{$rule['name']} - A porta ({$obj->port}) está fora do range ({$rule['start_port']}, {$rule['end_port']}).";
                }

                if(!$this->validateProtocol($obj->protocol, $rule['protocol'])) {
                    // retorna false, pois o protocolo nao corresponde
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->packageId}, Rule: #{$rule['name']} - O protocolo ({$obj->port}) é inválido.";
                }

                if($rule['action'] == 'deny') {
                    // retorna false, pois se chegar um pacote com essas caracteristicas precisa-se bloquear
                    $objReturn->fail = true;
                    $objReturn->messages[] = "#{$obj->packageId} [{$rule['name']} = action]" . '<br/>';
                }
            }

            return $objReturn;
        }
    }

    public function import(Request $request)
    {
        try {
            $arrayObj = $this->getArrayFromFile($request->file('file'));
            $rules = Rule::orderBy('priority', 'ASC')->get()->toArray();

            // é necessário que se faça uma transaçao do banco...
            $return = array();
            foreach($arrayObj as $obj) {
                $return[] = $this->validateObj($obj, $rules);
            }
            return response()->json($return, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 500);
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
