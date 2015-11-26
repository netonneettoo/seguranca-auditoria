@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
				<div class="panel-heading">
                    Rules
                    <a href="/rules/create" class="btn btn-success pull-right" data-toggle="tooltip" data-placement="left" title="Add">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <br style="clear:both;" />
                </div>
				<div class="panel-body">
					<table class="table table-striped table-hover table-condensed" id="table-rules">
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Name</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Direction</th>
                                <th>Protocol</th>
                                <th>Start Port</th>
                                <th>End Port</th>
                                <th>Action</th>
                                <th>Content</th>
                                <th width="140px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rules as $rule)
                                <tr>
                                    <td class="priority" data-rule-id="{{$rule->id}}">{{$rule->priority}}</td>
                                    <td>{{$rule->name}}</td>
                                    <td>{{$rule->source}}</td>
                                    <td>{{$rule->destination}}</td>
                                    <td>{{$rule->direction}}</td>
                                    <td>{{$rule->protocol}}</td>
                                    <td>{{$rule->start_port}}</td>
                                    <td>{{$rule->end_port}}</td>
                                    <td>{{$rule->action}}</td>
                                    <td>{{$rule->content}}</td>
                                    <td>
                                        <a href="/rules/{{$rule->id}}" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Show">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="/rules/{{$rule->id}}/edit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>
                                        <form id="form-delete-{{$rule->id}}" action="/rules/{{$rule->id}}" method="POST" style="display:inline;">
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
                        <form id="form-import" style="display:inline;">
                            <input id="token_import" type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input id="file-import" type="file" name="file" accept="text/plain" style="display:none;" />
                            <button class="btn btn-xs btn-primary">
                                <span class="glyphicon glyphicon-import" aria-hidden="true"></span> Validate File
                            </button>
                        </form>
                        {{--<a href="/packages/export" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Export to TXT">--}}
                            {{--<span class="glyphicon glyphicon-export" aria-hidden="true"></span>--}}
                        {{--</a>--}}
                    </div>
                    <br style="clear:both;" />
                </div>
			</div>
		</div>
	</div>

    <!-- Modal HTML embedded directly into document -->
    <div id="result-import-rules" style="display:none;"></div>
</div>
@endsection

@section('scripts')
    <script src="/plugins/row-sorter/RowSorter.js"></script>
    <script>
        $(document).ready(function()
        {
            $("#table-rules").rowSorter({
                handler: "td.priority",
                onDrop: function() {
                    var rulesArray = [];
                    $('#table-rules tbody tr').each(function(index, element) {
                        var tr = $(this).find('td.priority')[0];
                        $(tr).text((index + 1));
                        rulesArray.push({
                            "id": parseInt($(tr).attr('data-rule-id')),
                            "priority": (index + 1)
                        });
                    });

                    $.ajax({
                        url: '/rules/sortable',
                        type: 'POST',
                        data: {'rules' : rulesArray},
                        cache: false,
                        success: function (data) {
                            console.log(data);

//                            var modal = '#result-import-rules';
//                            var aTag = document.createElement('div');
//                            aTag.setAttribute('href', modal);
//                            aTag.setAttribute('rel', 'modal:open');
//                            aTag

                            new PNotify({
                                text: 'Priorities successfully changed.',
                                styling: "bootstrap3",
                                icon: '',
                                type: 'success',
                                delay: 2000
                            });
                        },
                        error: function (data) {
                            new PNotify({
                                text: 'There was an error changing priorities.',
                                styling: "bootstrap3",
                                icon: '',
                                type: 'error',
                                delay: 2000
                            });
                        }
                    });
                }
            });

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
                var url = '/rules/import';
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
                                //console.log(elem);
                            }, false);
                        }
                        return myXhr;
                    },
                    success: function (data) {
                        new PNotify({
                            text: 'File of packages successfully imported.',
                            styling: "bootstrap3",
                            icon: '',
                            type: 'success',
                            delay: 2000
                        });
                    },
                    error: function (data) {
                        new PNotify({
                            text: data.responseJSON.message,
                            styling: "bootstrap3",
                            icon: '',
                            type: 'error',
                            delay: 2000
                        });
                    }
                });
            };

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