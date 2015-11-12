<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Rule;
use Illuminate\Http\Request;

class RulesController extends Controller {

    private $model;

    public function __construct(Rule $model)
    {
        $this->middleware('auth');
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

}
