<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Himatech – @yield('title', 'Suivi des dépenses')</title>
    
    <!-- Tailwind CSS + Alpine.js + Font Awesome + Chart.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Police moderne -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-dark {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Animations premium */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            50% { box-shadow: 0 0 0 20px rgba(99, 102, 241, 0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
        .animate-pulseGlow { animation: pulseGlow 1.5s infinite; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-countUp { animation: countUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Card 3D hover */
        .card-3d {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            transform-style: preserve-3d;
            perspective: 800px;
        }
        .card-3d:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.2), 0 8px 16px -6px rgba(99, 102, 241, 0.08);
        }
        
        /* Sidebar */
        .sidebar-transition {
            transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .main-transition {
            transition: margin-left 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .menu-item {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .menu-item.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.18), rgba(139, 92, 246, 0.08));
            border-left: 3px solid #6366f1;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .menu-item:hover:not(.active) {
            background: rgba(99, 102, 241, 0.08);
            transform: translateX(4px);
        }
        
        /* Tooltip */
        .tooltip {
            position: relative;
        }
        .tooltip .tooltip-text {
            visibility: hidden;
            opacity: 0;
            width: 80px;
            background: #1f2937;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 4px 8px;
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%) scale(0.9);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            font-size: 10px;
            font-weight: 500;
            pointer-events: none;
            white-space: nowrap;
        }
        .tooltip .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #1f2937;
        }
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            transform: translateX(-50%) scale(1);
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #818cf8; }
        
        /* Toast */
        .toast {
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-50 to-indigo-50/30" 
      x-data="{ sidebarOpen: true, pageLoading: true }" 
      x-init="setTimeout(() => pageLoading = false, 800)">

<!-- Skeleton loader -->
<div x-show="pageLoading" x-cloak class="fixed inset-0 bg-white z-50 flex items-center justify-center">
    <div class="text-center">
        <div class="relative w-32 h-32 mx-auto mb-6">
            <div class="absolute bottom-0 left-0 w-16 h-16 animate-float">
                <i class="fas fa-truck-pickup text-4xl text-indigo-500"></i>
            </div>
            <div class="absolute top-0 right-0" style="animation: swing 1.5s ease-in-out infinite;">
                <i class="fas fa-hard-hat text-4xl text-yellow-500"></i>
            </div>
            <div class="absolute bottom-0 right-0 animate-float" style="animation-delay: 0.5s;">
                <i class="fas fa-shovel text-3xl text-emerald-500"></i>
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 animate-spin-slow">
                <i class="fas fa-cog text-5xl text-indigo-400"></i>
            </div>
        </div>
        <div class="flex items-center justify-center gap-2 mb-3">
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-ping" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-ping" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-ping" style="animation-delay: 0.4s"></div>
        </div>
        <p class="text-gray-700 font-semibold text-lg">Chargement...</p>
        <p class="text-xs text-gray-400 mt-1">Préparation de votre espace de travail</p>
    </div>
</div>

<div x-show="!pageLoading" x-cloak class="relative min-h-screen">
    
    <!-- Sidebar Overlay (mobile) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside 
        x-show="sidebarOpen"
        x-transition:enter="transition-transform duration-400 ease-out"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-400 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full z-30 sidebar-transition shadow-2xl glass-dark"
        :class="sidebarOpen ? 'w-64' : 'w-20'">
        
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-5 border-b border-white/10 flex items-center justify-between">
                <div x-show="sidebarOpen" x-transition class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg animate-pulseGlow">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg tracking-tight">Himatech</h1>
                        <p class="text-indigo-300 text-[10px] tracking-wide font-medium">SUIVI DES DÉPENSES</p>
                    </div>
                </div>
                <div x-show="!sidebarOpen" x-transition class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto shadow-lg">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active text-white' : '' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-chart-pie w-5 text-lg"></i>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Tableau de bord</span>
                </a>
                
                <a href="{{ route('chantiers.index') }}" 
                   class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('chantiers.*') ? 'active text-white' : '' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-hard-hat w-5 text-lg"></i>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Chantiers</span>
                </a>
                
                <a href="{{ route('depenses.index') }}" 
                   class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('depenses.*') ? 'active text-white' : '' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-file-invoice-dollar w-5 text-lg"></i>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Dépenses</span>
                </a>
                
                <a href="{{ route('bon-commandes.index') }}" 
                   class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('bon-commandes.*') ? 'active text-white' : '' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-file-signature w-5 text-lg"></i>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Bons de commande</span>
                </a>
                
                <a href="{{ route('fournisseurs.index') }}" 
                   class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('fournisseurs.*') ? 'active text-white' : '' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-truck w-5 text-lg"></i>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Fournisseurs</span>
                </a>
                
                @if(auth()->user()->isAdmin())
                <div class="pt-3 mt-2 border-t border-white/10">
                    <a href="{{ route('users.index') }}" 
                       class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('users.*') ? 'active text-white' : '' }}"
                       :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                        <i class="fas fa-users w-5 text-lg"></i>
                        <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Utilisateurs</span>
                    </a>
                    
                    <a href="{{ route('logs.index') }}" 
                       class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('logs.index') ? 'active text-white' : '' }}"
                       :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                        <i class="fas fa-history w-5 text-lg"></i>
                        <span x-show="sidebarOpen" x-transition class="text-sm font-medium">Historique</span>
                    </a>
                </div>
                @endif
            </nav>
            
            <!-- Footer sidebar -->
            <div class="p-4 border-t border-white/10">
                <div x-show="sidebarOpen" x-transition>
                    <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-white/5 hover:bg-white/10 transition">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-indigo-300 text-xs capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-indigo-300 hover:text-red-400 transition-all duration-200 hover:scale-110">
                                <i class="fas fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div x-show="!sidebarOpen" x-transition class="flex justify-center">
                    <div class="relative group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-all duration-300">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap pointer-events-none shadow-lg">
                            {{ auth()->user()->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="min-h-screen transition-all duration-400"
          :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'"
          style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
        
        <!-- Header glassmorphism -->
        <header class="sticky top-0 z-20 glass border-b border-white/30 shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Bouton toggle sidebar -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="group w-10 h-10 rounded-xl bg-white/60 hover:bg-white transition-all duration-300 flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 active:scale-95 border border-white/30"
                            :title="sidebarOpen ? 'Masquer le menu' : 'Afficher le menu'">
                        <i class="fas fa-bars text-gray-700 text-lg transition-all duration-300 group-hover:rotate-90"></i>
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Tableau de bord')</h2>
                        <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block animate-pulse"></span>
                            Bienvenue, {{ auth()->user()->name }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 shadow-sm">
                        <i class="fas fa-calendar-alt text-gray-500 text-sm"></i>
                        <span class="text-sm text-gray-600 font-medium">{{ date('d F Y') }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-md flex items-center justify-center animate-pulseGlow">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="p-6 animate-fadeIn">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="fixed top-20 right-6 z-50 toast bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                <i class="fas fa-circle-check text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-white/80 hover:text-white transition-all duration-200 hover:scale-110">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
            
            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="fixed top-20 right-6 z-50 toast bg-gradient-to-r from-red-500 to-rose-500 text-white px-5 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                <i class="fas fa-circle-exclamation text-xl"></i>
                <span class="font-medium">{{ $errors->first() }}</span>
                <button @click="show = false" class="ml-4 text-white/80 hover:text-white transition-all duration-200 hover:scale-110">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 3s linear infinite;
    }
    @keyframes swing {
        0%, 100% { transform: rotate(-5deg); }
        50% { transform: rotate(5deg); }
    }
    .animate-ping {
        animation: ping 1.2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    .transition-400 {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🏗️ Himatech - Plateforme de suivi des dépenses');
    });
</script>

@yield('scripts')
</body>
</html>