@extends('landingpage.navigation.navigation')

@section('content')
<style>
    form{
        width: 320px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-radius: 10px; 
        background-color: #eaeaea;
        margin: auto;
    }
</style>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <img src="{{ asset('img/sample.png') }}" style="margin-left: 2%" class="mb-5 mt-1">
                <form action="{{ route('login.check') }}" method="POST" style="padding: 38px;"> 
                    @csrf     
                    <h1 class="mb-3 mt-3 text-center"><b>LOGIN</b></h1>
                    <div class="form-outline mb-4">
                        @if($errors->any())
                            <div class="alert alert-danger text-center" role="alert">
                                {{$errors->first()}}
                            </div>
                        @endif
                        <label class="title-detail">Email</label>
                        <input type="text" class="form-control " name="email" placeholder="Enter email address" value="{{ old('email') }}" required/>
                
                    </div>
                    <div class="form-outline mb-3">
                        <label class="title-detail">Password</label>
                        <input type="password" class="form-control " id="password" name="password" placeholder="Enter password" required/>
                    </div>
                    <div style="display: flex">
                        <input type='checkbox' onclick="myFunction()" id='show-password'/>
                        <p style="margin-bottom: -1px; "class="p-1"> Show password</p>
                    </div>  
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" class="btn btn-primary" style="padding-left: 2.5rem; padding-right: 2.5rem;">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            }else {
                x.type = "password";
            }
        }
    </script>

@endsection