@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
				<div class="panel-heading">
                    Packages
                    <a href="/packages/create" class="btn btn-success pull-right" data-toggle="tooltip" data-placement="left" title="Add">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <br style="clear:both;" />
                </div>
				<div class="panel-body">
					<table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Port</th>
                                <th>Protocol</th>
                                <th>Data</th>
                                <th width="140px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>{{$package->package_id}}</td>
                                    <td>{{$package->source}}</td>
                                    <td>{{$package->destination}}</td>
                                    <td>{{$package->port}}</td>
                                    <td>{{$package->protocol}}</td>
                                    <td>{{$package->data}}</td>
                                    <td>
                                        <a href="/packages/{{$package->id}}" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Show">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="/packages/{{$package->id}}/edit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>
                                        <form id="form-delete-{{$package->id}}" action="/packages/{{$package->id}}" method="POST" style="display:inline;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Remove">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
					</table>
				</div>
                <div class="panel-footer">
                    <div class="pull-right">
                        {{--<form id="form-import" style="display:inline;">--}}
                            {{--<input id="token_import" type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                            {{--<input id="file-import" type="file" name="file" accept="text/plain" style="display:none;" />--}}
                            {{--<button class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Import TXT">--}}
                                {{--<span class="glyphicon glyphicon-import" aria-hidden="true"></span>--}}
                            {{--</button>--}}
                        {{--</form>--}}
                        <a href="/packages/export" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Export to TXT">
                            <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
                        </a>
                    </div>
                    <br style="clear:both;" />
                </div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function()
        {
            $('form[id^=form-delete] button').click(function (evt) {
                evt.preventDefault();
                var parent = $(this).parent('form');
                new PNotify({
                    title: 'Deletion confirmation',
                    text: 'Are you sure you want to delete this item?',
                    styling: "bootstrap3",
                    icon: 'glyphicon glyphicon-question-sign',
                    hide: false,
                    confirm: {
                        confirm: true,
                        buttons: [
                        {
                            text: 'Delete',
                            addClass: 'btn-danger',
                            click: function(notice) {
                                PNotify.removeAll();
                                parent.submit();
                            }
                        },
                        {
                            text: 'Cancel',
                            click: function(notice) {
                                PNotify.removeAll();
                            }
                        }]
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    }
                });
            });

            var sendFile = function(file)
            {
                var url = '/packages/import';
                var formData = new FormData();
                formData.append('_token', $('#token_import').val());
                formData.append('file', file);

                $.ajax({
                    url: window.location.origin + url,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function() {  // Custom XMLHttpRequest
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                            myXhr.upload.addEventListener('progress', function (elem) {
                                //* faz alguma coisa durante o progresso do upload
                                console.log(elem);
                            }, false);
                        }
                        return myXhr;
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (data) {
                        console.log(data);
                    },
                });
            }

            $('#form-import').submit(function(evt) {
                evt.preventDefault();
                $('#file-import').click();
            });

            $('#file-import').change(function(event) {
                sendFile(event.target.files[0]);
            });
        });
    </script>
@endsection