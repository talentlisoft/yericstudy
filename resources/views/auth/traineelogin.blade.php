@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">学员登陆</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('traineelogin') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="traineename" class="col-md-4 col-form-label text-md-right">学员名称</label>
                            <div class="col-md-6">
                                <input id="traineename" type="text" class="form-control @error('traineename') is-invalid @enderror" name="traineename" value="{{ old('traineename') }}" required autofocus>
                                @error('traineename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">登陆密码</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputcaptcha" class="col-md-4 col-form-label text-md-right">验证码</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="inputcaptcha" class="form-control  @error('captcha') is-invalid @enderror" name="captcha" required>
                                        @error('captcha')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        {!! captcha_img('flat') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    登陆
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
