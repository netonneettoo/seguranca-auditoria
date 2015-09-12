@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form action="/packages/{{$package->id}}" method="POST">
				<div class="panel panel-default">
					<div class="panel-heading">Packages</div>
					<div class="panel-body">
						@include('packages.form')
					</div>
					<div class="panel-footer">
						<input class="btn btn-success pull-right" type="submit" value="Update" />
						<a href="/packages" class="btn btn-danger">Cancel</a>
						<br style="clear:both;" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection