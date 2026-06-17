<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Himatech – Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-900 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-900">⚙️ Himatech</h1>
            <p class="text-gray-500 mt-2">Plateforme de suivi des dépenses chantiers</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="votre@email.ma">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="••••••••">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition">
                Se connecter
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">© 2024 Himatech – Tous droits réservés</p>
    </div>
</body>
</html>
