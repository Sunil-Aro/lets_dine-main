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
                    <form id="edit_permission_form" action="{{route('article.update')}}">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{$articles->id}}">
                        <br>
                        <div>
                            <x-input-label for="username" :value="__('User Name')" />
                            <x-text-input style="opacity: 50%" id="username" class="block mt-1 w-full" type="text" name="username" value="{{$articles->username}}" disabled required/>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        <br>
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input style="opacity: 50%" id="email" class="block mt-1 w-full" type="email" name="email"  value="{{$articles->email}}" disabled required/>
                            <small style="color: red">*Note: Email gets from log in user</small>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <br>
                        <div>
                            <x-input-label for="title" :value="__('Article Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{$articles->title}}" required autofocus autocomplete="title" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <br>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{$articles->description}}" required autofocus autocomplete="description" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <br>
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
                url: '{{ route('article.update') }}',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.message) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.replace("{{ route('article.list') }}");
                        }, 1000);
                    } else {
                        toastr.success("Article Updated Successfully!");
                    }
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

