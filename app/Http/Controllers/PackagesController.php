<?php namespace App\Http\Controllers;


use App\Package;
use Illuminate\Http\Request;

class PackagesController extends Controller {

	private $model;

	public function __construct(Package $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$packages = $this->model->getAll();
		return view('packages.index')->with(['packages' => $packages]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('packages.create');
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
				throw new \Exception($validation->messages()->all(), 500);
			}

			$data = $this->model->store($request->all());

			if (! $data->save())
			{
				throw new \Exception('Could not save', 500);
			}
			else
			{
				return view('packages.index');
			}
		}
		catch(\Exception $e)
		{
			/*return redirect('packages.store')
				->withErrors($e->getMessage())
				->withInput();*/
			return $e->getMessage();
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
		$package = $this->model->find($id);
		return view('packages.show')->with(['package' => $package]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$package = $this->model->find($id);
		return view('packages.edit')->with(['package' => $package]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		dd('ok: ' . $id . ' deletou.');
		
	}

	public function import(Request $request)
	{
		//$reader = \CsvReader::open('/path/to/file.csv');

		dd($request->file('file'));
	}

	public function export()
	{
		dd('success exporting');
	}
}
