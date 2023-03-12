@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.site_ayarlari").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.site_ayarlari")}}</h1>
                </div>
                <div class="col-sm-6">

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
                            <h3 class="card-title"><i class="fas fa-cog mr-1"></i> {{config("const.temel_bilgiler")}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="temelBilgilerGuncelle" method="post"
                              action="{{route("admin.site_ayarlari_temel_bilgiler.post")}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Site Adı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                        </div>
                                        <input type="text" name="site_adi" class="form-control" id="forName"
                                               placeholder="Site Adı"
                                               value="{{ old("site_adi", siteAyarlariCek()["site_adi"])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Site Açıklaması</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                        </div>
                                        <input type="text" name="site_aciklama" class="form-control" id="forEmail"
                                               placeholder="Site Açıklaması"
                                               value="{{old("site_aciklama", siteAyarlariCek()["site_aciklama"])}}">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button @if(@kullanicininYetkileri()["site_ayarlari"]["temel_bilgiler"] == "on") class="btn  ml-2 btn-success" type="submit" @else type="button" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-success not-allowed" @endif><i class="fas fa-save"></i> {{config("const.kaydet")}}</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-sm-12">
                    <!-- jquery validation -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-image mr-1"></i> Site Resimleri</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="siteResimleriGuncelle" method="post" action="{{route('admin.site_ayarlari_site_resimleri.post')}}"  enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputFile">Logo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" accept=".png,.jpg,.jpeg" name="logo" class="custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Dosya seçiniz</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile2">Favicon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" accept=".ico" name="favicon" class="custom-file-input" id="exampleInputFile2">
                                            <label class="custom-file-label" for="exampleInputFile2">Dosya seçiniz</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button @if(@kullanicininYetkileri()["site_ayarlari"]["site_resimleri"] == "on") class="btn  ml-2 btn-success" type="submit" @else type="button" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-success not-allowed" @endif><i class="fas fa-save"></i> {{config("const.kaydet")}}</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-sm-12">
                    <!-- jquery validation -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-address-book mr-1"></i> İletişim Bilgileri</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="iletisimBilgileriGuncelle" method="post" action="{{route('admin.site_ayarlari_iletisim_bilgileri.post')}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Telefon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" name="telefon" class="form-control" id="forName"
                                               placeholder="Telefon"
                                               value="{{ old("telefon", siteAyarlariCek()["telefon"])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">E-Mail</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control" id="forEmail"
                                               placeholder="E-Mail"
                                               value="{{old("email", siteAyarlariCek()["email"])}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="forAdres">Adres</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                        </div>
                                        <input type="text" name="adres" class="form-control" id="forAdres"
                                               placeholder="Adres"
                                               value="{{old("adres", siteAyarlariCek()["adres"])}}">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button @if(@kullanicininYetkileri()["site_ayarlari"]["iletisim_bilgileri"] == "on") class="btn  ml-2 btn-success" type="submit" @else type="button" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-success not-allowed" @endif><i class="fas fa-save"></i> {{config("const.kaydet")}}</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-sm-12">
                    <!-- jquery validation -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-address-book mr-1"></i> Sosyal Medya</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="sosyalMedyaGuncelle" method="post" action="{{route('admin.site_ayarlari_sosyal_medya.post')}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Facebook</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        </div>
                                        <input type="text" name="facebook" class="form-control" id="forName"
                                               placeholder="Facebook"
                                               value="{{ old("facebook", siteAyarlariCek()["facebook"])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Instagram</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input type="text" name="instagram" class="form-control" id="forEmail"
                                               placeholder="Instagram"
                                               value="{{old("instagram", siteAyarlariCek()["instagram"])}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="forEmail2">Twitter</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        </div>
                                        <input type="text" name="twitter" class="form-control" id="forEmail2"
                                               placeholder="Twitter"
                                               value="{{old("twitter", siteAyarlariCek()["twitter"])}}">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button @if(@kullanicininYetkileri()["site_ayarlari"]["sosyal_medya"] == "on") class="btn  ml-2 btn-success" type="submit" @else type="button" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-success not-allowed" @endif><i class="fas fa-save"></i> {{config("const.kaydet")}}</button>
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
                submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{config("const.kaydediliyor")}}');
            }
        });
        $('#sosyalMedyaGuncelle').validate({
            rules: {
                facebook: {
                    url: true
                },
                instagram: {
                    url: true
                },
                twitter: {
                    url: true
                },
            },
            messages: {
                facebook: {
                    url: "Lütfen geçerli bir Facebook adresi giriniz"
                },
                instagram: {
                    url: "Lütfen geçerli bir Instagram adresi giriniz"
                },
                twitter: {
                    url: "Lütfen geçerli bir Twitter adresi giriniz"
                },
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
        $('#iletisimBilgileriGuncelle').validate({
            rules: {
                telefon: {
                    required: true,
                    minlength: 3
                },
                adres: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    minlength: 3,
                    email: true,
                },
            },
            messages: {
                telefon: {
                    required: "Telefon giriniz",
                    minlength: "Telefon en az 3 karakter uzunluğunda olmalıdır"
                },
                adres: {
                    required: "Adres giriniz",
                    minlength: "Adres en az 3 karakter uzunluğunda olmalıdır"
                },
                email: {
                    required: "Email giriniz",
                    email: "Email formatı hatalı",
                    minlength: "Email en az 3 karakter uzunluğunda olmalıdır"
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
        $('#temelBilgilerGuncelle').validate({
            rules: {
                site_adi: {
                    required: true,
                    minlength: 3
                },
                site_aciklama: {
                    required: true,
                    minlength: 3
                },
            },
            messages: {
                site_adi: {
                    required: "Site adı giriniz",
                    minlength: "Site adı en az 3 karakter uzunluğunda olmalıdır"
                },
                site_aciklama: {
                    required: "Site açıklama giriniz",
                    minlength: "Site açıklama en az 3 karakter uzunluğunda olmalıdır"
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

        $('#siteResimleriGuncelle').validate({
            rules: {
                logo: {
                    accept: "image/png,image/jpeg,image/jpg",
                    maxsize: 1024 * 1024 * 10, // 10 MB
                },
                favicon: {
                    accept: "image/x-icon",
                    maxsize: 1024 * 1024 * 10, // 10 MB
                },
            },
            messages: {
                logo: {
                    accept: "Resim dosyası seçiniz. (.png, .jpg, .jpeg)",
                    maxsize: "Resim dosyası 10 MB'dan büyük olamaz"
                },
                favicon: {
                    accept: "Resim dosyası seçiniz. (.ico)",
                    maxsize: "Resim dosyası 10 MB'dan büyük olamaz"
                },
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
