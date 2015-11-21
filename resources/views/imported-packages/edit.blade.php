@extends('app')

@section('content')
<div class="container">
	<div class="row">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	</div>
	<div class="row">
		<div class="col-md-12">
			<form id="package-edit" action="/imported-packages/{{$package->id}}" method="POST">
				<div class="panel panel-default">
					<div class="panel-heading">Packages</div>
					<div class="panel-body">
						<input type="hidden" name="_method" value="PUT">
						@include('packages.form')
					</div>
					<div class="panel-footer">
						<input class="btn btn-success pull-right" type="submit" value="Update" />
						<a href="/imported-packages" class="btn btn-danger">Cancel</a>
						<br style="clear:both;" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection