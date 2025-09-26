<x-layouts.app :title="'login'">
    <div class="bg-white shadow-xl ring-1 ring-black/5 rounded-2x p-6">
        <h1 class="tex-2xl font-semibold mb-6">Sign in</h1>

        <form id="login-form" class="space-y-4" method="POST" action="/login">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1" for="login-email">Email</label>
                <input type="email" name="email" id="login-email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="login-password">Password</label>
                <input type="password" name="password" id="login-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
            </div>
            <button type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                Sign in
            </button>
            <p class="text-sm text-neutral-600">Don't have an account? <a href="/register"
                    class="text-indigo-600 hover:text-indigo-500">Register here</a></p>
        </form>
    </div>

    <script>
    </script>
</x-layouts.app>
