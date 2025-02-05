<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <button type="button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>

                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex flex-shrink-0 items-center">
                    <a href="/accueil">
                        <img id="logo" class="h-8 w-auto" src="/public/img/logos/logo_sans_fond.png" alt="BibliOlen">
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:block">
                    <div class="flex space-x-4">
                        <a href="/accueil"
                           class="nav-link accueil text-gray-300 rounded-md px-3 py-2 text-sm font-medium">Accueil</a>
                        <a href="/livres/recherche"
                           class="nav-link livres text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Livres</a>
                        <a href="/historique"
                           class="nav-link historique text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Historique</a>
                        <a href="/inventaire?option=inventaire"
                           class="nav-link inventaire text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Inventaire</a>
                        <a href="/utilisateur/recherche"
                           class="nav-link utilisateur text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Utilisateurs</a>
                        <a href="/reservation"
                           class="nav-link reservation text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-sm font-medium">Réservations</a>
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                <div class="relative ml-3">
                    <div>
                        <button type="button"
                                class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                                onclick="toggleUserMenu()">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
<!--                            <img class="h-8 w-8 rounded-full" src="/public/img/user_icon_white.jpg" alt="">-->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="h-8 w-8 rounded-full text-white">
                                <path d="M18 20a6 6 0 0 0-12 0"/>
                                <circle cx="12" cy="10" r="4"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
                         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                         id="user-menu">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                           id="user-menu-item-2">Mon profil</a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                           id="user-menu-item-2">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sm:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="/accueil"
               class="nav-link accueil bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium">Accueil</a>
            <a href="/livres/recherche"
               class="nav-link livres text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Livres</a>
            <a href="/historique"
               class="nav-link historique text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Historique</a>
            <a href="/inventaire?option=inventaire"
               class="nav-link inventaire text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Inventaire</a>
            <a href="/utilisateur/recherche"
               class="nav-link utilisateur text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Utilisateurs</a>
            <a href="/reservation"
               class="nav-link reservation text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-sm font-medium">Réservations</a>
        </div>
    </div>
</nav>
