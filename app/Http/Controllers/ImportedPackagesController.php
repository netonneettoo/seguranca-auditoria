<?php namespace App\Http\Controllers;


use App\ImportedPackage;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportedPackagesController extends Controller {

	private $model;

	public function __construct(ImportedPackage $model)
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
		$packages = $this->model->all();
		return view('imported-packages.index')->with(['packages' => $packages]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('imported-packages.create');
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
				return redirect('imported-packages/create')
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
				return redirect('imported-packages');
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
		return view('imported-packages.show')->with(['package' => $package]);
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
		return view('imported-packages.edit')->with(['package' => $package]);
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
				return redirect('imported-packages/'.$id.'/edit')
					->withErrors($validation->messages()->all())
					->withInput();
			}
			else
			{
				$data = $this->model->put($request->all(), $id);

				if (! $data->save())
				{
					return redirect('imported-packages/edit')
						->withErrors('Could not update the package')
						->withInput();
				}
			}

			return redirect('imported-packages');
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
				$data = ImportedPackage::find($id);

				if ($data != null)
				{
					$data->delete();

					return redirect('imported-packages');
				}
			}

			return redirect('imported-packages/create')
				->withErrors('Could not delete the package:' . $id)
				->withInput();
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function import(Request $request)
	{
		$file = $request->file('file');
		$regexFilename = '/pacote\d{10}.txt/';

		if (preg_match($regexFilename, $file->getClientOriginalName())) {
			//dd('success');
		} else {
			//dd("Nome do arquivo precisa obedecer ao padrão deste exemplo: 'pacote9999999999.txt'");
		}

		$lines = explode(PHP_EOL, File::get($file->getRealPath()));

		$return = [];
		foreach($lines as $line) {
			$fields = explode(',', $line);
			$newArray['package_id'] = $fields[0];
			$newArray['source'] = $fields[1];
			$newArray['destination'] = $fields[2];
			$newArray['port'] = $fields[3];
			$newArray['protocol'] = $fields[4];
			$newArray['data'] = $fields[5];
			$return[] = $newArray;
		}

		dd($return);
	}

	public function export()
	{
		$packages = Package::all();
		$file = public_path('file-export.txt');
		$fopen = fopen($file, 'w+');

		if ($fopen) {
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
            // adiciona EOF no final do arquivo
            $return[] = 'EOF';
			//dd(implode(PHP_EOL, $return)); //test
			fwrite($fopen, implode(PHP_EOL, $return));
			fclose($fopen);
		}

		return response()->download($file, 'pacote2012207180.txt');
	}
}
