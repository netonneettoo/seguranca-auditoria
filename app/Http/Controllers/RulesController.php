<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ImportedPackage;
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
            $obj->package_id = intval($explode[0]);
            $obj->source = trim($explode[1]);
            $obj->destination = trim($explode[2]);
            $obj->port = intval($explode[3]);
            $obj->protocol = trim($explode[4]);
            $obj->data = trim($explode[5]);
//            dd($obj);
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
            // fazendo verificação da primeira linha, pra não levar em consideração a contagem dos registros
            if ($line_num == 0) {
                continue;
            }
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

    private function validateIp($ip, $ruleIp) {
        if ($ruleIp == '*') {
            return true;
        } else {
            return $ip == $ruleIp;
        }
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
                return boolval($port >= $start);
            } else {
                return boolval(($port >= $start) && ($port <= $end));
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
                $objReturn->messages[] = "PackageId: #{$obj->package_id}, RuleName: #{$rule['name']} - Regra de saída, sem necessidade de validar.";
                $objReturn->fail = false;
            } else {

                if(!($this->validateIp($obj->source, $rule['source']))) {
                    // retorna false, pois a validacao deu erro
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->package_id}, RuleName: #{$rule['name']} - O ip de origem ({$obj->source}) não corresponde à ({$rule['source']}}).";
                }

                if(!($this->validateIp($obj->destination, $rule['destination']))) {
                    // retorna false, pois a validacao deu erro
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->package_id}, RuleName: #{$rule['name']} - O ip de destino ({$obj->destination}) não corresponde à ({$rule['destination']}).";
                }

                if(!$this->validatePortRange($obj->port, $rule['start_port'], $rule['end_port'])) {
                    // retorna false pois a porta nao esta no range correto
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->package_id}, Rule: #{$rule['name']} - A porta ({$obj->port}) está fora do range ({$rule['start_port']}, {$rule['end_port']}).";
                }

                if(!$this->validateProtocol($obj->protocol, $rule['protocol'])) {
                    // retorna false, pois o protocolo nao corresponde
                    $objReturn->fail = true;
                    $objReturn->messages[] = "PackageId: #{$obj->package_id}, Rule: #{$rule['name']} - O protocolo ({$obj->port}) é inválido.";
                }

                if($rule['action'] == 'deny') {
                    if(!$objReturn->fail) {
                        // retorna false, pois se chegar um pacote com essas caracteristicas precisa-se bloquear
                        $obj->is_deny = true;
                        $objReturn->messages[] = "#{$obj->package_id} [{$rule['name']} = action]" . '<br/>';
                    }
                } else {
                    $obj->is_deny = false;
                }
            }

            if (!$obj->is_deny) {
                $objReturn->messages[] = "#{$obj->package_id} passou pela regra: [{$rule['name']}]" . '<br/>';
            }

            return $objReturn;
        }
    }

    public function import(Request $request)
    {
        try {

            $file = $_FILES['file'];
            $regexFilename = '/pacote\d{10}.txt/';

            if (preg_match($regexFilename, $file['name'])) {


                $arrayObj = $this->getArrayFromFile($file['tmp_name']);
                $rules = Rule::orderBy('priority', 'ASC')->get()->toArray();

                // é necessário que se faça uma transaçao do banco...
                $return = array();
                foreach ($arrayObj as $obj) {
                    $return[] = $this->validateObj($obj, $rules);
                }

                dd($return);

                //test
                \DB::beginTransaction();
                foreach ($return as $obj) {
                    if (!$obj->fail) { // se não tiver corrido erro
                        if (! $obj->package->is_deny) {
                            $importedPackage = (new ImportedPackage())->fill((array)$obj->package);
                            $importedPackage->save();
                        }
                    } else {
                        \DB::rollBack();
                    }
                }
                \DB::commit();
                //test

                return response()->json($return, 200);

            } else {
                //dd("Nome do arquivo precisa obedecer ao padrão deste exemplo: 'pacote9999999999.txt'");
                throw new \Exception('O nome do arquivo é inválido. Ex: pacote2012207180.txt', 500);
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
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
