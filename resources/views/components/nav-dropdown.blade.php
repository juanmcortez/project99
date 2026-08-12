@props(['user'])

@php
    $user->loadMissing('demographic.phones', 'demographic.address');

    $displayName = $user->demographic?->full_name ?: $user->username;
    $profilePicture = $user->demographic?->profile_picture;
    $phones = $user->demographic?->phones ?? collect();
    $address = $user->demographic?->address;

    $canViewSettings = $user->can('activity-log.view')
        || $user->can('users.manage')
        || $user->can('roles.manage')
        || $user->can('permissions.manage');

    $canViewRolesConfig = $user->can('roles.manage') || $user->can('permissions.manage');

    $navLinkClass = fn (bool $active) => $active
        ? 'block rounded-md px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50'
        : 'block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900';

    $accordionButtonClass = 'flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900';
@endphp

<div class="relative" data-nav-dropdown>
    <button
        type="button"
        class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        data-nav-dropdown-trigger
        aria-expanded="false"
        aria-haspopup="true"
    >
        <span class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-200 text-gray-500">
            @if ($profilePicture)
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profilePicture) }}"
                    alt="{{ $displayName }}"
                    class="h-full w-full object-cover"
                />
            @else
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12 2.25a5.25 5.25 0 1 0 0 10.5 5.25 5.25 0 0 0 0-10.5ZM2.25 20.25a9.75 9.75 0 0 1 19.5 0v.75H2.25v-.75Z" clip-rule="evenodd" />
                </svg>
            @endif
        </span>
        <span class="hidden max-w-[10rem] truncate font-medium text-gray-900 sm:inline">{{ $displayName }}</span>
        <svg class="h-4 w-4 text-gray-500" data-nav-dropdown-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        class="absolute right-0 z-50 mt-2 hidden w-64 origin-top-right rounded-md border border-gray-200 bg-white py-1 shadow-lg focus:outline-none sm:w-72"
        data-nav-dropdown-panel
        role="menu"
    >
        <div class="px-4 py-3">
            <p class="truncate text-xs font-medium text-gray-900">{{ $user->username }}</p>
            <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
            @foreach ($phones as $phone)
                <p class="truncate text-xs text-gray-500">{{ $phone->type->label() }}: {{ $phone->phone_number }}</p>
            @endforeach
        </div>

        <div class="border-t border-gray-100"></div>

        @if ($address)
            <div class="px-4 py-2 text-xs leading-relaxed text-gray-500">
                {!! nl2br(e($address->formatted)) !!}
            </div>

            <div class="border-t border-gray-100"></div>
        @endif

        <div class="px-1 py-1">
            <a href="{{ route('profile.edit') }}" class="{{ $navLinkClass(request()->routeIs('profile.*')) }}" role="menuitem">
                My profile
            </a>
        </div>

        @if ($canViewSettings)
            <div class="border-t border-gray-100"></div>

            <div class="px-1 py-1">
                <button
                    type="button"
                    class="{{ $accordionButtonClass }}"
                    data-nav-accordion-trigger
                    data-nav-accordion-target="settings"
                    aria-expanded="false"
                >
                    <span>Settings</span>
                    <svg class="h-4 w-4 shrink-0 transition-transform duration-200" data-nav-accordion-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="hidden space-y-0.5 pl-2" data-nav-accordion-panel="settings">
                    @can('activity-log.view')
                        <a href="{{ route('activity-log.index') }}" class="{{ $navLinkClass(request()->routeIs('activity-log.*')) }}" role="menuitem">
                            Activity log
                        </a>
                    @endcan

                    @can('users.manage')
                        <a href="{{ route('admin.users.index') }}" class="{{ $navLinkClass(request()->routeIs('admin.users.*')) }}" role="menuitem">
                            Users roles
                        </a>
                    @endcan

                    @if ($canViewRolesConfig)
                        <button
                            type="button"
                            class="{{ $accordionButtonClass }}"
                            data-nav-accordion-trigger
                            data-nav-accordion-target="roles-config"
                            aria-expanded="false"
                        >
                            <span>Roles config</span>
                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200" data-nav-accordion-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div class="hidden space-y-0.5 pl-2" data-nav-accordion-panel="roles-config">
                            @can('permissions.manage')
                                <a href="{{ route('admin.permissions.index') }}" class="{{ $navLinkClass(request()->routeIs('admin.permissions.*')) }}" role="menuitem">
                                    Permissions
                                </a>
                            @endcan

                            @can('roles.manage')
                                <a href="{{ route('admin.roles.index') }}" class="{{ $navLinkClass(request()->routeIs('admin.roles.*')) }}" role="menuitem">
                                    Roles
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="border-t border-gray-100"></div>

        <div class="px-1 py-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full rounded-md px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>
