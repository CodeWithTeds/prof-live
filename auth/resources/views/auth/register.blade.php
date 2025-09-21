<x-layouts.app :title="'Register'">
    <div class="bg-white shadow-xl ring-1 ring-black/5 rounded-2x p-6">
        <h1 class="tex-2xl font-semibold mb-6">Create an account</h1>

        <form id="login-form" action="" class="space-y-4">

            <div>
                <label class="block text-sm font-medium mb-1" for="">Name</label>
                <input type="text" name="name" class="" id="">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="">Email</label>
                <input type="email" name="email" id="">
            </div>
            <div>
                <label for="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" id="">
            </div>

            <div>
                <label for="block text-sm font-medium mb-1">Confirm Password</label>
                <input type="text" name="password_confirmation" id="">
            </div>

            <button type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                Sign in
            </button>
            <p class="text-sm text-neutral-600">Already have an account</p>
        </form>

    </div>
</x-layouts.app>
