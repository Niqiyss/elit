<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ Auth::guard('admin')->user()->staffname }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl px-8 py-7 shadow-xl overflow-hidden mb-8">
                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-3xl font-extrabold text-white">Feedback Observation Form Versions</h1>
                    <p class="text-violet-300 mt-2">View and manage saved versions</p>
                </div>
            </div>


            @if(session('success'))
            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            @if($forms->isEmpty())

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-24">

                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Create Feedback Observation Form</h2>
                    <p class="text-sm text-gray-400 mt-1">Create the first version of the form</p>
                </div>

                <div class="p-6">

                    <form method="POST" action="{{ route('admin.post.form.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Form Name</label>
                                <input type="text" name="form_name" value="{{ old('form_name') }}" required placeholder="Feedback Observation Form" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Instruction</label>
                                <input type="text" name="instruction" value="{{ old('instruction') }}" placeholder="Enter instruction" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">Create Form</button>
                        </div>

                    </form>

                </div>
            </div>

            @else

            @php
            $activeForm = $forms->firstWhere('status', 'Active');
            @endphp

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm mb-24">

                <div class="overflow-x-auto rounded-3xl">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 border-b border-gray-200">
                            <tr class="text-xs uppercase tracking-wide text-gray-900">
                                <th class="px-6 py-4 text-left w-32">Version</th>
                                <th class="px-6 py-4 text-left">Form Content</th>
                                <th class="px-6 py-4 text-center w-32">Status</th>
                                <th class="px-6 py-4 text-center w-32">Usage</th>
                                <th class="px-6 py-4 text-center w-24">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach($forms as $form)

                            <tr class="{{ $form->status === 'Active' ? 'bg-blue-50/40' : 'bg-white' }} hover:bg-slate-50 transition">

                                <td class="px-6 py-6">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg font-bold">V{{ $form->version }}</div>
                                </td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-gray-900 mb-2">{{ $form->form_name }}</p>

                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-gray-500">
                                        <span><strong class="text-gray-900">{{ $form->section_count }}</strong> Sections</span>
                                        <span><strong class="text-gray-900">{{ $form->field_count }}</strong> Fields</span>
                                    </div>

                                    @if($form->instruction)
                                    <p class="text-gray-400 mt-2">{{ $form->instruction }}</p>
                                    @else
                                    <p class="text-gray-300 mt-2">No instruction</p>
                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($form->status === 'Active')
                                    <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Active</span>
                                    @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">Inactive</span>
                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($form->is_used)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Used</span>
                                    @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Not Used</span>
                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <button type="button"
                                        data-preview="{{ route('admin.post.form.show', $form->formID) }}"
                                        data-edit="{{ route('admin.post.form.edit', $form->formID) }}"
                                        data-delete="{{ !$form->is_used ? route('admin.post.form.delete', $form->formID) : '' }}"
                                        data-version="{{ $form->version }}"
                                        onclick="openActionMenu(this)"
                                        class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-gray-500 hover:text-gray-800 hover:bg-gray-200 transition">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="currentColor"
                                            viewBox="0 0 24 24">

                                            <circle cx="12" cy="5" r="1.7"></circle>
                                            <circle cx="12" cy="12" r="1.7"></circle>
                                            <circle cx="12" cy="19" r="1.7"></circle>

                                        </svg>

                                    </button>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>
            </div>

            @endif

        </div>
    </div>


    <div id="floatingActionMenu" class="hidden fixed w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-[9999] overflow-hidden">

        <a id="actionPreview" href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            Preview
        </a>

        <a id="actionEdit" href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-t border-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.1 2.1 0 013 2.97L8.4 17.92 4 19l1.08-4.4L16.862 3.487z" />
            </svg>
            Edit
        </a>

        <form id="actionDeleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <button id="actionDeleteButton" type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50 border-t border-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                </svg>
                Delete
            </button>
        </form>

    </div>


    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">
        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between gap-3">

                    <a href="{{ route('admin.manage.form') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">Back</a>

                    @if($forms->isNotEmpty() && $activeForm)

                    <form method="POST" action="{{ route('admin.post.form.new-version', $activeForm->formID) }}">
                        @csrf

                        <button type="submit" onclick="return confirm('Create a new version based on Version {{ $activeForm->version }}?')" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl transition">+ New Version</button>
                    </form>

                    @endif

                </div>

            </div>

        </div>
    </div>

    <script>
        function openActionMenu(button) {
            const menu = document.getElementById('floatingActionMenu');
            const preview = document.getElementById('actionPreview');
            const edit = document.getElementById('actionEdit');
            const deleteForm = document.getElementById('actionDeleteForm');
            const deleteButton = document.getElementById('actionDeleteButton');

            preview.href = button.dataset.preview;
            edit.href = button.dataset.edit;

            if (button.dataset.delete) {
                deleteForm.action = button.dataset.delete;
                deleteForm.classList.remove('hidden');
                deleteButton.onclick = function() {
                    return confirm('Delete Version ' + button.dataset.version + '?');
                };
            } else {
                deleteForm.classList.add('hidden');
            }

            const rect = button.getBoundingClientRect();
            const width = 176;
            const height = button.dataset.delete ? 145 : 98;

            let left = rect.right - width;
            let top = rect.bottom + 6;

            if (left < 10) left = 10;
            if (top + height > window.innerHeight - 10) top = rect.top - height - 6;

            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
            menu.classList.remove('hidden');
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('floatingActionMenu');
            if (!event.target.closest('[onclick="openActionMenu(this)"]') && !event.target.closest('#floatingActionMenu')) menu.classList.add('hidden');
        });

        window.addEventListener('scroll', function() {
            document.getElementById('floatingActionMenu').classList.add('hidden');
        }, true);

        window.addEventListener('resize', function() {
            document.getElementById('floatingActionMenu').classList.add('hidden');
        });
    </script>

</x-app-layout>