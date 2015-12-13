@extends('app')

@section('content')
    <table class="table display row-border table-striped" id="users-table">
        <thead>
            <tr>
                <th colspan="7">Production Order</th>
                <th colspan="2">Barcode</th>
                <th colspan="2">Carelabel</th>
            </tr>
            <tr>
                <!-- <th>Id</th> -->
                <!-- <th>Po size</th> -->
                <!-- <th>Order code</th> -->
                <th>Po</th>
                <th>Size</th>
                <th>Style</th>
                <th>Color</th>
                <th>Color desc</th>
                <th>Season</th>
                <th>Total order qty</th>
                <th>Stock Qty</th>
                <th>Total-Stock Qty</th>
                <th>Flash</th>
                <th>Closed</th>
                <!-- <th>Created</th> -->
                <!-- <th>Modified</th> -->
            </tr>
        </thead>
        <tfoot>
            <tr>
                <!-- <th>Id</th> -->
                <!-- <th>Po size</th> -->
                <!-- <th>Order code</th> -->
                <th>Po</th>
                <th>Size</th>
                <th>Style</th>
                <th>Color</th>
                <th>Color desc</th>
                <th>Season</th>
                <th>Total order qty</th>
                <th>Stock Qty</th>
                <th>Total-Stock Qty</th>
                <th>Flash</th>
                <th>Closed</th>
                <!-- <th>Created</th> -->
                <!-- <th>Modified</th> -->
            </tr>
        </thead>

        
    </table>
@endsection
