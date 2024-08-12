<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Permission') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="edit_permission_form" action="{{route('permission.update')}}">
                        @csrf
                        <!-- Permission Name -->
                        <div>
                            <input type="hidden" id="id" name="id" value="{{$roles->id}}">
                            <x-input-label for="role" :value="__('Edit Roles')" />
                            <x-text-input id="role" value="{{$roles->name}}" class="block mt-1 w-full" type="text" name="role" required/>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <br>
                        <div class="mb-4">
                            <label for="guard_name" class="block text-sm text-gray-600 mb-2">Guard Name:</label>
                            <select id="guard_name" name="guard_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="admin">Admin</option>
                                <option value="web">Web</option>
                            </select>
                        </div>
                        <br>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($permissions as $permission)
                                <div class="flex items-center">
                                    <input {{($hasPermissions)->contains($permission->name) ? 'checked' : ''}} type="checkbox" id="permission_{{ $permission->id }}" value="{{ $permission->name }}" name="permissions[]" class="mr-2">
                                    <label for="permission_{{ $permission->id }}" class="text-sm text-gray-600">
                                        <span style="padding-left: 1rem" >{{ $permission->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div style="padding-left: 66rem">
                            <x-primary-button class="ms-2">
                                {{ __('Update') }}
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
        $('#edit_permission_form').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('role.update') }}',
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
                        toastr.success("Role Updated Successfully!");
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

