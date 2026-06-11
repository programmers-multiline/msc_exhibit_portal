<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Google Font -->
<!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"> -->
<link rel="stylesheet" href="{{ asset('css/font.css') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/eventlogo2.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('vendors/images/eventlogo2.png') }}">

<title>Exhibit Portal</title>

<link rel="stylesheet" href="{{ asset('vendors/images/eventlogo2.png') }}">
<link rel="stylesheet" href="{{ asset('vendors/images/eventlogo2.png') }}">
<link rel="stylesheet" href="{{ asset('vendors/images/eventlogo2.png') }}">

<link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/styles/style.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/styles/icon-font.min.css') }}">

<link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.min.css') }}">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->

<!-- <link rel="stylesheet" href="{{ asset('vendors/styles/pagination_style.css') }}"> -->

<link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">



<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">

<!-- DataTable JS -->
<!--  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->
 <script src="{{ asset('src/plugins/datatables/js/jquery-3.7.0.min.js') }}"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- <link rel="stylesheet" href="{{ asset('css/all.min.css') }}"> -->
<!--  <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}"> -->
<!--  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/> -->
<!--  <link rel="stylesheet" href="{{ asset('src/plugins/select2/dist/css/select2.min.css') }}"> -->





</head>

<body>
<!--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>   --> 

<script src="{{ asset('src/plugins/select2/dist/js/select2.full.min.js') }}"></script>

@include('layouts.header')
@include('layouts.sidebar')

<div class="main-container">
@yield('content')
@yield('scripts')
</div>

<script src="{{ asset('vendors/scripts/core.js') }}"></script>
<script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('vendors/scripts/process.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>

 <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>

<script src="{{ asset('vendors/scripts/datatable-setting.js') }}"></script>

<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- <script>
$(document).ready(function(){
    $('.data-table').DataTable();
});
</script> -->



</body>
</html>