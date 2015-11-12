@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
				<div class="panel-heading">Rule</div>
				<div class="panel-body">
                    <strong>Id: </strong><br/>
					{{$rule->id}} <br /><br />
                    <strong>Priority: </strong><br/>
					{{$rule->priority}} <br /><br />
                    <strong>Name: </strong><br/>
					{{$rule->name}} <br /><br />
                    <strong>Source: </strong><br/>
					{{$rule->source}} <br /><br />
                    <strong>Destination: </strong><br/>
					{{$rule->destination}} <br /><br />
                    <strong>Direction: </strong><br/>
					{{$rule->direction}} <br /><br />
                    <strong>Protocol: </strong><br/>
                    {{$rule->protocol}} <br /><br />
                    <strong>Start Port: </strong><br/>
                    {{$rule->start_port}} <br /><br />
                    <strong>End Port: </strong><br/>
                    {{$rule->end_port}} <br /><br />
                    <strong>Action: </strong><br/>
                    {{$rule->action}} <br /><br />
                    <strong>Content: </strong><br/>
                    {{$rule->content}} <br /><br />
				</div>
                <div class="panel-footer">
                    <a href="/rules" class="btn btn-default">To Back</a>
                    <a href="/rules/{{$rule->id}}/edit" class="btn btn-success pull-right">Edit</a>
                    <br style="clear:both;" />
                </div>
			</div>
		</div>
	</div>
</div>
@endsection