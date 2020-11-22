@foreach ($notifications as $index=>$notification)
    {{ $index + 1 }}) {{$notification['text']}} Click <a href = "{{ $notification['href'] }}">here</a> to view them now.<br>
    @if( isset($notification['detailLines']) )
        @foreach ( $notification['detailLines'] as $detail )
            <p style="margin-left:10px">{!! $detail !!}</p>
        @endforeach
    @endif
@endforeach

<p>Powered by: <a href="https://www.evolutionmarketing.com">Evolution Marketing</a></p>
