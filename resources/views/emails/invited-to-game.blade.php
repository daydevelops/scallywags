@component('mail::message')
# Introduction

You have been invited to game {{$game->id}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
