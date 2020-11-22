@if( isset($toAdmin) && $toAdmin == true )
    {{ $clientName }} successfully signed the contract. Click <a href = "{{ url('proposal-list?type=signed') }}">here</a> to view.<br><br>
@else
    Here is the link to your proposal: <a href = "{{ url($url) }}">{{ url(urlencode($url)) }}</a><br><br>
@endif

    <p>Powered by: <a href="https://www.evolutionmarketing.com">Evolution Marketing</a></p>
