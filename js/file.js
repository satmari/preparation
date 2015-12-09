$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('Datatables.data') !!}',
        columns: [
            { data: 'po', name: 'po' },
            
        ]
    });
});