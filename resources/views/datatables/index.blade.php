@extends('app')

@section('content')
    <table class="table display row-border table-striped" id="users-table">
        <thead>
            <tr>
                <th colspan="7" style="background-color:#eef;border-top:1px solid;">Production Order</th>
                <th colspan="4" style="background-color:#efe;border-top:1px solid;">Barcode</th>
                
            </tr>
            <tr>
                <!-- <th>Id</th> -->
                <!-- <th>Po size</th> -->
                <!-- <th>Order code</th> -->
                <th style="background-color:#eef;">Po</th>
                <th style="background-color:#eef;">Size</th>
                <th style="background-color:#eef;">Style</th>
                <th style="background-color:#eef;">Color</th>
                <th style="background-color:#eef;">Color desc</th>
                <th style="background-color:#eef;">Season</th>
                <th style="background-color:#eef;">Total Order Qty</th>
                <th style="background-color:#efe;">B. printed</th>
                <th style="background-color:#efe;">B. to print</th>
                <th style="background-color:#efe;">B. on stock</th>
                <th style="background-color:#efe;">B. in modules</th>
                <!-- <th>Closed</th> -->
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
                <th>B. Printed</th>
                <th>Still to print</th>
                <th>C. Printed</th>
                <th>Still to print</th>
                <!-- <th>Closed</th> -->
                <!-- <th>Created</th> -->
                <!-- <th>Modified</th> -->
            </tr>
        </thead>


        
    </table>
@endsection
