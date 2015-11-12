@extends('app')

@section('content')
<div class="container">
	<div class="row">
        <div class="col-md-12">
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
	</div>
	<div class="row">
		<div class="col-md-12">
			<form id="rule-create" action="/rules" method="POST">
				<div class="panel panel-default">
					<div class="panel-heading">Packages</div>
					<div class="panel-body">
						@include('rules.form')
					</div>
					<div class="panel-footer">
						<input class="btn btn-success pull-right" type="submit" value="Create" />
						<a href="/rules" class="btn btn-danger">Cancel</a>
						<br style="clear:both;" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection