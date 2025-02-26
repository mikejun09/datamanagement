@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">
       
        <div class="col">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 mt-5" >
                        <div class="card" style="box-shadow: 0px 0 30px rgba(0, 0, 0, 0.1);
                                                background-color: #ffffff5e;
                                                border-radius: 5px;">
                            <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <label class="espc">ADD NEW USER</label>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            
                            <div class="card-body">
                                <form action="{{ route('add_user') }}" method="POST">
                                    @csrf
            
            
                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="name" required autofocus>
            
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                   
            
                                 
            
                                    <div class="row mb-0 justify-content-center">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Add') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection