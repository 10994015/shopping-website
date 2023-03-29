<x-app-layout>
    <div class="reset-password">
        <form action="{{route('profile.update-password')}}" method="post">
            @csrf
            <label for="">
                <p>請輸入原密碼 <span>*</span></p>
                <x-input type="password" name="old_password" class="mt-1"  :errors="$errors" required />
            </label>
            <label for="">
                <p>請輸入新密碼 <span>*</span></p>
                <x-input type="password" name="new_password" class="mt-1"  :errors="$errors" required />
            </label>
            <label for="">
                <p>確認新密碼 <span>*</span></p>
                <x-input type="password" name="new_password_confirmation" class="mt-1"  :errors="$errors" required />
            </label>
            <label for="">
                <button type="submit">儲存變更</button>
            </label>
        </form>
    </div>
@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>