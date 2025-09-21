<x-layouts.app :title="'Register'">
    <div class="bg-white shadow-xl ring-1 ring-black/5 rounded-2x p-6">
        <h1 class="tex-2xl font-semibold mb-6">Create an account</h1>

        <form id="register-form" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1" for="register-name">Name</label>
                <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" id="register-name" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="register-email">Email</label>
                <input type="email" name="email" id="register-email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="register-password">Password</label>
                <input type="password" name="password" id="register-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="register-password-confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="register-password-confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <button type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                Register
            </button>
            <p class="text-sm text-neutral-600">Already have an account? <a href="/login" class="text-indigo-600 hover:text-indigo-500">Login here</a></p>
        </form>

    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const password = formData.get('password');
            const passwordConfirmation = formData.get('password_confirmation');

            // Check if passwords match
            if (password !== passwordConfirmation) {
                alert('Passwords do not match!');
                return;
            }

            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: password,
                password_confirmation: passwordConfirmation
            };

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    // Store the token
                    localStorage.setItem('auth_token', result.token);
                    alert('Registration successful!');
                    // Redirect to dashboard or home page
                    window.location.href = '/dashboard';
                } else {
                    alert('Registration failed: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during registration');
            }
        });
    </script>
</x-layouts.app>
