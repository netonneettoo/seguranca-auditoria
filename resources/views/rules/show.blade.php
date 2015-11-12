@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
				<div class="panel-heading">Packages</div>
				<div class="panel-body">
                    <strong>Id: </strong><br/>
					{{$package->package_id}} <br /><br />
                    <strong>Source: </strong><br/>
					{{$package->source}} <br /><br />
                    <strong>Destination: </strong><br/>
					{{$package->destination}} <br /><br />
                    <strong>Port: </strong><br/>
					{{$package->port}} <br /><br />
                    <strong>Protocol: </strong><br/>
					{{$package->protocol}} <br /><br />
                    <strong>Data: </strong><br/>
					{{$package->data}} <br /><br />
				</div>
                <div class="panel-footer">
                    <a href="/packages" class="btn btn-default">To Back</a>
                    <a href="/packages/{{$package->id}}/edit" class="btn btn-success pull-right">Edit</a>
                    <br style="clear:both;" />
                </div>
			</div>
		</div>
	</div>
</div>
@endsection