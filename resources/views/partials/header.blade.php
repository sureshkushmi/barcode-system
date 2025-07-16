<!-- Navbar -->
<nav class="app-header navbar navbar-expand bg-body">
  <!--begin::Container-->
  <div class="container-fluid">
    <!-- Start navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
            @php
                $active = request()->routeIs('superadmin.dashboard') ? 'active' : '';
            @endphp

            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ $active }}">Dashboard</a>
            @else
                <a href="{{ route('users.dashboard') }}" class="nav-link {{ $active }}">Dashboard</a>
            @endif
      </li>

      <li class="nav-item d-none d-md-block">
          <a href="{{ route('profile.edit') }}" class="nav-link">Profile</a>
      </li>

    </ul>
    <!-- End navbar links -->

    <ul class="navbar-nav ms-auto">
                <!-- Google Translate Language Dropdown -->
                <li class="nav-item dropdown">
            <a
              href="#"
              id="languageDropdown"
              role="button"
              class="nav-link d-flex align-items-center"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="bi bi-globe me-1" style="font-size: 1.2rem;"></i>
              <span class="d-none d-md-inline">Language</span>
              <i class="bi bi-caret-down-fill ms-1"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="languageDropdown" style="min-width: 180px;">
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('en')">
    🇺🇸 <span class="ms-2">English</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('es')">
    🇪🇸 <span class="ms-2">Spanish</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('zh-CN')">
    🇨🇳 <span class="ms-2">Chinese</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('tl')">
    🇵🇭 <span class="ms-2">Tagalog</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('vi')">
    🇻🇳 <span class="ms-2">Vietnamese</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('fr')">
    🇫🇷 <span class="ms-2">French</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('ar')">
    🇸🇦 <span class="ms-2">Arabic</span>
  </button>
  <button class="dropdown-item d-flex align-items-center" onclick="changeLanguage('ko')">
    🇰🇷 <span class="ms-2">Korean</span>
  </button>
</div>

          </li>

        <!-- Messagesss ====================== -->
  
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" id="messageDropdown">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger" id="unread-count">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" id="message-list">
          <span class="dropdown-item dropdown-header">Messages</span>
          <div id="message-items"></div>
          <div class="dropdown-divider"></div>
          <a href="{{ route('users.messages.index') }}" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
       <!-- end messages ====================== -->
      <li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <!-- User icon -->
        <i class="bi bi-person-circle me-1"></i> <!-- user icon -->

        <!-- Username + dropdown arrow together -->
        <span class="d-none d-md-inline">
            {{ Auth::user()->name }}
            <i class="bi bi-caret-down-fill ms-1"></i> <!-- dropdown arrow -->
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!-- Menu Footer -->
        <li class="user-footer d-flex justify-content-between px-3 py-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">
                <i class="fas fa-user-cog me-1"></i> Profile
            </a>
            <form id="logout-form" action="{{ route('logoutusers') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-default btn-flat">
                    <i class="fas fa-sign-out-alt me-1"></i> Sign out
                </button>
            </form>
        </li>
    </ul>
</li>

    </ul>
  </div>
  <!--end::Container-->
</nav>
<!-- /.navbar -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  function loadUnreadMessages() {
    $.get('{{ url("/messages/unread") }}', function (data) {
      const count = data.count;

      // Update count badge
      if (count > 0) {
        $('#unread-count').text(count).show();
      } else {
        $('#unread-count').text('0').hide();
      }

      // Update message items
      let html = '';

      if (data.messages.length === 0) {
        html = `<div class="dropdown-item text-center text-muted">No new messages</div>`;
      } else {
        data.messages.forEach(msg => {
          html += `
            <a href="/public/messages/conversation/${msg.sender.id}" class="dropdown-item message-link" data-sender-id="${msg.sender.id}">
              <div class="d-flex">
                
                <div class="flex-grow-1 ms-2">
                  <h3 class="dropdown-item-title">${msg.sender.name}</h3>
                  <p class="fs-7">${msg.message.slice(0, 40)}...</p>
                  <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> ${new Date(msg.created_at).toLocaleString()}</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
          `;
        });
      }

      $('#message-items').html(html);
    }).fail(function () {
      console.error('Failed to fetch unread messages.');
    });
  }

  // Initial load when DOM is ready
  loadUnreadMessages();

  // Refresh every 10 seconds
  setInterval(loadUnreadMessages, 10000);

  $('#messageDropdown').on('shown.bs.dropdown', function () {
    setTimeout(function () {
      $.post('{{ url("/messages/mark-all-read") }}', {
        _token: '{{ csrf_token() }}'
      })
      .done(function () {
        $('#unread-count').text('0').hide();
      })
      .fail(function (xhr) {
        console.error('Error:', xhr.responseText);
      });
    }, 5000); // wait 5 second before marking as read
  });


});
</script>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement(
      {
        pageLanguage: 'en',
        autoDisplay: false
      },
      'google_translate_element'
    );
  }

  function changeLanguage(lang) {
    const select = document.querySelector("select.goog-te-combo");
    if (select) {
      select.value = lang;
      select.dispatchEvent(new Event("change"));
    }
  }
</script>

<!-- Hidden translate widget (needed for the script to work) -->
<div id="google_translate_element" style="display: none;"></div>

<!-- Google Translate Script -->
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>

