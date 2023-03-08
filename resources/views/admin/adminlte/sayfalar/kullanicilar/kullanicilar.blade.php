@extends('admin.adminlte.partials.master')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kullanıcılar</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a @if(@kullanicininYetkileri()["kullanicilar"]["ekle"] == "on") class="btn  ml-2 btn-outline-info" href="{{route("admin.kullanicilar_ekle.get")}}" @else href="#" title="YETKİNİZ YOK" class="btn  ml-2 btn-outline-info not-allowed" @endif><i class="fa fa-plus"></i> Kullanıcı Ekle</a>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Kullanıcı Listesi</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ad Soyad</th>
                                    <th>Email</th>
                                    <th>Durum</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($kullanicilar as $kullanici)

                                    <tr>
                                        <td>{{$kullanici->id}}</td>
                                        <td>{{$kullanici->name}}</td>
                                        <td>{{$kullanici->email}}</td>
                                        <td><input type="checkbox" @if($kullanici->id == auth()->guard('admin')->user()->id) disabled @endif name="my-checkbox" @if($kullanici->status) checked @endif data-id="{{$kullanici->id}}" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success"></td>
                                        <td>{{date("Y-m-d", strtotime($kullanici->created_at))}}</td>
                                        <td><a @if(@kullanicininYetkileri()["kullanicilar"]["yetkiler"] == "on") class="btn  ml-2 btn-outline-info" href="{{route("admin.kullanicilar_yetkiler.get", ["id" => $kullanici->id])}}" @else href="#" title="YETKİNİZ YOK" class="btn  ml-2 btn-outline-info not-allowed" @endif><i class="fa fa-lock"></i> Yetkiler</a><a @if(@kullanicininYetkileri()["kullanicilar"]["duzenle"] == "on") class="btn  ml-2 btn-outline-warning" href="{{route("admin.kullanicilar_duzenle.get", ["id" => $kullanici->id])}}" @else href="#" title="YETKİNİZ YOK" class="btn  ml-2 btn-outline-warning not-allowed" @endif><i class="fas fa-pen"></i> Düzenle</a><a @if(@kullanicininYetkileri()["kullanicilar"]["sil"] == "on") class="btn  ml-2 btn-outline-danger btnSil" href="{{route("admin.kullanicilar_sil.get", ["id" => $kullanici->id])}}" @else href="#" title="YETKİNİZ YOK" class="btn  ml-2 btn-outline-danger not-allowed" @endif><i class="fas fa-trash"></i> Sil</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Ad Soyad</th>
                                    <th>Email</th>
                                    <th>Durum</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script>
    $(function () {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[ 0, 'desc' ]]
        });
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', switchChange);
        function switchChange(event, state) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $.ajax({
                url: "{{route("admin.kullanicilar_aktif_pasif.post")}}",
                type: "POST",
                data: {
                    id: $(this).data("id"),
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                    if(response.status){
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                    }else{
                        event.bootstrapSwitch('state', false);
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                }
            });
        }
    });
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection
