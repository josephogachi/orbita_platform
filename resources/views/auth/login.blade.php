<x-guest-layout>
    <div class="flex min-h-[650px] w-full max-w-6xl overflow-hidden bg-white shadow-[0_20px_50px_rgba(0,26,65,0.1)] rounded-[3rem] border border-gray-100">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-orbita-blue p-16 text-white flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/login-bg.jpg') }}" class="w-full h-full object-cover opacity-30 mix-blend-overlay" alt="Security">
                <div class="absolute inset-0 bg-gradient-to-br from-orbita-blue via-orbita-blue/90 to-transparent"></div>
            </div>

            <div class="relative z-10">
                <x-application-logo mode="dark" class="w-20 h-20 !justify-start mb-10" />
                
                <h2 class="text-5xl font-black uppercase tracking-tighter leading-[0.9] mb-6">
                    Professional<br><span class="text-white/40">Security Access.</span>
                </h2>
                <div class="w-20 h-1.5 bg-white/20 rounded-full mb-10"></div>
            </div>

            <div class="relative z-10 space-y-8">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 text-white/50">Membership Benefits</h4>
                    <p class="text-sm text-white/80 leading-relaxed font-medium">
                        Create an account to track your orders, manage smart lock installations, and access exclusive Orbita support.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 text-white/50">Data Integrity</h4>
                    <p class="text-sm text-white/80 leading-relaxed font-medium">
                        Your credentials are protected by enterprise-grade encryption. We safeguard your data as strictly as your physical premises.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-10 sm:p-16 flex flex-col justify-center bg-white">
            <div class="mb-12 lg:hidden">
                <x-application-logo class="h-10 !justify-start" />
            </div>

            <div class="mb-10">
                <h3 class="text-3xl font-black text-orbita-blue uppercase tracking-tighter">Client Login</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.25em] mt-2">Authorized Personnel Only</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700 placeholder-gray-300" placeholder="Enter your email">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-orbita-blue transition" href="{{ route('password.request') }}">Recover?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required 
                           class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orbita-blue/10 transition-all text-sm font-bold text-gray-700" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" class="w-full py-5 bg-orbita-blue hover:bg-black text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-500 shadow-xl shadow-blue-900/10 hover:shadow-none">
                    Verify & Enter
                </button>
            </form>

            <div class="mt-10 relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                <div class="relative flex justify-center text-[9px] font-black uppercase tracking-[0.4em] text-gray-300"><span class="px-6 bg-white">Fast Access</span></div>
            </div>

            <div class="mt-8">
                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center py-5 border border-gray-100 rounded-2xl hover:bg-gray-50 transition-all duration-300 text-[10px] font-black uppercase tracking-widest text-gray-600 group">
                    <img class="h-5 w-5 mr-4 group-hover:scale-110 transition-transform" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    Continue with Google
                </a>
            </div>

            <p class="mt-12 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                New to the platform? <a href="{{ route('register') }}" class="text-orbita-blue font-black ml-1 hover:underline underline-offset-4">Register</a>
            </p>
        </div>
    </div>
</x-guest-layout>