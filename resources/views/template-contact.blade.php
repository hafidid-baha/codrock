{{-- Template Name: Contact Template
  --}}

@extends('layouts.app')

@section('content')
    <!-- contact form -->
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-7">
                <form action={{ $_SERVER['PHP_SELF'] }} method="POST">
                    <div class="mb-3">
                        <input required type="text" class="form-control" name="email" placeholder="Email" />
                    </div>
                    <div class="mb-3">
                        <input required type="text" class="form-control" name="subject" placeholder="Subject" />
                    </div>
                    <div class="mb-3">
                        <textarea required class="form-control" placeholder="Message" name="message"></textarea>
                    </div>
                    <button type="submit" name="btn_save_contact_info" value="save_contact_info"
                        class="btn d-block text-center w-100 btn-light rounded-0 mb-3">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
