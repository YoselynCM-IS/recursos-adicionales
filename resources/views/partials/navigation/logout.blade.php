<li class="nav-item">
    <a class="nav-link" href="{{ route('logout') }}" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa fa-unlock"></i> {{ __('Cerrar sesión') }}
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>