
@foreach($childs as $child)
    <option style="color:black" value="{{$child->id}}">
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{ $child->name }}
        @if(count($child->childs))
            @include('manageChild',['childs' => $child->childs])
        @endif
    </option>
@endforeach



{{--<ul>--}}
{{--    @foreach($childs as $child)--}}
{{--        <li>--}}
{{--            {{ $child->name }}--}}
{{--            @if(count($child->childs))--}}
{{--                @include('manageChild',['childs' => $child->childs])--}}
{{--            @endif--}}
{{--        </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}
