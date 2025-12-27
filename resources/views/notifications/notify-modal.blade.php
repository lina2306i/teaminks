<!--  ⚠️ $user est passé depuis le controller
    Notify button -->
<button onclick="openNotifyModal()"
    class="border border-cyan-600 text-cyan-600 rounded-lg px-3 py-1 hover:bg-gray-700">
    notify
</button>

<!-- Modal -->
<div id="notifyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-gray-800 w-1/2 rounded-lg p-6 relative">

        <!-- Close -->
        <button onclick="closeNotifyModal()" class="absolute top-3 right-3 text-gray-400 hover:text-white">
            ✕
        </button>

        <h2 class="text-white text-xl mb-4">
            notify : {{ $user->name }}
        </h2>

        <form method="POST" action="{{ route('notify.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="to" value="{{ $user->id }}">

            <!-- Title -->
            <div>
                <label class="text-white text-lg">Title</label>
                <input type="text" name="title"
                    value="{{ old('title') }}"
                    required
                    placeholder="Notification title"
                    class="w-full mt-2 bg-gray-700 text-white rounded-lg px-4 py-2">
                @error('title')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div>
                <label class="text-white text-lg">Message</label>
                <textarea name="message" rows="4" required
                    class="w-full mt-2 bg-gray-700 text-white rounded-lg px-4 py-2"
                    placeholder="Notification message">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label class="text-white text-lg">Type</label>
                <select name="type"
                    class="w-full mt-2 bg-gray-700 text-white rounded-lg px-4 py-2">
                    <option value="success">success</option>
                    <option value="info" selected>info</option>
                    <option value="warning">warning</option>
                    <option value="error">error</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-800 text-white px-5 py-2 rounded-lg">
                    Send
                </button>
            </div>
        </form>

    </div>
</div>


@push('script')

<script>
function openNotifyModal() {
    document.getElementById('notifyModal').classList.remove('hidden');
    document.getElementById('notifyModal').classList.add('flex');
}

function closeNotifyModal() {
    document.getElementById('notifyModal').classList.add('hidden');
    document.getElementById('notifyModal').classList.remove('flex');
}
</script>

@endpush
