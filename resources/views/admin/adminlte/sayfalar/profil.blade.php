@extends('admin.adminlte.partials.master')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                     <h1>Profil</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route("admin.index.get")}}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active">Profil</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    @include('admin.adminlte.widgets.alert')
                </div>
                <div class="col-md-6 col-sm-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-pen mr-1"></i> Bilgilerimi Güncelle</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="bilgileriGuncelle" method="post"
                              action="{{route("admin.profil_bilgi_guncelle.post")}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Ad Soyad</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="name" class="form-control" id="forName"
                                               placeholder="Adınız Soyadınız"
                                               value="{{ old("name", auth()->guard('admin')->user()->name) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                    <input type="email" name="email" class="form-control" id="forEmail"
                                           placeholder="Email Adresiniz"
                                           value="{{old("email", auth()->guard('admin')->user()->email)}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forPassword">Mevcut Şifre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                    <input type="password" name="password" class="form-control" id="forPassword"
                                           placeholder="Mevcut Şifreniz" required>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Kaydet</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-sm-12">
                    <!-- jquery validation -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-shield mr-1"></i> Şifremi Güncelle</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="sifreDegistir" method="post" action="{{route('admin.sifremi_guncelle.post')}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forPasswordOld">Mevcut Şifre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                    <input type="password" name="password_old" class="form-control" id="forPasswordOld"
                                           placeholder="Mevcut Şifreniz">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forNewPassword">Yeni Şifre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-lock"></i></span>
                                        </div>
                                    <input type="password" name="password" class="form-control" id="forNewPassword"
                                           placeholder="Yeni Şifreniz">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forNewPasswordConfirm">Yeni Şifre Tekrarı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-lock"></i></span>
                                        </div>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           id="forNewPasswordConfirm" placeholder="Yeni Şifre Tekrarınız">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Kaydet</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    <!-- jquery-validation -->
    <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <script>
        $('form').on('submit', function(event) {
            // Formda hala geçerli olan validation kurallarını kontrol edin
            if ($(this).valid()) {
                // Geçerli ise form submit işlemini gerçekleştirin
                var submitButton = $(this).find('button[type="submit"]');
                submitButton.prop("disabled", true);
                submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Kaydediliyor...');
            }
        });
        $('#bilgileriGuncelle').validate({
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
                    required: "Adınızı ve soyadınızı giriniz",
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

        $('#sifreDegistir').validate({
            rules: {
                password_old: {
                    required: true,
                    minlength: 6,
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: '#forNewPassword'
                }
            },
            messages: {
                password_old: {
                    required: "Mevcut Şifre zorunludur",
                    minlength: "Şifreniz en az 6 karakter uzunluğunda olmalıdır"
                },
                password: {
                    required: "Yeni Şifre zorunludur",
                    minlength: "Şifreniz en az 6 karakter uzunluğunda olmalıdır",
                },
                password_confirmation: {
                    required: "Yeni Şifre Tekrarı zorunludur",
                    minlength: "Şifreniz en az 6 karakter uzunluğunda olmalıdır",
                    equalTo: "Yeni Şifreniz ile uyuşmuyor"
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

    </script>
@endsection
