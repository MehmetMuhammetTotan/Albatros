@extends('admin.adminlte.partials.master')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kullanıcı Ekle</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.kullanicilar.get")}}" class="btn btn-info"><i class="fa fa-users"></i> Kullanıcılar</a>
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
                            <h3 class="card-title"><i class="fas fa-user-plus"></i> Yeni Kullanıcı Ekle</h3>
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
                                               placeholder="Adı Soyadı" value="{{old("name")}}">
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
                                    <label for="forPassword">Şifre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control" id="forPassword"
                                               placeholder="Şifre" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forStatus">Durum</label>
                                    <div class="input-group">
                                        <input id="forStatus" type="checkbox" @if(old("aktiflik")) checked @endif name="aktiflik" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Kaydet</button>
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
                // Formda hala geçerli olan validation kurallarını kontrol edin
                if ($(this).valid()) {
                    // Geçerli ise form submit işlemini gerçekleştirin
                    var submitButton = $(this).find('button[type="submit"]');
                    submitButton.prop("disabled", true);
                    submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Kaydediliyor...');
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
                        email: "Lütfen geçerli bir email adresi giriniz"
                    },
                    password: {
                        required: "Şifre zorunludur",
                        minlength: "Şifreniz en az 6 karakter uzunluğunda olmalıdır"
                    },
                    name: {
                        required: "Ad Soyad zorunludur",
                        minlength: "Adınız ve soyadınız en az 3 karakter uzunluğunda olmalıdır"
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
