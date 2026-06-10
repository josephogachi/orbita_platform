@extends('layouts.public')

@section('content')

{{-- HERO SECTION --}}
<div class="relative bg-orbita-blue py-24 md:py-32 overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10" 
         style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 text-center z-10">
        <span class="text-orbita-gold text-[10px] font-black uppercase tracking-[0.3em] mb-4 block">
            Authorized Distributor Program
        </span>
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-6">
            Partner With <span class="text-orbita-gold">Orbita Kenya</span>
        </h1>
        <p class="text-gray-300 max-w-2xl mx-auto text-lg leading-relaxed font-medium">
            Join Kenya's premier hospitality technology brand. Secure exclusive territorial rights, access deep wholesale pricing, and build a highly profitable enterprise in your region.
        </p>
    </div>
</div>

{{-- INTERACTIVE ONBOARDING FLOW --}}
<section class="py-16 md:py-24 bg-orbita-light" x-data="{ agreed: {{ session('success') || $errors->any() ? 'true' : 'false' }}, step: 1 }">
    <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
        
        {{-- SUCCESS BANNER (Moved outside so it's always visible!) --}}
        @if(session('success'))
        <div class="mb-10 bg-green-50 border-2 border-green-200 rounded-2xl p-6 md:p-8 text-center shadow-xl animate-fade-in-down">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-2xl md:text-3xl font-black text-green-800 mb-2 uppercase tracking-wide">Application Received!</h3>
            <p class="text-green-700 font-medium md:text-lg">{{ session('success') }}</p>
        </div>
        @endif

        {{-- VALIDATION ERRORS BANNER --}}
        @if($errors->any())
        <div class="mb-10 bg-red-50 border-2 border-red-200 rounded-2xl p-6 md:p-8 text-center shadow-xl animate-fade-in-down">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-red-800 mb-2 uppercase tracking-wide">Oops! Something is missing.</h3>
            <div class="text-red-700 font-medium mb-4">Please fix the following errors and submit again:</div>
            <ul class="text-red-600 text-sm md:text-base font-bold list-none space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
       {{-- 1. About Orbita & The Partnership Model --}}
            <div class="mb-16 md:mb-20 border-b border-gray-100 pb-16 md:pb-20">
                <div class="text-center mb-10 md:mb-14">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">
                        The <span class="text-orbita-gold">Orbita</span> Advantage
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-12 items-center">
                    {{-- Left Column: Who We Are --}}
                    <div>
                        <h3 class="text-lg md:text-xl font-black uppercase tracking-widest text-gray-900 mb-4 flex items-center gap-3">
                            <span class="w-8 h-1 bg-orbita-gold rounded-full"></span> Who We Are
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:text-lg">
                            Orbita Kenya is the undisputed leader in premium hospitality appliances and smart security technology. We engineer, supply, and support enterprise-grade solutions including <strong>Smart Hotel RFID Locks, Hotel Room Safe Boxes, Minibars, Electric Kettles, Hair Dryers, Hotel Phones, and Smart Room Switches</strong>.
                        </p>
                        <p class="text-gray-600 leading-relaxed md:text-lg">
                            Our technology secures and elevates the guest experience in the region's most prestigious properties. <em>(We invite you to view our extensive Client Portfolio on our Projects page to see the caliber of hotels that trust Orbita).</em>
                        </p>
                    </div>

                    {{-- Right Column: The Business Model --}}
                    <div class="bg-gray-50 p-8 md:p-10 rounded-[2rem] border border-gray-200 shadow-sm relative">
                        <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                            <svg class="w-24 h-24 text-orbita-blue" fill="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg md:text-xl font-black uppercase tracking-widest text-orbita-blue mb-4 relative z-10">The Partnership Model</h3>
                        
                        <div class="space-y-4 relative z-10 text-sm md:text-base text-gray-600 leading-relaxed">
                            <p>
                                We are actively seeking elite, technically capable local businesses to become our exclusive hands on the ground. It is important to note that <strong>this is not a commission-based affiliate program</strong>—it is a true B2B wholesale partnership.
                            </p>
                            <p>
                                As an authorized regional distributor, you will purchase our enterprise-grade systems directly from Orbita Kenya at <strong>deeply discounted wholesale prices</strong>. You are then empowered to price, sell, and install these solutions to your local clients, securing your own highly lucrative profit margins.
                            </p>
                            
                            {{-- Brand Guidelines Callout Box --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 mt-6 shadow-sm">
                                <h4 class="font-black text-gray-900 mb-2 flex items-center gap-2 uppercase tracking-wide text-xs md:text-sm">
                                    <svg class="w-5 h-5 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Brand Protection & Pricing Guidelines
                                </h4>
                                <p class="text-xs md:text-sm text-gray-500 leading-relaxed">
                                    To protect the integrity of the Orbita brand and ensure fair market practices nationwide, all partners must adhere to our standard pricing guidelines. This guarantees that while you enjoy excellent profit margins, our end-users are never exploited, maintaining absolute trust in the Orbita name.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 2. The 4 Exclusive Regions --}}
            <div class="mb-16 md:mb-20 border-b border-gray-100 pb-16 md:pb-20">
                <div class="text-center mb-10 md:mb-14">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">
                        Exclusive <span class="text-orbita-gold">Territories</span>
                    </h2>
                    <p class="text-gray-500 max-w-2xl mx-auto md:text-lg">
                        We operate on a strict one-partner-per-region model. <strong class="text-orbita-blue">Nairobi County and the Coastal Region are managed exclusively by Orbita Kenya HQ.</strong> The rest of the republic is divided into four highly lucrative, protected distributor regions.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                    {{-- Region 1 --}}
                    <div class="bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] hover:border-orbita-gold hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue/10 rounded-xl flex items-center justify-center text-orbita-blue font-black text-xl">1</div>
                            <h4 class="text-lg md:text-xl font-black uppercase tracking-wider text-gray-900 leading-tight">Central & Lower Eastern</h4>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed pl-16">
                            Kiambu, Nyeri, Kirinyaga, Murang’a, Nyandarua, Machakos, Makueni, Kitui, Embu.
                        </p>
                    </div>
                    {{-- Region 2 --}}
                    <div class="bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] hover:border-orbita-gold hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue/10 rounded-xl flex items-center justify-center text-orbita-blue font-black text-xl">2</div>
                            <h4 class="text-lg md:text-xl font-black uppercase tracking-wider text-gray-900 leading-tight">Upper Eastern & Northern</h4>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed pl-16">
                            Meru, Tharaka Nithi, Isiolo, Marsabit, Mandera, Wajir, Garissa, Samburu.
                        </p>
                    </div>
                    {{-- Region 3 --}}
                    <div class="bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] hover:border-orbita-gold hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue/10 rounded-xl flex items-center justify-center text-orbita-blue font-black text-xl">3</div>
                            <h4 class="text-lg md:text-xl font-black uppercase tracking-wider text-gray-900 leading-tight">Greater Rift Valley</h4>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed pl-16">
                            Nakuru, Kajiado, Narok, Baringo, Laikipia, Uasin Gishu, Nandi, Trans Nzoia, Elgeyo Marakwet, West Pokot, Turkana.
                        </p>
                    </div>
                    {{-- Region 4 --}}
                    <div class="bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] hover:border-orbita-gold hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue/10 rounded-xl flex items-center justify-center text-orbita-blue font-black text-xl">4</div>
                            <h4 class="text-lg md:text-xl font-black uppercase tracking-wider text-gray-900 leading-tight">Western, Nyanza & South Rift</h4>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed pl-16">
                            Kisumu, Kakamega, Bungoma, Busia, Vihiga, Siaya, Homa Bay, Migori, Kisii, Nyamira, Kericho, Bomet.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. Operational Roles --}}
            <div class="mb-16 md:mb-20 border-b border-gray-100 pb-16 md:pb-20">
                <div class="text-center mb-10 md:mb-14">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">
                        Division of <span class="text-orbita-gold">Operations</span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                    {{-- The Partner Card --}}
                    <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-2 bg-orbita-blue"></div>
                        <h4 class="font-black text-gray-900 text-xl md:text-2xl uppercase tracking-widest mb-8 mt-2">The Partner's Role</h4>
                        <ul class="text-sm md:text-base text-gray-600 space-y-6 font-medium">
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-blue/10 text-orbita-blue rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-blue group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Local Sales & Marketing</strong> Actively prospect and acquire hotel, commercial, and residential developer clients within the assigned region.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-blue/10 text-orbita-blue rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-blue group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Physical Installation</strong> Execute professional, on-site hardware installations and software configuration for clients.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-blue/10 text-orbita-blue rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-blue group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Frontline Support</strong> Serve as the primary point of contact for routine technical support and troubleshooting.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-blue/10 text-orbita-blue rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-blue group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Financial Management</strong> Handle all direct billing, invoicing, and payment collection from local clients.</div>
                            </li>
                        </ul>
                    </div>

                    {{-- Orbita Card --}}
                    <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] relative overflow-hidden group hover:border-orbita-gold/50 transition-colors duration-300">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orbita-gold to-yellow-400"></div>
                        <h4 class="font-black text-gray-900 text-xl md:text-2xl uppercase tracking-widest mb-8 mt-2 relative z-10">Orbita HQ's Role</h4>
                        <ul class="text-sm md:text-base text-gray-600 space-y-6 font-medium relative z-10">
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-gold/10 text-orbita-gold rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-gold group-hover:text-white transition-colors border border-orbita-gold/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Wholesale Supply</strong> Provide guaranteed stock availability at exclusive distributor pricing to ensure your profitability.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-gold/10 text-orbita-gold rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-gold group-hover:text-white transition-colors border border-orbita-gold/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Technical Training</strong> Provide comprehensive certification training for your technicians on Orbita systems.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-gold/10 text-orbita-gold rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-gold group-hover:text-white transition-colors border border-orbita-gold/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Warranty & Replacements</strong> Honor all manufacturer warranties and provide rapid hardware replacements for defective units.</div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-orbita-gold/10 text-orbita-gold rounded-full flex items-center justify-center mt-0.5 group-hover:bg-orbita-gold group-hover:text-white transition-colors border border-orbita-gold/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div><strong class="text-gray-900 block mb-1">Lead Forwarding</strong> Reroute any inquiries or leads that originate from your territory directly to your sales team.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 4. Partnership Commitments & Expectations --}}
            <div class="mb-16 md:mb-20 border-b border-gray-100 pb-16 md:pb-20">
                <div class="text-center mb-10 md:mb-14">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">
                        Partnership <span class="text-orbita-gold">Commitments</span>
                    </h2>
                    <p class="text-gray-500 max-w-2xl mx-auto md:text-lg">We believe in building highly profitable, long-term relationships. To ensure mutual success and maintain the premium standard our clients expect, we ask our regional partners to align with the following operational commitments.</p>
                </div>
                
                <div class="bg-white border border-gray-200 shadow-sm rounded-[2rem] p-6 md:p-10 lg:p-12">
                    <div class="space-y-8 md:space-y-10 text-sm md:text-base text-gray-600 leading-relaxed font-medium">
                        
                        {{-- Clause A --}}
                        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                            <span class="font-black text-orbita-gold text-2xl md:text-3xl hidden md:block">A.</span>
                            <div>
                                <h4 class="font-black uppercase tracking-widest text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="md:hidden text-orbita-gold">A.</span> Growth Milestones
                                </h4>
                                <p>To ensure your assigned territory remains actively serviced and highly profitable, partners are expected to achieve a baseline target of <strong>500 smart locks or equivalent products annually</strong> (approximately 125 units per quarter). We will work closely with you to help you hit and exceed these targets.</p>
                            </div>
                        </div>

                        {{-- Clause B --}}
                        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                            <span class="font-black text-orbita-gold text-2xl md:text-3xl hidden md:block">B.</span>
                            <div>
                                <h4 class="font-black uppercase tracking-widest text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="md:hidden text-orbita-gold">B.</span> Initial Market Readiness
                                </h4>
                                <p>To effectively serve your local clients with zero delays, partners begin their journey by purchasing a tailored Starter Package. This includes essential demonstration kits, marketing collateral, and a baseline hardware inventory to confidently launch your local operations.</p>
                            </div>
                        </div>

                        {{-- Clause C --}}
                        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                            <span class="font-black text-orbita-gold text-2xl md:text-3xl hidden md:block">C.</span>
                            <div>
                                <h4 class="font-black uppercase tracking-widest text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="md:hidden text-orbita-gold">C.</span> Excellence in Service
                                </h4>
                                <p>As the exclusive face of Orbita in your region, we rely on you to deliver professional installations and exceptional customer support. Upholding these premium standards protects both your local business reputation and our national brand integrity.</p>
                            </div>
                        </div>
                        
                        {{-- The Review Clause (Softened from the harsh red box) --}}
                        <div class="flex flex-col md:flex-row gap-4 md:gap-6 p-6 md:p-8 bg-gray-50 rounded-2xl border border-gray-200 mt-8">
                            <div class="shrink-0">
                                <svg class="w-10 h-10 md:w-12 md:h-12 text-orbita-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-black text-lg uppercase tracking-widest text-gray-900 mb-2">Territory & Performance Review</h4>
                                <p class="text-gray-600 md:text-lg leading-relaxed">Our primary goal is to support your growth. However, if a region consistently underperforms for <strong>two consecutive quarters</strong>, or if pricing and brand guidelines are breached, Orbita Kenya reserves the right to review the exclusivity agreement. We maintain this policy simply to ensure that no market is left unserved and that all our partners are actively thriving.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 5. Onboarding Process (Responsive Timeline) --}}
            <div>
                <div class="text-center mb-10 md:mb-14">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">
                        The Onboarding <span class="text-orbita-gold">Process</span>
                    </h2>
                </div>
                
                <div class="relative">
                    {{-- Horizontal line for desktop --}}
                    <div class="hidden lg:block absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-1/2 z-0"></div>
                    
                    {{-- Vertical line for mobile/tablet --}}
                    <div class="lg:hidden absolute top-0 left-6 bottom-0 w-0.5 bg-gray-200 z-0"></div>

                    <div class="flex flex-col lg:flex-row justify-between gap-8 lg:gap-0 relative z-10">
                        {{-- Step 1 --}}
                        <div class="flex lg:flex-col items-center gap-6 lg:gap-0 lg:text-center w-full lg:w-[150px] mx-auto bg-white lg:px-2">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue text-white rounded-full flex items-center justify-center font-black text-lg lg:mb-4 shadow-lg ring-4 ring-white">1</div>
                            <div>
                                <h5 class="font-bold text-sm md:text-base uppercase tracking-widest text-gray-900">Application</h5>
                                <p class="text-xs md:text-sm text-gray-500 mt-1 lg:mt-2">Submit the form below</p>
                            </div>
                        </div>
                        {{-- Step 2 --}}
                        <div class="flex lg:flex-col items-center gap-6 lg:gap-0 lg:text-center w-full lg:w-[150px] mx-auto bg-white lg:px-2">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue text-white rounded-full flex items-center justify-center font-black text-lg lg:mb-4 shadow-lg ring-4 ring-white">2</div>
                            <div>
                                <h5 class="font-bold text-sm md:text-base uppercase tracking-widest text-gray-900">HQ Review</h5>
                                <p class="text-xs md:text-sm text-gray-500 mt-1 lg:mt-2">3-5 business days</p>
                            </div>
                        </div>
                        {{-- Step 3 --}}
                        <div class="flex lg:flex-col items-center gap-6 lg:gap-0 lg:text-center w-full lg:w-[150px] mx-auto bg-white lg:px-2">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue text-white rounded-full flex items-center justify-center font-black text-lg lg:mb-4 shadow-lg ring-4 ring-white">3</div>
                            <div>
                                <h5 class="font-bold text-sm md:text-base uppercase tracking-widest text-gray-900">Interview</h5>
                                <p class="text-xs md:text-sm text-gray-500 mt-1 lg:mt-2">Virtual or at Nairobi HQ</p>
                            </div>
                        </div>
                        {{-- Step 4 --}}
                        <div class="flex lg:flex-col items-center gap-6 lg:gap-0 lg:text-center w-full lg:w-[150px] mx-auto bg-white lg:px-2">
                            <div class="w-12 h-12 shrink-0 bg-orbita-blue text-white rounded-full flex items-center justify-center font-black text-lg lg:mb-4 shadow-lg ring-4 ring-white">4</div>
                            <div>
                                <h5 class="font-bold text-sm md:text-base uppercase tracking-widest text-gray-900">Agreement</h5>
                                <p class="text-xs md:text-sm text-gray-500 mt-1 lg:mt-2">Contract & Stock Purchase</p>
                            </div>
                        </div>
                        {{-- Step 5 --}}
                        <div class="flex lg:flex-col items-center gap-6 lg:gap-0 lg:text-center w-full lg:w-[150px] mx-auto bg-white lg:px-2">
                            <div class="w-12 h-12 shrink-0 bg-orbita-gold text-white rounded-full flex items-center justify-center font-black text-lg lg:mb-4 shadow-[0_0_20px_rgba(197,160,89,0.4)] ring-4 ring-white">5</div>
                            <div>
                                <h5 class="font-bold text-sm md:text-base uppercase tracking-widest text-gray-900">Launch</h5>
                                <p class="text-xs md:text-sm text-gray-500 mt-1 lg:mt-2">Training & Market Entry</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- THE GATE (Checkbox) --}}
            <div class="mt-16 md:mt-24 p-6 md:p-8 lg:p-10 bg-gray-900 rounded-[2rem] border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-2xl cursor-pointer hover:bg-gray-800 hover:border-orbita-gold/30 transition-all duration-300" @click="agreed = !agreed; if(agreed) step = 1;">
                <div class="flex items-start md:items-center gap-5">
                    <div class="shrink-0 mt-1 md:mt-0 relative w-8 h-8 md:w-10 md:h-10 border-2 border-orbita-gold rounded-lg flex items-center justify-center transition-colors" :class="agreed ? 'bg-orbita-gold' : 'bg-transparent'">
                        <svg x-show="agreed" class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-lg md:text-xl">I accept the terms and requirements.</h4>
                        <p class="text-gray-400 text-xs md:text-sm mt-1 max-w-2xl leading-relaxed">By checking this box, I formally acknowledge the minimum sales quotas, the exclusivity clauses, and confirm my business has the financial and operational capacity to become a Regional Distributor.</p>
                    </div>
                </div>
                <div x-show="agreed" class="shrink-0 text-orbita-gold font-bold text-sm uppercase tracking-widest animate-bounce mt-4 md:mt-0 md:ml-4 text-center w-full md:w-auto">
                    Scroll Down <br class="hidden md:block"> To Apply ↓
                </div>
            </div>
        </div>

        {{-- MULTI-STEP APPLICATION FORM (Hidden until agreed) --}}
        <div x-show="agreed" 
             x-transition 
             x-cloak
             class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-2xl border-t-8 border-orbita-blue overflow-hidden mt-10">
             
            {{-- Form Header & Progress Bar --}}
            <div class="bg-gray-50 px-6 md:px-16 py-10 md:py-12 border-b border-gray-100">
                <div class="text-center mb-8 md:mb-10">
                    <h2 class="text-2xl md:text-4xl font-black text-orbita-blue uppercase tracking-tighter">Official Application</h2>
                    <p class="text-gray-500 text-sm md:text-base mt-2">Complete the 3 stages below to submit your business profile.</p>
                </div>

                {{-- Progress Indicator --}}
                <div class="relative max-w-lg mx-auto">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -translate-y-1/2 rounded-full z-0"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-orbita-gold -translate-y-1/2 rounded-full z-0 transition-all duration-500" :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                    
                    <div class="relative z-10 flex justify-between">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-colors duration-300" :class="step >= 1 ? 'bg-orbita-gold text-white' : 'bg-gray-200 text-gray-400'">1</div>
                            <span class="text-[10px] md:text-xs uppercase font-bold mt-2 tracking-widest" :class="step >= 1 ? 'text-orbita-blue' : 'text-gray-400'">Company</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-colors duration-300" :class="step >= 2 ? 'bg-orbita-gold text-white' : 'bg-gray-200 text-gray-400'">2</div>
                            <span class="text-[10px] md:text-xs uppercase font-bold mt-2 tracking-widest" :class="step >= 2 ? 'text-orbita-blue' : 'text-gray-400'">Contact</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm transition-colors duration-300" :class="step >= 3 ? 'bg-orbita-gold text-white' : 'bg-gray-200 text-gray-400'">3</div>
                            <span class="text-[10px] md:text-xs uppercase font-bold mt-2 tracking-widest" :class="step >= 3 ? 'text-orbita-blue' : 'text-gray-400'">Strategy</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- UPDATED ACTION ROUTE --}}
            <form action="{{ route('partnership.store') }}" method="POST" class="p-6 md:p-12 lg:p-16">
                @csrf
                
                {{-- STEP 1: COMPANY PROFILE --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6 md:space-y-8">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-6">Company Profile</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Registered Company Name *</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="e.g. Acme Security Solutions Ltd">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Company KRA PIN *</label>
                            <input type="text" name="kra_pin" value="{{ old('kra_pin') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="P000000000A">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Primary Industry/Niche *</label>
                            <select name="business_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition appearance-none">
                                <option value="" disabled {{ old('business_type') ? '' : 'selected' }}>Select your core business...</option>
                                <option value="Security Systems Installer" {{ old('business_type') == 'Security Systems Installer' ? 'selected' : '' }}>Security Systems Installer</option>
                                <option value="IT & Networking Setup" {{ old('business_type') == 'IT & Networking Setup' ? 'selected' : '' }}>IT & Networking Setup</option>
                                <option value="General Hardware/Supplier" {{ old('business_type') == 'General Hardware/Supplier' ? 'selected' : '' }}>General Hardware / Supplier</option>
                                <option value="Real Estate Developer" {{ old('business_type') == 'Real Estate Developer' ? 'selected' : '' }}>Real Estate Developer</option>
                                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Years in Business *</label>
                            <input type="number" name="years_active" value="{{ old('years_active') }}" min="1" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="e.g. 5">
                        </div>
                    </div>
                </div>

                {{-- STEP 2: CONTACT DETAILS --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;" class="space-y-6 md:space-y-8">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-6">Contact & Location</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Lead Contact Person *</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="Director / General Manager Name">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Official Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="director@company.co.ke">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Direct Phone Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="07XX XXX XXX">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Physical Office/Shop Location *</label>
                            <input type="text" name="physical_address" value="{{ old('physical_address') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition" placeholder="e.g. Links Road, Nyali, Mombasa">
                        </div>
                    </div>
                </div>

                {{-- STEP 3: STRATEGIC FIT --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;" class="space-y-6 md:space-y-8">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-6">Partnership Strategy</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Target Territory *</label>
                            <select name="region" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition appearance-none font-bold text-orbita-blue md:text-lg">
                                <option value="" disabled {{ old('region') ? '' : 'selected' }}>Select Region...</option>
                                <option value="Central & Lower Eastern" {{ old('region') == 'Central & Lower Eastern' ? 'selected' : '' }}>Central & Lower Eastern</option>
                                <option value="Upper Eastern & Northern" {{ old('region') == 'Upper Eastern & Northern' ? 'selected' : '' }}>Upper Eastern & Northern</option>
                                <option value="Greater Rift Valley" {{ old('region') == 'Greater Rift Valley' ? 'selected' : '' }}>Greater Rift Valley</option>
                                <option value="Western, Nyanza & South Rift" {{ old('region') == 'Western, Nyanza & South Rift' ? 'selected' : '' }}>Western, Nyanza & South Rift</option>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-2 italic font-medium">*Note: Nairobi and Coastal regions are managed directly by Orbita HQ and are not available for distributorship.</p>
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Current Staff/Tech Size *</label>
                            <select name="team_size" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition appearance-none">
                                <option value="" disabled {{ old('team_size') ? '' : 'selected' }}>Select team size...</option>
                                <option value="1-5" {{ old('team_size') == '1-5' ? 'selected' : '' }}>1 - 5 Employees</option>
                                <option value="6-15" {{ old('team_size') == '6-15' ? 'selected' : '' }}>6 - 15 Employees</option>
                                <option value="16-30" {{ old('team_size') == '16-30' ? 'selected' : '' }}>16 - 30 Employees</option>
                                <option value="30+" {{ old('team_size') == '30+' ? 'selected' : '' }}>30+ Employees</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Why are you the right partner for this region? *</label>
                        <textarea name="proposal" rows="5" placeholder="Briefly describe your current client base (e.g., hotels, developers) and how you plan to achieve the target of 1,000 unit sales..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 md:py-5 focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none transition">{{ old('proposal') }}</textarea>
                    </div>
                </div>

                {{-- WIZARD NAVIGATION BUTTONS --}}
                <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col-reverse md:flex-row items-center justify-between gap-4">
                    {{-- Previous Button --}}
                    <div class="w-full md:w-auto">
                        <button type="button" x-show="step > 1" @click="step--" class="w-full md:w-auto px-6 py-4 bg-gray-100 text-gray-700 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Back
                        </button>
                    </div>

                    {{-- Next / Submit Buttons --}}
                    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
                        <button type="button" x-show="step < 3" @click="step++" class="w-full md:w-auto px-8 py-4 bg-orbita-blue text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-orbita-gold transition-colors flex items-center justify-center gap-2 shadow-lg">
                            Next Step
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        
                        <button type="submit" x-show="step === 3" class="w-full md:w-auto px-10 py-5 bg-orbita-gold text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl hover:bg-orbita-blue transition-colors shadow-[0_10px_30px_-10px_rgba(212,175,55,0.6)]">
                            Submit Application
                        </button>
                    </div>
                </div>

            </form>
        </div>
        
    </div>
</section>

@endsection