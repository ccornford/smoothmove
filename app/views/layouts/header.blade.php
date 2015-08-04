<div class="header-navigation-wrapper">
    <a href="{{ URL::route('page.home') }}" class="header-logo">
        {{ HTML::image('assets/images/logo-light.svg', 'Smooth Move logo') }}
    </a>
    <div class="header-rightside">
      <a href="javascript:void(0)" class="header-navigation-menu-button" id="js-mobile-menu"></a>
      <div class="header-search">
        {{ Form::open(array('route' => 'property.search', 'role' => 'search', 'method' => 'get')) }}

              {{ Form::text('s', Input::old('s'), array('placeholder'=>'e.g. Newcastle or NE1')) }}

              {{ Form::submit('') }}
              <span class="icon-search"></span>

          {{ Form::close() }}
      </div><!-- END .header-search -->
      <nav role="navigation">
          <ul id="js-navigation-menu" class="nav-navigation-menu show">
              <li><a href="/#how-it-works">How it works</a></li>
              @if (Sentry::check())
                @if (Sentry::getUser()->hasAccess('manager'))
                  <li><a href="{{ URL::route('dashboard.show') }}">Dashboard @if($countQuestions) <div class="notif">{{$countQuestions}}</div> @endif</a></li>
                @endif
                <li><a href="{{ URL::route('account.edit')}}">Account @if($countAnswers) <div class="notif">{{$countAnswers}}</div> @endif</a></li>
                <li><a href="{{ URL::route('logout.do')}}">Log out</a></li>
              @else
                <li><a href="/#sign-up">Sign up</a></li>
                <li><a href="{{ URL::route('login.show') }}">Log in</a></li>
              @endif
          </ul>
      </nav>
    </div><!-- END .header-rightside -->
</div><!-- END .header-navigation-wrapper -->