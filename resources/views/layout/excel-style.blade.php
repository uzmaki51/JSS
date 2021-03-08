<style>
    table, th, td {
        border: 1px solid #000000;
        border-collapse: collapse;
    }
    table thead tr th {
        background-color: #f1f0f1;
    }
    table tbody tr td{
        background-color: #ffffff;
    }
</style>
@if(isset($excel_title))
<div>{{ $excel_title }}</div>
@endif