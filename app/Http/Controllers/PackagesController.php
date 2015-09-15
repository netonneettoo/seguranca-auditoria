<?php namespace App\Http\Controllers;


use App\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

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
				return redirect('packages');
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
		$package = $this->model->getFind($id);
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
		$package = $this->model->getFind($id);
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
				return redirect('packages/'.$id.'/edit')
					->withErrors($validation->messages()->all())
					->withInput();
			}
			else
			{
				$data = $this->model->put($request->all(), $id);

				if (! $data->save())
				{
					return redirect('packages/edit')
						->withErrors('Could not update the package')
						->withInput();
				}
			}

			return redirect('packages');
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
		dd($request->file('file'));
	}

	public function export()
	{
		$packages = Package::all();
		$file = public_path('newfile.txt');
		$f = fopen($file, 'w+');

		if ($f) {
			$separator = ',';
			$return = [];
			foreach($packages as $package) {
				$return[] =
					Crypt::decrypt($package->package_id).$separator.
					Crypt::decrypt($package->source).$separator.
					Crypt::decrypt($package->destination).$separator.
					Crypt::decrypt($package->port).$separator.
					Crypt::decrypt($package->protocol).$separator.
					Crypt::decrypt($package->data);
			}
			//dd(implode(PHP_EOL, $return)); //test
			fwrite($f, implode(PHP_EOL, $return));
			fclose($f);
		}

		return response()->download($file, 'export-'.date('d-m-Y-H-i-s').'.txt');
	}
}
