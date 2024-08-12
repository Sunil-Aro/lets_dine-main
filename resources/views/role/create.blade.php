<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Role!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="role_form" action="{{ route('role.store') }}" method="POST">
                        @csrf
                        <!-- Role Name -->
                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <x-text-input id="role" class="block mt-1 w-full" type="text" name="role" :value="old('role')" required autofocus autocomplete="role" />
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <br>
                        <div class="mb-4">
                            <label for="guard_name" class="block text-sm text-gray-600 mb-2">Guard Name:</label>
                            <select id="guard_name" name="guard_name" onchange="" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="admin">Admin</option>
                                <option value="web">Web</option>
                            </select>
                        </div>
                        <br>
                        <!-- Permissions -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                        <div id="filtered-results">

                        </div>
                            {{-- @foreach ($permissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" id="permission_{{ $permission->id }}" value="{{ $permission->name }}" name="permissions[]" class="mr-2">
                                    <label for="permission_{{ $permission->id }}" class="text-sm text-gray-600">
                                        <span style="padding-left: 1rem">{{ $permission->name }}</span>
                                    </label>
                                </div>
                            @endforeach --}}
                        </div>
                        <br>
                        <div class="flex justify-end">
                            <x-primary-button class="ms-2">
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#guard_name').on('change', function() {
            var guardName = $(this).val();

            $.ajax({
                url: '{{route('role.guard_filter')}}', // The URL to your filtering route
                method: 'GET',
                data: { guard_name: guardName },
                success: function(response) {
                    $('#filtered-results').empty();
                    if (response.length > 0) {
                        $.each(response, function(index, permissionName) {
                            var checkboxHtml = `
                                <div class="flex items-center">
                                    <input type="checkbox" id="permission_${index}" value="${permissionName}" name="permissions[]" class="mr-2">
                                    <label for="permission_${index}" class="text-sm text-gray-600">
                                        <span style="padding-left: 1rem">${permissionName}</span>
                                    </label>
                                </div>
                            `;
                            $('#filtered-results').append(checkboxHtml);
                        });
                    } else {
                        $('#filtered-results').append('<p>No permissions found for this guard.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#filtered-results').append('<p>There was an error retrieving the permissions.</p>');
                }
            });
        });
    });

</script>

<script>
    $(document).ready(function() {
        $('#role_form').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('role.store') }}',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.message) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.replace("{{ route('role.list') }}");
                        }, 1000);
                    } else {
                        toastr.success("Permission Added Successfully!");
                    }
                    // $("#refresh_div").load(location.href + " #refresh_div");
                },
                error: function(xhr, status, error) {
                    let errorMsg = "An error occurred. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    toastr.error(errorMsg);
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

