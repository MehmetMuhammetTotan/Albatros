@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close font__size-18" data-dismiss="alert">
            <span  aria-hidden="true"><i class="fa fa-times alertprimary"></i></span>
            <span class="sr-only">Kapat</span>
        </button>
        <div class="row">
            <div class="col-auto d-flex justify-content-center align-items-center">
                <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_j6fywzxe.json"  background="transparent"  speed="1"  style="width: 55px; height: 55px"    autoplay loop></lottie-player>
            </div>
            <div class="col-auto">
        <h5><strong><u>Başarısız!</u></strong></h5>
        Lütfen aşağıdaki hataları düzeltin.<br />
        @foreach ($errors->all() as $error)
            <span>* {{ $error }}</span><br />
        @endforeach
            </div>
        </div>

    </div>

@endif


@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close font__size-18" data-dismiss="alert">
            <span  aria-hidden="true"><i class="fa fa-times alertprimary"></i></span>
            <span class="sr-only">Kapat</span>
        </button>
        <div class="row">
            <div class="col-auto d-flex justify-content-center align-items-center">
                <lottie-player src="https://assets1.lottiefiles.com/datafiles/8UjWgBkqvEF5jNoFcXV4sdJ6PXpS6DwF7cK4tzpi/Check Mark Success/Check Mark Success Data.json"  background="transparent"  speed="1"  style="width: 55px; height: 55px;"    autoplay></lottie-player>
            </div>
            <div class="col-auto">
                <h5><strong><u>Başarılı!</u></strong></h5>
                {{ session('success') }}
            </div>
        </div>

    </div>
@endif


