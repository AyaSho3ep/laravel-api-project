@component('mail::message')

    <h1>We have received a request to reset your account password</h1>


            Your six-digit CODE is {{ $code->code }}

    The allowed duration of the code is one hour from the time this message was received.
    
    Do not share your One time code With Anyone. The allowed duration of the code is one hour from the time this message was received.<br>

    Please discard if this wasn't you.


    Thanks,<br>

        {{ config('app.name') }}

@endcomponent
