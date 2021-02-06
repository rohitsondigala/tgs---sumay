@include('admin.template.head')
<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
@include('admin.template.sidebar')
<div class="page-wrapper">
@include('admin.template.header')
@yield('content')
</body>
@include('admin.template.footer')
