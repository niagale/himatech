@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<!-- En-tête avec animation -->
<div class="mb-8 animate-slideIn">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tableau de bord</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-chart-pie text-indigo-500 text-sm"></i>
                Vue globale des dépenses – {{ date('d/m/Y') }}
            </p>
        </div>
        <div class="hidden md:block">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <i class="fas fa-sync-alt text-indigo-400 animate-spin-slow"></i>
                <span>Mis à jour en temps réel</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres sans émojis -->
<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 mb-8 border border-white/50 animate-fadeInUp" style="animation-delay: 0.05s">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <div class="w-1 h-5 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-full"></div>
            <i class="fas fa-sliders-h text-indigo-500"></i> Filtrer les données
        </h3>
        <div class="text-xs text-gray-400">
            <i class="fas fa-mouse-pointer"></i> Filtres dynamiques
        </div>
    </div>
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-hard-hat text-gray-400 mr-1"></i> Chantier</label>
            <select name="chantier_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/50">
                <option value="">Tous les chantiers</option>
                @foreach($chantiers as $c)
                <option value="{{ $c->id }}" {{ request('chantier_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Date début</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/50">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Date fin</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/50">
        </div>
        <div class="flex items-end gap-3">
            <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-search text-xs"></i> Appliquer
            </button>
            <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2 border border-gray-200">
                <i class="fas fa-undo-alt text-xs"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- KPI Cards avec compteurs animés -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-3d animate-fadeInUp" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Dépenses</p>
                <p class="text-3xl font-bold text-gray-800">
                    <span x-data="{ count: 0 }" x-init="setTimeout(() => { count = {{ $totalDepenses }} }, 300)" x-text="new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(count)">0</span>
                    <span class="text-sm font-normal text-gray-400">MAD</span>
                </p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fas fa-receipt text-indigo-400"></i>
                    {{ $nombreDepenses }} dépenses
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-3d animate-fadeInUp" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Budget Total</p>
                <p class="text-3xl font-bold text-gray-800">
                    <span x-data="{ count: 0 }" x-init="setTimeout(() => { count = {{ $totalBudget }} }, 400)" x-text="new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(count)">0</span>
                    <span class="text-sm font-normal text-gray-400">MAD</span>
                </p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fas fa-hard-hat text-emerald-500"></i>
                    {{ $nombreChantiers }} chantiers
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-coins text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-3d animate-fadeInUp" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Budget Restant</p>
                <p class="text-3xl font-bold {{ $budgetRestant < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    <span x-data="{ count: 0 }" x-init="setTimeout(() => { count = {{ $budgetRestant }} }, 500)" x-text="new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(count)">0</span>
                    <span class="text-sm font-normal text-gray-400">MAD</span>
                </p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fas fa-percent text-amber-500"></i>
                    {{ $pourcentageConsomme ?? 0 }}% consommé
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-piggy-bank text-white text-xl"></i>
            </div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-3d animate-fadeInUp" style="animation-delay: 0.25s">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Fournisseurs</p>
                <p class="text-3xl font-bold text-gray-800">
                    <span x-data="{ count: 0 }" x-init="setTimeout(() => { count = {{ $topFournisseurs->count() }} }, 600)" x-text="count">0</span>
                    <span class="text-sm font-normal text-gray-400">actifs</span>
                </p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fas fa-truck text-purple-500"></i>
                    partenaires
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-truck text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques premium -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fadeInUp card-3d" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white text-sm"></i>
                </div>
                Dépenses vs Budget
            </h3>
            <div class="text-xs text-gray-400">Par chantier</div>
        </div>
        <canvas id="chantierChart" height="220"></canvas>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fadeInUp card-3d" style="animation-delay: 0.35s">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
                Évolution mensuelle
            </h3>
            <div class="text-xs text-gray-400">{{ date('Y') }}</div>
        </div>
        <canvas id="mensuelChart" height="220"></canvas>
    </div>
</div>

<!-- Top fournisseurs + Chantiers critiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fadeInUp card-3d" style="animation-delay: 0.4s">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-medal text-white text-sm"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Top Fournisseurs</h3>
        </div>
        <div class="space-y-4">
            @foreach($topFournisseurs as $index => $f)
            <div class="group">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <span class="w-6 text-center">
                            @if($index == 0) <i class="fas fa-crown text-yellow-500"></i>
                            @elseif($index == 1) <i class="fas fa-medal text-gray-400"></i>
                            @elseif($index == 2) <i class="fas fa-medal text-amber-600"></i>
                            @else <span class="text-xs text-gray-400">#{{ $index+1 }}</span>
                            @endif
                        </span>
                        {{ $f->nom }}
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ number_format($f->depenses_sum_montant ?? 0, 2, ',', ' ') }} MAD</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full transition-all duration-500 group-hover:opacity-80" style="width: {{ $topFournisseurs->first()->depenses_sum_montant > 0 ? round(($f->depenses_sum_montant ?? 0) / $topFournisseurs->first()->depenses_sum_montant * 100) : 0 }}%; background: linear-gradient(90deg, #6366f1, #8b5cf6);"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fadeInUp card-3d" style="animation-delay: 0.45s">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Chantiers critiques</h3>
        </div>
        @forelse($chantiersCritiques as $c)
        <div class="group relative overflow-hidden bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-4 mb-3 border border-red-100 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $c['nom'] }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($c['pourcentage'], 100) }}%"></div>
                        </div>
                        <p class="text-xs font-bold text-red-600">{{ $c['pourcentage'] }}%</p>
                    </div>
                </div>
                <a href="{{ route('chantiers.show', \App\Models\Chantier::where('nom', $c['nom'])->first()->id ?? 1) }}" class="text-indigo-600 hover:text-indigo-800 transition ml-3">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm">Tous les chantiers sont dans les limites budgétaires</p>
            <p class="text-xs text-gray-400 mt-1">Parfait !</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Résumé des filtres -->
@if(request('chantier_id') || request('date_debut') || request('date_fin'))
<div class="mt-6 bg-indigo-50/50 backdrop-blur-sm rounded-xl p-4 border border-indigo-100 animate-fadeInUp" style="animation-delay: 0.5s">
    <p class="text-sm text-indigo-700 flex items-center gap-2">
        <i class="fas fa-filter text-indigo-500"></i>
        <span class="font-medium">Filtres actifs :</span>
        @if(request('chantier_id')) <span class="bg-indigo-100 px-2 py-0.5 rounded-full text-xs">Chantier filtré</span> @endif
        @if(request('date_debut')) <span class="bg-indigo-100 px-2 py-0.5 rounded-full text-xs">Depuis le {{ request('date_debut') }}</span> @endif
        @if(request('date_fin')) <span class="bg-indigo-100 px-2 py-0.5 rounded-full text-xs">Jusqu'au {{ request('date_fin') }}</span> @endif
    </p>
</div>
@endif
@endsection

@section('scripts')
<script>
// Dépenses par chantier
const chantierData = @json($depensesParChantier);
new Chart(document.getElementById('chantierChart'), {
    type: 'bar',
    data: {
        labels: chantierData.map(c => c.nom.substring(0, 20)),
        datasets: [
            { 
                label: 'Dépenses', 
                data: chantierData.map(c => c.total), 
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderRadius: 8,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            },
            { 
                label: 'Budget', 
                data: chantierData.map(c => c.budget), 
                backgroundColor: 'rgba(229, 231, 235, 0.8)',
                borderRadius: 8,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }
        ]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: true,
        plugins: { 
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } },
            tooltip: { backgroundColor: '#1f2937', titleColor: '#f3f4f6', bodyColor: '#d1d5db', padding: 10, cornerRadius: 8 }
        },
        scales: { 
            y: { 
                beginAtZero: true, 
                grid: { color: '#e5e7eb', drawBorder: false },
                title: { display: true, text: 'Montant (MAD)', color: '#6b7280', font: { size: 11 } }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Mensuel
const moisLabels = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const mensuelData = Array(12).fill(0);
@foreach($depensesMensuelles as $m)
    mensuelData[{{ $m->mois - 1 }}] = {{ $m->total }};
@endforeach

new Chart(document.getElementById('mensuelChart'), {
    type: 'line',
    data: {
        labels: moisLabels,
        datasets: [{ 
            label: 'Dépenses', 
            data: mensuelData, 
            borderColor: '#10b981', 
            backgroundColor: 'rgba(16,185,129,0.08)', 
            fill: true, 
            tension: 0.4,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            borderWidth: 2.5
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: true,
        plugins: { 
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } },
            tooltip: { backgroundColor: '#1f2937', titleColor: '#f3f4f6', bodyColor: '#d1d5db', padding: 10, cornerRadius: 8 }
        },
        scales: { 
            y: { 
                beginAtZero: true, 
                grid: { color: '#e5e7eb', drawBorder: false },
                title: { display: true, text: 'Montant (MAD)', color: '#6b7280', font: { size: 11 } }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endsection