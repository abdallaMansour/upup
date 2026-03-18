@props(['stage', 'isPrivate' => false, 'expiresAt' => null])

<nav class="top-navbar">
    <div class="container-fluid px-3">
        <div class="navbar-inner">
            <div class="nav-right">
                <button class="lang-btn" id="langToggleBtn" onclick="toggleLanguage()" data-switch-to-ar="ع" data-switch-to-en="EN">EN</button>
            </div>
            @if ($isPrivate)
                <div class="nav-left">
                    @if ($expiresAt)
                        <span class="expiry-badge" data-content-ar="تنتهي الصلاحية: {{ $expiresAt }}" data-content-en="Expires: {{ $expiresAt }}">{{ __('profile.expires_at') }}: {{ $expiresAt }}</span>
                    @endif
                    <a href="{{ route('profile.logout', $stage) }}" class="btn-logout">
                        <i class="fas fa-sign-out-alt me-1"></i><span data-content-ar="تسجيل الخروج" data-content-en="Logout">{{ __('profile.logout') }}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
