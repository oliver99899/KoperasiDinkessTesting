@if(session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 4000)" 
         x-show="show"
         x-transition.duration.300ms
         class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-start gap-3 shadow-sm">
        <i class="ph-fill ph-check-circle text-xl mt-0.5 shrink-0"></i>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 4000)" 
         x-show="show"
         x-transition.duration.300ms
         class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-start gap-3 shadow-sm">
        <i class="ph-fill ph-warning-circle text-xl mt-0.5 shrink-0"></i>
        <span class="font-bold text-sm">{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-show="show"
         x-transition.duration.300ms
         class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <i class="ph-fill ph-warning-circle text-xl shrink-0"></i>
            <span class="font-bold text-sm">Terjadi Kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-sm ml-7 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif