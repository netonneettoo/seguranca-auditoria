<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
    <label for="package_id">Id</label>
    <input class="form-control" type="number" step="1" min="1" max="9999" id="package_id" name="package_id" required autofocus="autofocus"/>
</div>
<div class="form-group">
    <label for="source">Source</label>
    <input class="form-control" type="text" id="source" name="source" maxlength="15" required/>
</div>
<div class="form-group">
    <label for="destination">Destination</label>
    <input class="form-control" type="text" id="destination" name="destination" maxlength="15" required/>
</div>
<div class="form-group">
    <label for="port">Port</label>
    <input class="form-control" type="number" step="1" min="1" max="9999" id="port" name="port" required/>
</div>
<div class="form-group">
    <label for="protocol">Protocol</label>
    <select class="form-control" id="protocol" name="protocol">
        <option value="">-- Choose --</option>
        <option value="tcp">TCP</option>
        <option value="udp">UDP</option>
        <option value="icmp">ICMP</option>
    </select>
</div>
<div class="form-group">
    <label for="data">Data</label>
    <input class="form-control" type="text" id="data" name="data" maxlength="50" required/>
</div>

@section('scripts')

    @if(@isset($package))
        <script>
            $(document).ready(function() {
                $('#package_id').val('{{trim($package->package_id)}}');
                $('#source').val('{{trim($package->source)}}');
                $('#destination').val('{{trim($package->destination)}}');
                $('#port').val('{{trim($package->port)}}');
                $('#protocol').val('{{trim($package->protocol)}}');
                $('#data').val('{{trim($package->data)}}');
            });
        </script>
    @endif

    <script>
        $(document).ready(function()
        {
            $('#package_id').mask('9999');

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
            });
        });
    </script>
@endsection