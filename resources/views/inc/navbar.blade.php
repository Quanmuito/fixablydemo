<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{__('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('orders.index', 1) }}>All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('orders.search') }}>Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('orders.create')}}>Create</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('invoices.invoices') }}>List invoices</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>