<x-admin-layout>
    @include('admin.components.FlashMessage')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Create Client</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.client.list') }}"><i
                                class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.client.list')}}">Client Account</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Client</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="p-4 border rounded">
                        <form action="{{ route('admin.client.store') }}" method="post" class="row g-3 needs-validation"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="validationServer01" class="form-label">Client name<span class="text-danger">*</span></label>
                                <input type="text" name="client_name" class="form-control" id=""
                                    value="{{ old('client_name') }}" placeholder="Enter Full Name">
                                <div class="valid-feedback">Looks good!</div>
                                @if ($errors->has('client_name'))
                                    <div class="text-danger">{{ $errors->first('client_name') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom03" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    id="validationCustom03">
                                <div class="invalid-feedback">Please provide your email.</div>
                                @if ($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom03" class="form-label">Mobile</label>
                                <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control"
                                    id="validationCustom03">
                                <div class="invalid-feedback">Please provide your Mobile.</div>
                                @if ($errors->has('mobile'))
                                    <div class="text-danger">{{ $errors->first('mobile') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom03" class="form-label">Skype</label>
                                <input type="text" name="skype" value="{{ old('skype') }}" class="form-control"
                                    id="validationCustom03">
                                <div class="invalid-feedback">Please provide your Skype.</div>
                                @if ($errors->has('skype'))
                                    <div class="text-danger">{{ $errors->first('skype') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom03" class="form-label">LinkedIn</label>
                                <input type="text" name="linkedin" value="{{ old('linkedin') }}" class="form-control"
                                    id="validationCustom03">
                                <div class="invalid-feedback">Please provide your Linked In.</div>
                                @if ($errors->has('linkedin'))
                                    <div class="text-danger">{{ $errors->first('linkedin') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom03" class="form-label">Other</label>
                                <input type="text" name="other" value="{{ old('other') }}" class="form-control"
                                    id="validationCustom03">
                                <div class="invalid-feedback">Please provide Other.</div>
                                @if ($errors->has('other'))
                                    <div class="text-danger">{{ $errors->first('other') }}</div>
                                @endif
                            </div>
                            
                            <div class="col-md-12">
                                <label for="validationServer01" class="form-label">Location</label>
                                <textarea name="location" id="location" class="form-control" cols="30" rows="2">{{old('location')}}</textarea>
                               
                                <div class="valid-feedback">Looks good!</div>
                                @if ($errors->has('location'))
                                    <div class="text-danger">{{ $errors->first('location') }}</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="row row-cols-auto g-3">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success px-5 radius-30">Create</button>
                                    </div>

                                    <div class="col">
                                        <a href="{{route('admin.client.list')}}" class="btn btn-outline-success px-5 radius-30">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>

        </div>
    </div>


    @push('scripts')
        <script>
        </script>
    @endpush
</x-admin-layout>
