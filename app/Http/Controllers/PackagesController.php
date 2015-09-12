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
				return redirect('packages/create')
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
				return view('packages.index');
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
	public function update(Request $request, $id)
	{
		try
		{
			$validation = $this->model->validatePut($request->all());

			if ($validation->fails())
			{
				return redirect('packages/create')
					->withErrors($validation->messages()->all())
					->withInput();
			}
			else
			{
				$data = $this->model->put($request->all(), $id);

				if (! $data->save())
				{
					return redirect('packages/create')
						->withErrors('Could not update the package')
						->withInput();
				}
			}

			return view('packages.index');
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
				$data = Package::find($id);

				if ($data != null)
				{
					$data->delete();

					return redirect('packages');
				}
			}

			return redirect('packages/create')
				->withErrors('Could not delete the package:' . $id)
				->withInput();
		}
		catch(\Exception $e)
		{
			dd($e->getMessage());
		}
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
