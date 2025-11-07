<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showToast(type = 'success', message = '', position = 'top-end', timer = 3000) {
        Swal.fire({
            toast: true,
            position: position,
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: timer,
            timerProgressBar: true,
        });
    }

    @if (session('toast'))
        showToast('{{ session('toast.type') }}', '{{ session('toast.message') }}');
    @endif
</script>
