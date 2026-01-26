<x-guest-layout>
    <div class="flex min-h-[700px] w-full max-w-6xl overflow-hidden bg-white shadow-[0_20px_50px_rgba(0,26,65,0.1)] rounded-[3rem] border border-gray-100">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-orbita-blue p-16 text-white flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/login-bg.jpg') }}" class="w-full h-full object-cover opacity-30 mix-blend-overlay" alt="Security Infrastructure">
                <div class="absolute inset-0 bg-gradient-to-br from-orbita-blue via-orbita-blue/90 to-transparent"></div>
            </div>

            <div class="relative z-10">
                <x-application-logo mode="dark" class="w-20 h-20 !justify-start mb-10" />
                
                <h2 class="text-5xl font-black uppercase tracking-tighter leading-[0.9] mb-6">
                    Join the<br><span class="text-white/40">Secure Circle.</span>
                </h2>
                <div class="w-20 h-1.5 bg-white/20 rounded-full mb-10"></div>
            </div>

            <div class="relative z-10 space-y-8">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 text-white/50">Why Register?</h4>
                    <p class="text-sm text-white/80 leading-relaxed font-medium">
                        Your account is your gateway to precision security. Manage your **smart lock installations**, download **digital invoices**, and track your **Orbita hardware** orders in real-time.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 text-white/50">Enterprise Protection</h4>
                    <p class="text-sm text-white/80 leading-relaxed font-medium">
                        We apply the same rigorous standards to your digital profile as we do to our physical security products. Your privacy is our priority.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-10 sm:p-16 flex flex-col justify-center bg-white">
            <div class="mb-12 lg:hidden">
                <x-application-logo class="h-10 !justify-start" />
            </div>

            <div class="mb-10">
                <h3 class="text-3xl font-black text-orbita-blue uppercase tracking-tighter">Create Profile</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.25em] mt-2">Start your journey with Orbita Kenya</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700 placeholder-gray-300" placeholder="Joseph ogachi">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700 placeholder-gray-300" placeholder="email@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Secure Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-orbita-blue hover:bg-black text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-500 shadow-xl shadow-blue-900/10 hover:shadow-none">
                        Register Account
                    </button>
                </div>
            </form>

            <div class="mt-10 relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                <div class="relative flex justify-center text-[9px] font-black uppercase tracking-[0.4em] text-gray-300"><span class="px-6 bg-white">Social Sign Up</span></div>
            </div>

            <div class="mt-8">
                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center py-5 border border-gray-100 rounded-2xl hover:bg-gray-50 transition-all duration-300 text-[10px] font-black uppercase tracking-widest text-gray-600 group">
                    <img class="h-5 w-5 mr-4 group-hover:scale-110 transition-transform" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    Register with Google
                </a>
            </div>

            <p class="mt-12 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Already have an account? <a href="{{ route('login') }}" class="text-orbita-blue font-black ml-1 hover:underline underline-offset-4">Sign In</a>
            </p>
        </div>
    </div>
</x-guest-layout>