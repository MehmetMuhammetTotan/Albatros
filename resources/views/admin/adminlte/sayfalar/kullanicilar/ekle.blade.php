@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.kullanicilar").' - '.config("const.ekle").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.kullanici")}} {{config("const.ekle")}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.kullanicilar.get")}}" class="btn btn-info"><i class="fa fa-users"></i> {{config("const.kullanicilar")}}</a>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @include('admin.adminlte.widgets.alert')
                </div>


                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-plus"></i> {{config("const.yeni")}} {{config("const.kullanici")}} {{config("const.ekle")}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- form start -->
                        <form id="yeniKullaniciEkle" method="post"
                              action="{{route("admin.kullanicilar_ekle.post")}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Ad Soyad</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="name" class="form-control" id="forName"
                                               placeholder="Ad?? Soyad??" value="{{old("name")}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control" id="forEmail"
                                               placeholder="Email Adresi" value="{{old("email")}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forPassword">??ifre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control" id="forPassword"
                                               placeholder="??ifre" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forStatus">Durum</label>
                                    <div class="input-group">
                                        <input id="forStatus" type="checkbox" @if(old("aktiflik")) checked @endif name="aktiflik" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{config("const.kaydet")}}</button>
                            </div>
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- jquery-validation -->
    <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <script>
        $(function () {

            $('form').on('submit', function(event) {
                // Formda hala ge??erli olan validation kurallar??n?? kontrol edin
                if ($(this).valid()) {
                    // Ge??erli ise form submit i??lemini ger??ekle??tirin
                    var submitButton = $(this).find('button[type="submit"]');
                    submitButton.prop("disabled", true);
                    submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{config("const.kaydediliyor")}}');
                }
            });
            $('#yeniKullaniciEkle').validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    email: {
                        required: "Email adresi zorunludur",
                        email: "L??tfen ge??erli bir email adresi giriniz"
                    },
                    password: {
                        required: "??ifre zorunludur",
                        minlength: "??ifreniz en az 6 karakter uzunlu??unda olmal??d??r"
                    },
                    name: {
                        required: "Ad Soyad zorunludur",
                        minlength: "Ad??n??z ve soyad??n??z en az 3 karakter uzunlu??unda olmal??d??r"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

        })
    </script>
@endsection

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection
