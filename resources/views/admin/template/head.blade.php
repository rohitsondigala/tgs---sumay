<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('PAGE_TITLE') | {{env('APP_NAME')}}</title>

    <!-- FONTS and ICONS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500"
          rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" rel="stylesheet" />

    <!-- SLEEK STYLE FILE -->
    <link id="sleek-css" rel="stylesheet" href="{{asset('/assets/css/sleek.min.css')}}" />
    <link id="sleek-css" rel="stylesheet" href="{{asset('/assets/css/custom.css')}}" />
    <link href="{{asset('/assets/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/plugins/data-tables/datatables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    @livewireStyles
</head>
