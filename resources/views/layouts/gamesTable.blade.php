<table id='ugames-table' class='table'>
    <thead>
        <tr>
            <th scope='col'>Date</th>
            <th scope='col'>Competition</th>
            <th scope='col'>public/private</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($games as $g)
            <tr class='{{$g->isPlaying()?"my-game":""}}'>
                <td scope='col' class='game-date'>{{$g->gamedate}}</td>
                <td scope='col' class='game-comp'>
                    @if (sizeof($g->users)>1)
                        {{$g->users[0]->name}} and {{sizeof($g->users)-1}} other
                    @else
                        {{$g->users[0]->name}}
                    @endif
                </td>
                <td scope='col' class='game-type {{$g->private?"":"public-game"}}'>
                    {{$g->private?"private":"public"}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
