<x-app-layout>
    <div class="checkout-success">
        {{$user->name}},感謝您的購買~
    </div>
@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>
