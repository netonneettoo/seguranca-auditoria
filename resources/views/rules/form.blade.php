<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
    <label for="rule_id">Id</label>
    <input class="form-control" type="text" id="rule_id" name="rule_id" autofocus="autofocus"/>
</div>
<div class="form-group">
    <label for="priority">Priority</label>
    <input class="form-control" type="number" step="1" min="1" max="99" id="priority" name="priority" maxlength="2"/>
</div>
<div class="form-group">
    <label for="name">Name</label>
    <input class="form-control" type="text" id="name" name="name" maxlength="20"/>
</div>
<div class="form-group">
    <label for="source">Source</label>
    <input class="form-control" type="text" id="source" name="source" maxlength="15"/>
</div>
<div class="form-group">
    <label for="destination">Destination</label>
    <input class="form-control" type="text" id="destination" name="destination" maxlength="15"/>
</div>
<div class="form-group">
    <label for="direction">Direction</label>
    <select class="form-control" id="direction" name="direction">
        <option value="">-- Choose --</option>
        <option value="in">IN</option>
        <option value="out">OUT</option>
    </select>
</div>
<div class="form-group">
    <label for="protocol">Protocol</label>
    <select class="form-control" id="protocol" name="protocol">
        <option value="">-- Choose --</option>
        <option value="tcp">TCP</option>
        <option value="udp">UDP</option>
        <option value="icmp">ICMP</option>
        <option value="*">All</option>
    </select>
</div>
<div class="form-group">
    <label for="start_port">Start Port</label>
    <input class="form-control" type="text" id="start_port" name="start_port" maxlength="5"/>
</div>
<div class="form-group">
    <label for="end_port">End Port</label>
    <input class="form-control" type="text" id="end_port" name="end_port" maxlength="5"/>
</div>
<div class="form-group">
    <label for="action">Action</label>
    <select class="form-control" id="action" name="action">
        <option value="">-- Choose --</option>
        <option value="allow">ALLOW</option>
        <option value="deny">DENY</option>
    </select>
</div>
<div class="form-group">
    <label for="content">Content</label>
    <input class="form-control" type="text" id="content" name="content" maxlength="30"/>
</div>

@section('scripts')

    @if(@isset($rule))
        <script>
            $(document).ready(function() {
                $('#rule_id').val('{{trim($rule->id)}}').attr('readonly', true).attr('disable', true);
                $('#priority').val('{{trim($rule->priority)}}');
                $('#name').val('{{trim($rule->name)}}');
                $('#source').val('{{trim($rule->source)}}');
                $('#destination').val('{{trim($rule->destination)}}');
                $('#direction').val('{{trim($rule->direction)}}');
                $('#protocol').val('{{trim($rule->protocol)}}');
                $('#start_port').val('{{trim($rule->start_port)}}');
                $('#end_port').val('{{trim($rule->end_port)}}');
                $('#action').val('{{trim($rule->action)}}');
                $('#content').val('{{trim($rule->content)}}');
            });
        </script>
    @else
        <script>
            $(document).ready(function() {
                $('#rule_id').parent().addClass('hide').attr('readonly', true).attr('disable', true);
            });
        </script>
    @endif

    <script>
        $(document).ready(function()
        {
            $('#rule-create').validate({
                rules: {
                    priority: {
                        required: true,
                        number: true,
                        range: [1, 99]
                    },
                    name: {
                        required: true,
                        minlength: 1,
                        maxlength:20
                    },
                    source: {
                        required: true
                    },
                    destination: {
                        required: true
                    },
                    direction: {
                        required: true
                    },
                    protocol: {
                        required:true
                    },
                    start_port: {
                        required: true,
                        minlength: 1,
                        maxlength: 5
                    },
                    end_port: {
                        minlength: 1,
                        maxlength: 5
                    },
                    action: {
                        required: true
                    },
                    content: {
                        required: true,
                        minlength: 1,
                        maxlenght: 30
                    }
                }
            });

            $('#rule-edit').validate({
                rules: {
                    priority: {
                        required: true,
                        number: true,
                        range: [1, 99]
                    },
                    name: {
                        required: true,
                        minlength: 1,
                        maxlength: 20
                    },
                    source: {
                        required: true
                    },
                    destination: {
                        required: true
                    },
                    start_port: {
                        required: true,
                        minlength: 1,
                        maxlength: 5
                    },
                    end_port: {
                        minlength: 1,
                        maxlength: 5
                    },
                    action: {
                        required: true
                    },
                    content: {
                        required: true,
                        minlength: 1,
                        maxlenght: 30
                    }
                }
            });

            setInterval(function() {
                if ($("#start_port").val() == '*') {
                    $("#end_port").val('').attr('readonly', true).attr('disable', true);
                    $( "#end_port" ).rules("remove", "minlength maxlength");
                } else {
                    $("#end_port").removeAttr('readonly').removeAttr('disable');
                    $( "#end_port" ).rules( "add", {
                        minlength: 1,
                        maxlength: 5
                    });
                }
            }, 500);

            //$('#priority').mask('99');

            /*$('#package_id').mask('9999');

            $('#source').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
                translation: {
                    'Z': {
                        pattern: /[0-9]/, optional: true
                    }
                }
            });

            $('#destination').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
                translation: {
                    'Z': {
                        pattern: /[0-9]/, optional: true
                    }
                }
            });

            $('#port').mask('9999');

            $('#data').mask('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', {
                translation: {
                    'A': {
                        pattern: /[\w\W\s]/, optional: true
                    }
                }
            });*/
        });
    </script>
@endsection