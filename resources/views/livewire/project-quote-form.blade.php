<div class="max-w-5xl mx-auto py-12 px-4">
    <form wire:submit.prevent="submit" class="bg-white p-8 md:p-14 rounded-[3.5rem] shadow-2xl space-y-12 border border-gray-100">
        
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-black text-orbita-blue uppercase tracking-tighter">Project Quotation</h1>
            <p class="text-orbita-gold font-black uppercase tracking-[0.3em] text-[10px]">Hospitality & Security Enterprise Solutions</p>
        </div>

        {{-- SECTION 1: PROPERTY & CLIENT --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Property Name</label>
                <input type="text" wire:model="hotel_name" placeholder="e.g. Sarova Stanley" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Property Type</label>
                <select wire:model="property_type" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm">
                    <option value="">Select...</option>
                    <option value="hotel">Hotel / Resort</option>
                    <option value="apartment">Apartment</option>
                    <option value="hospital">Hospital</option>
                    <option value="school">School / University</option>
                    <option value="residence">Residence Home</option>
                    <option value="office">Corporate Office</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Mobile Number</label>
                <input type="text" wire:model="mobile_number" class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm">
            </div>
        </div>

        {{-- SECTION 2: MULTI-PRODUCT SELECTION --}}
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xs font-black uppercase tracking-widest text-orbita-blue">Equipment & Supplies</h3>
                <button type="button" wire:click="addItem" class="bg-orbita-blue text-white px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-orbita-gold transition">+ Add Item</button>
            </div>
            
            <div class="space-y-3">
                @foreach($selectedItems as $index => $item)
                <div class="flex flex-col md:flex-row gap-4 p-5 bg-gray-50 rounded-3xl items-center border border-gray-100 group">
                    <div class="flex-1 w-full">
                        <select wire:model.live="selectedItems.{{ $index }}.product_id" class="w-full border-none bg-white rounded-xl p-4 text-sm font-bold shadow-sm">
                            <option value="">Select Product / Accessory...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (KES {{ number_format($product->price) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-[9px] font-black uppercase text-gray-400">Qty</label>
                        <input type="number" wire:model.live="selectedItems.{{ $index }}.quantity" class="w-24 border-none bg-white rounded-xl p-4 text-center font-black shadow-sm" min="1">
                        @if(count($selectedItems) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-600 font-bold ml-2">✕</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- SECTION 3: TECHNICAL DETAILS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 p-8 bg-gray-50 rounded-[3rem] border border-gray-100">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Door Type (For Locks)</label>
                    <select wire:model="door_type" class="w-full bg-white border-none rounded-2xl p-4 text-sm shadow-sm">
                        <option value="wood">Wooden Door</option>
                        <option value="aluminum">Aluminum Profile</option>
                        <option value="glass">Glass Door</option>
                        <option value="steel">Steel Security Door</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Project Stage</label>
                    <select wire:model="project_stage" class="w-full bg-white border-none rounded-2xl p-4 text-sm shadow-sm">
                        <option value="new">New Construction</option>
                        <option value="ongoing">Ongoing Project</option>
                        <option value="replacement">Replacement / Upgrade</option>
                    </select>
                </div>
            </div>
            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Upload Door Sample (Optional)</label>
                <div class="border-2 border-dashed border-gray-200 rounded-3xl p-6 text-center hover:border-orbita-gold transition cursor-pointer relative">
                    <input type="file" wire:model="door_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    @if ($door_image)
                        <span class="text-xs font-bold text-green-500">✓ Image Selected</span>
                    @else
                        <span class="text-xs font-bold text-gray-400">Click to upload photo</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION 4: LOGISTICS & PAYMENT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="p-6 border-2 border-gray-100 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="font-black text-orbita-blue text-[11px] uppercase tracking-widest">Include Installation</p>
                        <p class="text-[9px] text-gray-400">Professional setup by Orbita engineers</p>
                    </div>
                    <input type="checkbox" wire:model.live="requires_installation" class="w-6 h-6 rounded-lg text-orbita-gold focus:ring-orbita-gold border-gray-200">
                </div>
                
                <select wire:model.live="location_type" class="w-full bg-gray-50 border-none rounded-2xl p-5 text-sm font-bold">
                    <option value="">Select Shipping Region...</option>
                    <option value="nairobi">Nairobi Region</option>
                    <option value="coast">Coast Region (Mombasa/Diani)</option>
                    <option value="rift">Rift Valley (Nakuru/Eldoret)</option>
                    <option value="others">Other Regions</option>
                </select>
            </div>

            <div class="p-8 bg-orbita-blue rounded-[2.5rem] text-white space-y-4 shadow-xl">
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest opacity-60">
                    <span>Estimated Total</span>
                    <span>KES {{ number_format($grandTotal) }}</span>
                </div>
                <div class="h-[1px] bg-white/10"></div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-orbita-gold">Required Deposit (60%)</p>
                    <h2 class="text-3xl font-black italic">KES {{ number_format($depositRequired) }}</h2>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="button" wire:click="$set('payment_plan', 'one-time')" class="flex-1 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $payment_plan == 'one-time' ? 'bg-white text-orbita-blue' : 'border-white/20' }}">One-time</button>
                    <button type="button" wire:click="$set('payment_plan', 'installment')" class="flex-1 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $payment_plan == 'installment' ? 'bg-white text-orbita-blue' : 'border-white/20' }}">Installments</button>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-orbita-gold py-7 rounded-full text-white font-black uppercase tracking-[0.3em] text-xs hover:bg-orbita-blue hover:shadow-2xl transition-all duration-500">
            Submit Request for Quotation
        </button>
    </form>
</div>