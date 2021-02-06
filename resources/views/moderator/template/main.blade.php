@include('moderator.template.head')
<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
@include('moderator.template.sidebar')
<div class="page-wrapper">
@include('moderator.template.header')
@yield('content')
</body>
@include('moderator.template.footer')
