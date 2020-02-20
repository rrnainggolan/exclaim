<ul class="nav nav-pills flex-column">
  @can('approve-claims')
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('expense-claims.active') ? 'active' : '' }}" 
        href="{{ route('expense-claims.active') }}">
        Active Claims
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('expense-claims.completed') ? 'active' : '' }}" 
        href="{{ route('expense-claims.completed') }}">
        Completed Claims
      </a>
    </li>
  @endcan
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs(['expense-claims.index', 'home']) ? 'active' : '' }}" 
    href="{{ route('expense-claims.index') }}">
      My Claims
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('expense-claims.create') ? 'active' : '' }}" 
    href="{{ route('expense-claims.create') }}">
      Request New Claim
    </a>
  </li>
</ul>