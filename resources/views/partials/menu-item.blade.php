


@if ($item['submenu'] == [])
<li class="nav-item">
  @if(strtoupper($item['name'])=='CONECTITY'||strtoupper($item['name'])=='TOOL-PC')
    <a href="{{ url($item['slug']) }}" class="nav-link" download>
      @else
    <a href="{{ url($item['slug']) }}" class="nav-link">

      @endif

      <i class="nav-icon fas fa-copy"></i>
      <p>
        {{ strtoupper($item['name']) }}
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
</li>

@else
    <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-copy"></i>
      <p>
        {{ strtoupper($item['name']) }}
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
	 @foreach ($item['submenu'] as $submenu)
     @if ($submenu['submenu'] == [])
      <li class="nav-item">
      @if(strtoupper($submenu['name'])=='CONECTITY'||strtoupper($submenu['name'])=='TOOL-PC')
        <a href="{{ url($submenu['slug']) }}" class="nav-link " download>
        @else
        <a href="{{ url($submenu['slug']) }}" class="nav-link" style="padding-top: 5px;padding-bottom: 5px;">
        @endif
          <table width="100%">
            <tr>
              <td width="10%"><i class="far fa-circle nav-icon"></i></td>
              <td><p>{{ strtoupper($submenu['name']) }}</p></td>
          </tr></table>
        </a>
      </li>
      @else
        @include('partials.menu-item', [ 'item' => $submenu,'varta'=>0 ])
      @endif
    @endforeach
    </ul>
  </li>
        

@endif