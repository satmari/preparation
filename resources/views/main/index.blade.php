@extends('app')

@section('content')

<table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Id</th>
                
            </tr>
        </thead>
    </table>


@endsection

@push('scripts')
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
            { data: 'id', name: 'id' }
        ]
    });
});
</script>
@endpush